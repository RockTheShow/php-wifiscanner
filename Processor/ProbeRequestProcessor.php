<?php

namespace Processor;

use DateTime;

class ProbeRequestProcessor implements ProcessorInterface
{
    /**
     * @var StationInfo[] 
     */
    protected $stations;
    
    public function __construct()
    {
        $this->stations = [];
    }
    
    public function notify($data)
    {
        $lines = explode("\n", $data);
        foreach ($lines as $line) {
            $tokens = explode(';', $line);
            
            // Station MAC
            if (!isset($tokens[1]) || empty($tokens[1])) {
                return;
            }
            
            // Index action only if it was yet unknown
            if (!isset($this->stations[$tokens[1]])) {
                $this->stations[$tokens[1]] = new StationInfo($tokens[1]);
            }
            
            // Cache
            $station = $this->stations[$tokens[1]];
            
            $station->setCurrentRssi($tokens[3]);
            $epochSeconds = current(explode('.', $tokens[0]));
            $station->setLastSeen(DateTime::createFromFormat('U', $epochSeconds));
            
            // TODO struct with bssids + essid
            if (!$tokens[4] || empty($tokens[4])) {
                return;
            }
            
            $station->addProbedAp($tokens[4]);
        }
    }
    
    public function getStations()
    {
        return $this->stations;
    }
}
