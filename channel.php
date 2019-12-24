<?php
use Swoole\Coroutine as co;
$chan = new co\Channel(1);
co::create(function () use ($chan) {
    for($i = 0; $i < 100000; $i++) {
        co::sleep(3.0);
        $chan->push(['rand' => rand(1000, 9999), 'index' => $i]);
        // echo "$i\n";
    }
});
co::create(function () use ($chan) {
    while(1) {
        echo "\n\n 2 co routine \n";
        $data = $chan->pop();
        echo "\n ------ \n data ready in channel id: ".$data['index']." and rand is: ".$data['rand']."\n ------ \n";
        // var_dump($data);
    }
});
swoole_event::wait();