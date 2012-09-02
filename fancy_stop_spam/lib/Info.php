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
        $this->language = $language;
        $this->config   = $config;
        $this->db       = $db;
    }

    public function getVersion()
    {
        $extensionId = 'fancy_stop_spam';
        $query = array(
            'SELECT'    => 'e.version',
            'FROM'      => 'extensions AS e',
            'WHERE'     => 'e.id=\''.$this->db->escape($extensionId).'\''
        );
        $result = $this->db->query_build($query) or error(__FILE__, __LINE__);
        $version = $this->db->result($result);

        return (empty($version) ? '' : $version);
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