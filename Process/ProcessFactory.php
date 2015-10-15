<?php

namespace Process;

use Symfony\Component\Process\Process;

class ProcessFactory
{
    protected $useSudo;
    
    public function __construct($useSudo = false)
    {
        $this->useSudo = $useSudo;
    }
    
    public function createProcess($commandLine)
    {
        return new Process($this->useSudo ? 'sudo '.$commandLine : $commandLine);
    }
}
