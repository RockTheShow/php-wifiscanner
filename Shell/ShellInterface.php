<?php

namespace Shell;

use Filesystem\NamedPipeInterface;

interface ShellInterface
{
    /**
     * @param string $command
     * @return OutputInterface
     */
    function exec($command);
    
    /**
     * @param type $command
     * @param NamedPipeInterface $output
     */
    function execAsync($command, NamedPipeInterface &$output);
}
