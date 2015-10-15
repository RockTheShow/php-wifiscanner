<?php

namespace Event;

interface EventSubscriberInterface
{
    public function notify($data);
}
