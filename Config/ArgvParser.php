<?php

namespace Config;

use InvalidArgumentException;

class ArgvParser implements ConfigurationParserInterface
{
    protected $argv;
    
    public function __construct($argv)
    {
        $this->argv = $argv;
    }

    public function getNetworkInterfaceName()
    {
        // Filter out options from argv array
        $arguments = array_values(array_filter($this->argv, function($value) {
            return strncmp($value, '--', 2) !== 0;
        }));
        // First and only argument (to date) is iface
        $iface = isset($arguments[1]) ? $arguments[1] : null;
        if (!$iface)
            throw new InvalidArgumentException('wireless interface name not provided');
        
        return $iface;
    }

    public function getShowOnlyTargetedProbes()
    {
        return in_array('--targeted-only', $this->argv);
    }

    public function getRendererClass()
    {
        return in_array('--color', $this->argv) ?
            '\Renderer\LightColorConsoleRenderer':
            '\Renderer\SimpleConsoleRenderer';
    }
}
