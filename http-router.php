<?php


$option = [
    "max_coroutine" => 1, // max coroutine number can be created, the default value is 4096
    "stack_size" => 8192, // set the stack size of each coroutine, the default value is 8192
];
Swoole\Coroutine::set($option);

$http = new swoole_http_server("0.0.0.0", 9501);


$http->on("start", function ($server) {
    echo "Swoole http server is started at http://0.0.0.0:9501\n";
});

$http->on("request", function ($request, $response) {

    echo "uri is : " . $request->server['request_uri'] . "\n";
    $path = "route" .$request->server['request_uri'];
    switch ($request->server['request_uri']) {
        case '/':
            require 'route/index.php'; // http://127.0.0.1:9501/
            break;
        default:
            if (file_exists($path)) {
                require $path; // example http://127.0.0.1:9501/aya.php
            } else {
                require "route/404.php"; // invalid uri
            }
            break;
    }
    $response->header("Content-Type", "text/plain");
    $response->end("Hello World\n");
});

$http->start();
