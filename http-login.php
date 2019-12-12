<?php


$option = [
    "max_coroutine" => 10, // max coroutine number can be created, the default value is 4096
    "stack_size" => 8192, // set the stack size of each coroutine, the default value is 8192
];
Swoole\Coroutine::set($option);

$http = new swoole_http_server("0.0.0.0", 9501);


$http->on("start", function ($server) {
    echo "Swoole http server is started at http://0.0.0.0:9501\n";
});

$http->on("request", function ($request, $response) {

    $view = "";
    if($request->server['request_method'] == 'POST') {
        echo var_export($request->post, true);
        if($request->post['username'] == 'aya' && $request->post['password'] == 'pass') {
            $username = $request->post['username'];
            $response->cookie('username', $request->post['username'], time() + 86400, '/');
        }
    } else {
        $username = $request->cookie['username'];
    }
    

    switch ($request->server['request_uri']) {
        case '/login':
            $view = file_get_contents('route/login-form.php'); // http://127.0.0.1:9501/
            break;
        default:
            $response->status(404);
            return $response->end();
    }
    // echo "view is : $view\n";
    $welcome = $username ? "welcome {$username}<br/>" : '';
    $response->header("Content-Type", "text/html");

    $response->end($welcome.$view);
    // $response->end($welcome.$view."<pre>".var_export($request, true)."</pre>");
});

$http->start();
