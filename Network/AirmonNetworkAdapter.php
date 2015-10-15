<?php

class AirmonNetworkAdapter implements NetworkAdapterInterface
{
    protected $shell;
    protected $realAdapterName;
    protected $virtualAdapterName;

    public function __construct(CommandLineInterface $shell, $realAdapterName, $virtualAdapterName)
    {
        $this->realAdapterName = $realAdapterName;
        $this->virtualAdapterName = $virtualAdapterName;
    }

    public function isMonitorEnabled()
    {
        $cmd = $this->shell->exec('iwconfig '.$this->virtualAdapterName.' | grep -q "No such device"');
        return $cmd->succeeded() && count($cmd->getOutput()) > 0;
    }

    public function enableMonitor()
    {
        $cmd = $this->shell->exec('airmon-ng start '.$this->realAdapterName);
        if (!$cmd->succeeded()) {
            throw new \RuntimeException(
                'Cannot set network adapter `'.$this->realAdapterName.'` to monitor mode: '.$cmd
            );
        }
    }
}
