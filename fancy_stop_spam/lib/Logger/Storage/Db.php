<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM')) {
    exit;
}

class FancyStopSpamLogStorageDb
{
    private $config;
    private $db;

    public function __construct(array $config, DBLayer $db)
    {
        $this->config   = $config;
        $this->db  = $db;
    }

    public function log($source, $type, $userId, $ip, $comment)
    {
        if ($this->config['o_fancy_stop_spam_use_db_logs'] == '0') {
            return;
        }

        $query = array(
            'INSERT'    => 'source, type, user_id, ip, time, comment',
            'INTO'      => 'fancy_stop_spam_logs',
            'VALUES'    => sprintf("'%s', '%s', '%s', '%s', '%s', '%s'",
                                $this->db->escape($source),
                                $this->db->escape($type),
                                $this->db->escape((integer) $userId),
                                $this->db->escape($ip),
                                $this->db->escape(time()),
                                $this->db->escape($comment)
                            )
        );

        $this->db->query_build($query) or error(__FILE__, __LINE__);
    }
}