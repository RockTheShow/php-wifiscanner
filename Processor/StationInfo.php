<?php

namespace Processor;

use DateTime;

class StationInfo
{
    /**
     * @var string 
     */
    protected $stationMac;
    
    /**
     * @var DateTime 
     */
    protected $lastSeen;
    
    /**
     * @var int 
     */
    protected $highestRssi;
    
    /**
     * @var ApInfo 
     */
    protected $probedAps;
    
    /**
     * @var bool
     */
    protected $sentUntargetedProbe;
    
    public function __construct($stationMac = null, $rssi = null)
    {
        $this->stationMac = $stationMac;
        $this->lastSeen = new DateTime('NOW');
        $this->highestRssi = $rssi;
        $this->probedAps = [];
        $this->sentUntargetedProbe = false;
    }
    
    public function getStationMac()
    {
        return $this->stationMac;
    }

    public function getLastSeen()
    {
        return $this->lastSeen;
    }

    public function getHighestRssi()
    {
        return $this->highestRssi;
    }
    
    public function getProbedAps()
    {
        return $this->probedAps;
    }
    
    public function sentUntargetedProbe()
    {
        return $this->sentUntargetedProbe;
    }

    public function setStationMac($stationMac)
    {
        $this->stationMac = $stationMac;
        return $this;
    }

    public function setLastSeen(DateTime $lastSeen)
    {
        $this->lastSeen = $lastSeen;
        return $this;
    }

    public function setCurrentRssi($rssi)
    {
        $rssi = intval($rssi);
        if ($this->highestRssi === null || $this->highestRssi < $rssi)
            $this->highestRssi = $rssi;
        return $this;
    }

    public function addProbedAp(ApInfo $ap)
    {
        $this->probedAps[$ap->getEssid()] = $ap;
        return $this;
    }
    
    public function setSentUntargetedProbe($setSentUntargetedProbe)
    {
        $this->sentUntargetedProbe = $setSentUntargetedProbe;
        return $this;
    }
}
