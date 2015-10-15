<?php

namespace Network;

use Event\EventSubscriberInterface;
use Process\ProcessFactory;
use Symfony\Component\Process\Process;

class TsharkNetworkSniffer implements NetworkSnifferInterface
{
    protected $shell;
    protected $processor;
    protected $adapter;
    protected $monitorMode;
    
    public function __construct(
        ProcessFactory $factory,
        EventSubscriberInterface $processor,
        $adapter,
        $monitorMode = false
    )
    {
        $this->shell = $factory;
        $this->processor = $processor;
        $this->adapter = $adapter;
        $this->monitorMode = $monitorMode;
    }
    
    public function sniffProbeRequests()
    {
        $commandLine = '/usr/local/bin/tshark'.
                       ($this->monitorMode ? ' -I' : '').
                       ' -l -i '.$this->adapter.
                       ' -Y "wlan.fc.pwrmgt == 1 || wlan.fc.type_subtype == 4"'.
                       ' -T fields -e frame.time_epoch -e wlan.sa -e wlan.da -e radiotap.dbm_antsignal -e wlan_mgt.ssid'.
                       ' -E separator=";"';
        $process = $this->shell->createProcess($commandLine);
        $processor = $this->processor;
        $globalOutout = '';
        $process->start(function($type, $output) use ($processor)
        {
            // Filter out errors and incomplete buffers
            if ($type === Process::OUT && $output[strlen($output) - 1] === "\n")
                $processor->notify($output);
        });
        return $process;
    }
}
