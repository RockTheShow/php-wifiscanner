<?php

namespace Processor;

use DateTime;

class StationInfo
{
    protected $stationMac;
    protected $lastSeen;
    protected $highestRssi;
    protected $probedAps;
    
    public function __construct($stationMac = null, $rssi = null)
    {
        $this->stationMac = $stationMac;
        $this->rssi = $rssi;
        $this->probedAps = [];
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
        if ($this->highestRssi < $rssi)
            $this->highestRssi = $rssi;
        return $this;
    }

    public function addProbedAp($ap)
    {
        $this->probedAps[$ap] = $ap;
    }
}
