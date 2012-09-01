<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM'))
    exit;

class FancyStopSpamDbLogger {
    const NUMBER_LOGS_FOR_SAVE             = 5000;

    private $language;
    private $config;
    private $db;

    public function __construct($language, $forum_config, $forum_db) {
        $this->language = $language;
        $this->config   = $forum_config;
        $this->db       = $forum_db;
    }

    // Log spam event to database
    public function log($activity_type, $user_id, $user_ip, $comment='') {
        global $forum_db, $forum_config;

        // LOGS enabled?
        if ($forum_config['o_fancy_stop_spam_use_logs'] == '0') {
            return TRUE;
        }

        $comment = utf8_substr($comment, 0, 200);

        // CLEAR OLD ENTRIES
        $this->clear_old_logs();

        $now = time();
        $query = array(
            'INSERT'    => 'user_id, ip, activity_type, activity_time, comment',
            'INTO'      => 'fancy_stop_spam_logs',
            'VALUES'    =>    '\'' . intval($user_id, 10)
                            . '\', \'' . $this->ip2long($user_ip)
                            . '\', \'' . intval($activity_type, 10)
                            . '\', \'' . $now
                            . '\', \'' . $forum_db->escape($comment)
                            . '\''
        );
        $forum_db->query_build($query) or error(__FILE__, __LINE__);
    }

    private function get_log_event_name($event) {
        $event = intval($event, 10);
        if (!empty($this->language['log event name ' . $event])) {
            return forum_htmlencode($this->language['log event name ' . $event]);
        }

        return forum_htmlencode($this->language['log event name unknown']);
    }

    private function clear_old_logs() {
        global $forum_db;

        if ($this->get_num_logs() > (self::NUMBER_LOGS_FOR_SAVE + 100)) {
            $max_old_id = $this->get_last_old_id_logs();

            // DEL OLDEST
            if ($max_old_id > 0) {
                $query = array(
                    'DELETE'    => 'fancy_stop_spam_logs',
                    'WHERE'     => 'id < '.$max_old_id
                );
                $forum_db->query_build($query) or error(__FILE__, __LINE__);
            }
        }
    }


    // Return number entries in logs table
    private function get_num_logs() {
        global $forum_db;

        $query = array(
            'SELECT'    => 'COUNT(*) AS num',
            'FROM'      => 'fancy_stop_spam_logs',
        );
        $result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

        return intval($forum_db->result($result), 10);
    }


    // Return oldest log entries id
    private function get_last_old_id_logs() {
        global $forum_db;

        $query = array(
            'SELECT'    => 'id',
            'FROM'      => 'fancy_stop_spam_logs',
            'ORDER BY'  => 'id DESC',
            'LIMIT'     => self::NUMBER_LOGS_FOR_SAVE.', 1'
        );
        $result = $forum_db->query_build($query) or error(__FILE__, __LINE__);

        return intval($forum_db->result($result), 10);
    }


    // Convert IP-address to long integer
    private function ip2long($ip) {
        return sprintf('%u', ip2long($ip));
    }
}