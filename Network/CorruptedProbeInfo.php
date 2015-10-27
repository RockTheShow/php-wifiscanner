<?php

namespace Network;

class CorruptedProbeInfo extends ProbeInfo
{
    public function getEssid()
    {
        return '<corrupted or non-printable>';
    }
}
