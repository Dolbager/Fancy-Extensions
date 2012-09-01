<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM')) {
    exit;
}

class FancyStopSpamDbLogger
{
    private $language;
    private $config;
    private $db;

    public function __construct($language, $config, $db)
    {
        $this->language = $language;
        $this->config   = $config;
        $this->db       = $db;
    }

    public function log($source, $type, $userId, $ip, $comment = '')
    {
        if ($this->config['o_fancy_stop_spam_use_db_logs'] == '0') {
            return;
        }

        $comment = utf8_substr(forum_trim($comment), 0, 200);
        $query = array(
            'INSERT'    => 'source, type, user_id, ip, time, comment',
            'INTO'      => 'fancy_stop_spam_logs',
            'VALUES'    => sprintf("'%s', '%s', '%s', '%s', '%s', '%s'",
                                $this->db->escape(forum_trim($source)),
                                $this->db->escape($type),
                                $this->db->escape((integer) $userId),
                                $this->db->escape($this->ip2long($ip)),
                                $this->db->escape(time()),
                                $this->db->escape($comment)
                            )
        );

        $this->db->query_build($query) or error(__FILE__, __LINE__);
    }

    private function getEventName($event)
    {
        $event = (integer) $event;
        if (!empty($this->language['log event name ' . $event])) {
            return forum_htmlencode($this->language['log event name ' . $event]);
        }

        return forum_htmlencode($this->language['log event name unknown']);
    }

    private function ip2long($ip)
    {
        return sprintf('%u', ip2long($ip));
    }
}