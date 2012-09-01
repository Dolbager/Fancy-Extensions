<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM')) {
    exit;
}

class FancyStopSpamLogger
{
    private $storage;

    public function __construct($storage)
    {
        $this->storage  = $storage;
    }

    public function log($source, $type, $userId, $ip, $comment = '')
    {
        $comment = utf8_substr(forum_trim($comment), 0, 200);
        $this->storage->log(forum_trim($source), $type, $userId, $this->ip2long($ip), $comment);
    }

    private function ip2long($ip)
    {
        return sprintf('%u', ip2long($ip));
    }
}