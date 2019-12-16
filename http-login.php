<?php

$table = new Swoole\Table(1024);
$table->column('id', Swoole\Table::TYPE_STRING, 64);
$table->column('username', Swoole\Table::TYPE_STRING, 64);
$table->create();

$option = [
    "max_coroutine" => 10, // max coroutine number can be created, the default value is 4096
    "stack_size" => 8192, // set the stack size of each coroutine, the default value is 8192
];
Swoole\Coroutine::set($option);

$http = new swoole_http_server("0.0.0.0", 9501);


$http->on("start", function ($server) {
    echo "Swoole http server is started at http://0.0.0.0:9501\n";
});

$http->on("request", function ($request, $response) use ($table) {

    echo var_export($request,true)."\n\n";
    
    echo "new request \n\n";
    $username = $userId = $welcome = $view = "";
    if ($request->server['request_method'] == 'POST') {
        echo var_export($request->post, true);
        if ($user = getUser($request->post['username'], $request->post['password'])) {
            $userId = $user['id'] . ":" . rand(1000000, 9999999);
            $table[$userId] = [
                'id' => $userId,
                'username' => $user['username']
            ];
            $response->cookie('userId', $userId, time() + 86400, '/');
            
            $response->redirect('http://127.0.0.1:9501/login');
            return $response->end();
        }
    } else {
        $userId = $request->cookie['userId'];
        $uri = $request->server['request_uri'];
        
        $authUsers = (array) $table;

        foreach($table as $row)
        {
            if($row['id'] == $userId) {
                $username = $row['username'];
            }
        }
        if (empty($username) && !in_array($uri, ['/login'])) {
            $response->status(401);
            return $response->end();
        }
    }

    echo "\n\n tabel ith userId :{$userId} " . var_export($table[$userId], true) . "\n\n";

    switch ($request->server['request_uri']) {
        case '/logout':
            $table->del($userId);
            return $response->redirect('http://127.0.0.1:9501/login');
            break;
            case '/login':
                $view = file_get_contents('route/login-form.php'); // http://127.0.0.1:9501/
                break;
        default:
            $response->status(404);
            return $response->end();
    }
    // echo "view is : $view\n";
    if(!empty($username)) {
        // $welcome = "<pre>" . var_export($table['users'],true) . "<pre>";
        $count  = $table->count();
        $welcome = $username ? "welcome {$username} with user Id {$userId} and lount of loged in users is: {$count}<br/> <a href='/logout'>logout</a>" : '';
    }

    $response->header("Content-Type", "text/html");

    $response->end($welcome . $view);
    // $response->end($welcome.$view."<pre>".var_export($request, true)."</pre>");
});

function getUser($username, $password)
{
    $users = [
        'aya' => [
            'id' => 100,
            'username' => 'aya',
            'nickname' => 'ayoob',
            'password' => '123456'
        ],
        'ali' => [
            'id' => 101,
            'username' => 'ali',
            'nickname' => 'ali',
            'password' => '123456'
        ]
    ];
    if (empty($users[$username])) {
        return null;
    }
    if ($users[$username]['password'] != $password) {
        return null;
    }
    return $users[$username];
}

$http->start();
