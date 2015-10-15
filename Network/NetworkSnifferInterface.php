<?php

namespace Network;

use Symfony\Component\Process\Process;

interface NetworkSnifferInterface
{
    /**
     * @return Process
     */
    public function sniffProbeRequests();
}
