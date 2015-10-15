<?php

namespace Filesystem;

class PosixNamedPipe implements NamedPipeInterface
{
    protected $path;
    protected $handle;
    
    public function __construct($path)
    {
        $this->path = $path;
        $this->handle = @posix_mkfifo($this->path, 644);
        if ($this->handle === false)
            throw new \RuntimeException('Cannot create FIFO');
    }
    
    public function __destruct()
    {
        if ($this->handle) {
            $ret = @unlink($this->path);
            if ($ret === false)
                throw new \RuntimeException('Cannot unlink FIFO');
        }
    }
    
    public function getHandle()
    {
        return $this->handle;
    }

    public function getPath()
    {
        return $this->path;
    }
}
