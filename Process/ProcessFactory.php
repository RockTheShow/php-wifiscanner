<?php

namespace Process;

use Symfony\Component\Process\Process;

class ProcessFactory
{
    public function createProcess($commandLine)
    {
        return new Process($commandLine);
    }
}
