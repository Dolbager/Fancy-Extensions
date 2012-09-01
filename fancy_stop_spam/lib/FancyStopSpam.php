<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM')) {
    exit;
}

require dirname(__FILE__) . '/Logger/Logger.php';
require dirname(__FILE__) . '/Info.php';
require dirname(__FILE__) . '/Plugin.php';
require dirname(__FILE__) . '/PluginFactory.php';

class FancyStopSpam
{
    private $language;
    private $config;
    private $db;
    private $logger;
    private $plugins;

    public function __construct(array $language, array $config, DBLayer $db, $logStorage)
    {
        $this->language = $language;
        $this->config   = $config;
        $this->db       = $db;
        $this->logger   = new FancyStopSpamLogger($logStorage);

        $this->initPlugins();
    }

    public function getPlugin($pluginName)
    {
        if (isset($this->plugins[$pluginName])) {
            return $this->plugins[$pluginName];
        }

        error('Invalid plugin');
    }

    public function getAvailablePlugins()
    {
        return $this->plugins;
    }

    public function triggerEvent($eventName, array $data = array())
    {
        $method = 'event' . $eventName;
        foreach ($this->plugins as $plugin) {
            if ($plugin->isEnabled() && method_exists($plugin, $method)) {
                $plugin->$method($data);
            }
        }
    }

    public function getInfo()
    {
        return new FancyStopSpamInfo($this->language, $this->config, $this->db);
    }

    private function initPlugins()
    {
        $pluginFactory = new FancyStopSpamPluginFactory(
            $this->language,
            $this->config,
            $this->db,
            $this->logger
        );

        $this->plugins['max_links']          = $pluginFactory->create('FancyStopSpamPluginMaxLinks');
        $this->plugins['identical_messages'] = $pluginFactory->create('FancyStopSpamPluginIdenticalMessages');
        $this->plugins['submit_mark']        = $pluginFactory->create('FancyStopSpamPluginSubmitMark');
        $this->plugins['form_fill_time']     = $pluginFactory->create('FancyStopSpamPluginFormFillTime');
        $this->plugins['honeypot']           = $pluginFactory->create('FancyStopSpamPluginHoneypot');
        $this->plugins['stop_forum_spam']    = $pluginFactory->create('FancyStopSpamPluginStopForumSpam');
    }
}