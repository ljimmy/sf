<?php

namespace SF\Application\Event;

use Swoole\Server;

class BufferEmpty extends AbstractServerEvent
{
    public function getName(): string
    {
        return 'BufferEmpty';
    }

    public function getCallback(): \Closure
    {
        return function (Server $server, $fd) {

        };
    }

}
