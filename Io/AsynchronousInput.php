<?php

namespace Io;

class AsynchronousInput implements InputInterface
{
    public function read($fd)
    {
        $read = [$fd];
        $write = [];
        $except = [];
        $result = stream_select($read, $write, $except, 0);
        if ($result === false)
            throw new RuntimeException('Cannot read fd `'.$fd.'` asynchronously');
        if ($result === 0)
            return false;
        $data = stream_get_line($fd, 80, PHP_EOL);
        return $data;
    }
}