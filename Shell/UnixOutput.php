<?php

namespace Shell;

class UnixOutput implements OutputInterface
{
    protected $statusCode;
    protected $outputArray;
    
    public function __construct($statusCode = null, array $output = [])
    {
        $this->statusCode = $statusCode;
        $this->outputArray = $output;
    }
    
    public function setStatusCode($statusCode)
    {
        $this->statusCode = intval($statusCode);
        return $this;
    }
    
    public function setOutputArray(array $output)
    {
        $this->outputArray = $output;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function succeeded()
    {
        return $this->statusCode === 0;
    }

    public function getOutputAsArray()
    {
        return $this->outputArray;
    }
    
    public function getOutput()
    {
        $output = implode($this->outputArray, "\n")."\n";
        return $output;
    }
    
    public function __toString()
    {
        return $this->getOutput();
    }
}
