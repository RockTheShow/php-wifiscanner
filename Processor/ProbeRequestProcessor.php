<?php

namespace Processor;

use DateTime;
use Event\EventSubscriberInterface;

class ProbeRequestProcessor implements EventSubscriberInterface
{    
    /**
     * @var EventSubscriberInterface 
     */
    protected $renderer;
    
    /**
     * @var StationInfo[] 
     */
    protected $stations;
    
    public function __construct(EventSubscriberInterface $renderer)
    {
        $this->renderer = $renderer;
        $this->stations = [];
    }
    
    // @TODO use unified format (i.e. json) for transferring data from sniffer to processor
    public function notify($data)
    {
        $lines = explode(PHP_EOL, $data);
        foreach ($lines as $line) {
            $tokens = explode(';', $line);
            
            // Station MAC
            if (!isset($tokens[1]) || empty($tokens[1])) {
                return;
            }
            // Index station only if it was yet unknown
            if (!isset($this->stations[$tokens[1]])) {
                $this->stations[$tokens[1]] = new StationInfo($tokens[1]);
            }
            
            $station = $this->stations[$tokens[1]];
            $station->setCurrentRssi($tokens[3]);
            $epochSeconds = current(explode('.', $tokens[0]));
            $station->setLastSeen(DateTime::createFromFormat('U', $epochSeconds));
            
            if (isset($tokens[4]) && !empty($tokens[4]) && preg_match('/^([\x09\x0A\x0D\x20-\x7E])*$/', $tokens[4]))
                $station->addProbedAp(new ApInfo($tokens[4]));
            elseif (isset($tokens[4]) && !empty($tokens[4]))
                $station->addProbedAp(new ApInfo ('<corrupted or non-printable>'));
            else
                $station->setSentUntargetedProbe(true);
            
            $this->renderer->notify($this->stations);
        }
    }
}
