<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM')) {
    exit;
}

class FancyStopSpamPluginFactory
{
    protected $language;
    protected $config;
    protected $db;
    protected $logger;

    public function __construct($language, $config, $db, $logger)
    {
        $this->language = $language;
        $this->config   = $config;
        $this->db       = $db;
        $this->logger   = $logger;
    }

    public function create($className)
    {
        $classFileName = substr($className, strlen('FancyStopSpamPlugin'));
        require dirname(__FILE__) . '/plugins/' . stripslashes($classFileName) . '.php';

        return new $className($this->language, $this->config, $this->db, $this->logger);
    }
}