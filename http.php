<?php

$index = 1;
$usleep = false;
$option = [
    "max_coroutine" => 1, // max coroutine number can be created, the default value is 4096
    "stack_size" => 8192, // set the stack size of each coroutine, the default value is 8192
];
Swoole\Coroutine::set($option);



$http = new swoole_http_server("127.0.0.1", 9501);

$http->on("start", function ($server) {
    echo "Swoole http server is started at http://127.0.0.1:9501\n";
});

$chan = new chan(1);

$http->on("request", function ($request, $response) use(&$index,&$usleep) {
    echo var_export(Swoole\Coroutine::stats(),true)."\n";
    $index++;

    if($index % 2 && !$usleep) {
        $usleep = true;
        Swoole\Coroutine::sleep(100);
        $usleep = false;
    }
    // $coros = Swoole\Coroutine::listCoroutines();
    // foreach($coros as $cid)
    // {
    //     echo var_export(Swoole\Coroutine::getBackTrace($cid),true)."\n";
    // }

    $id = co::getUid();
    echo "Swoole http server is started at http://127.0.0.1:9501 and index is : {$index} with {$id} cid\n";
    $response->header("Content-Type", "text/plain");
    $response->end("Hello World\n");
});

$http->start();

