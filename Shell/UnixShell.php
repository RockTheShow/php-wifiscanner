<?php

namespace Shell;

use LogicException;
use Shell\ShellInterface;
use Shell\UnixOutput;
use Filesystem\NamedPipeInterface;

class UnixShell implements ShellInterface
{
    public function exec($command)
    {
        $outArray = '';
        $statusCode = '';
        @exec($command.' 2>&1', $outArray, $statusCode);
        return new UnixOutput($statusCode, $outArray);
    }

    public function execAsync($command, NamedPipeInterface &$output)
    {
        throw new LogicException('Not implemented');
    }
}
