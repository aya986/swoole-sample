<?php

require 'config.php';
$chan  = new Chan(1);
$mysql;
// $chan = new co\Channel(1);
$s = microtime(true);

go(function () use (&$mysql,$config) {
    $mysql = new Swoole\Coroutine\MySQL;
    $mysql->connect($config['mysql']);
});

go(function () use ($chan, &$mysql,$config) {
    echo "start first corotine\n\n";
    $mysql = new Swoole\Coroutine\MySQL;
    $mysql->connect($config['mysql']);
    $prepare = $mysql->prepare('SELECT * FROM `domains` limit 1');
    $results = $prepare->execute();
    echo var_export($results, true) . "\n\n";
    $chan->push(['data' => $results[0] ?? []]);
    echo "end first corotine\n\n";
});



go(function () use ($chan) {
    echo "start seconf crotine \n\n";
    $data = $chan->pop();
    echo var_export($data['data'], true) . "\n\n";
});


echo "\n\n use " . (microtime(true) - $s) . " s \n";
