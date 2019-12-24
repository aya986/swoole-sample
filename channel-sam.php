<?php

go(function () {
    // User: I need you to bring me some information back.
    // Channel: OK! I will be responsible for scheduling.
    $channel = new Swoole\Coroutine\Channel;
    go(function () use ($channel) {
        // Coroutine A: Ok! I will show you the github addr info
        $addr_info = Co::getaddrinfo('github.com');
        $channel->push(['A', json_encode($addr_info, JSON_PRETTY_PRINT)]);
    });
    go(function () use ($channel) {
        // Coroutine B: Ok! I will show you what your code look like
        $mirror = Co::readFile(__FILE__);
        $channel->push(['B', $mirror]);
    });
    go(function () use ($channel) {
        // Coroutine C: Ok! I will show you the date
        $channel->push(['C', date(DATE_W3C)]);
    });
    for ($i = 3; $i--;) {
        list($id, $data) = $channel->pop();
        echo "From {$id}:\n {$data}\n";
    }
    // User: Amazing, I got every information at earliest time!
});

?>