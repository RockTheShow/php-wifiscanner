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
        $commandLine = 'iwconfig '.$this->virtualAdapterName;
        $process = $this->factory->createProcess($commandLine);
        $process->run();
        if ($process->getStatus() !== 0 && $process->getStatus() !== 237) {
            throw new \RuntimeException(
                'Cannot query network adapter `'.$this->realAdapterName.'` status: '.$process->getErrorOutput());
        }
        return $process->getStatus() === 237;
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
