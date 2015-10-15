<?php

namespace Network;

use Process\ProcessFactory;
use RuntimeException;

class AirmonNetworkAdapter implements NetworkAdapterInterface
{
    protected $factory;
    protected $realAdapterName;
    protected $virtualAdapterName;

    public function __construct(ProcessFactory $factory, $realAdapterName, $virtualAdapterName)
    {
        $this->factory = $factory;
        $this->realAdapterName = $realAdapterName;
        $this->virtualAdapterName = $virtualAdapterName;
    }

    public function isMonitorEnabled()
    {
        $commandLine = 'iwconfig '.$this->virtualAdapterName.' 2>&1 | grep -q "No such device"';
        $process = $this->factory->createProcess($commandLine);
        $process->run();
        return $process->isSuccessful() && strlen($process->getOutput()) > 0;
    }

    public function enableMonitor()
    {
        $commandLine = 'airmon-ng start '.$this->realAdapterName;
        $process = $this->factory->createProcess($commandLine);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new RuntimeException(
                'Cannot set network adapter `'.
                $this->realAdapterName.'` to monitor mode: '.
                $process->getErrorOutput()
            );
        }
    }
}
