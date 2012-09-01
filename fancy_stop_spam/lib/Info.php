<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM')) {
    exit;
}

class FancyStopSpamInfo
{
    private $language;
    private $config;
    private $db;

    public function __construct(array $language, array $config, $db)
    {
        $this->language      = $language;
        $this->config        = $config;
        $this->db            = $db;
    }

    public function getVersion()
    {
        return '1.5.2';
    }

    public function getAuthor()
    {
        return 'dimkalinux';
    }

    public function getSupportUrl()
    {
        return $this->language['Support url'];
    }
}