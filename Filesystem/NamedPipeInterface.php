<?php

namespace Filesystem;

interface NamedPipeInterface
{
    function getPath();
    function getHandle();
}
