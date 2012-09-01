<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM')) {
    exit;
}

class FancyStopSpamPluginIdenticalMessages extends FancyStopSpamPlugin
{
    const ID      = 'identical_messages';
    const NAME    = 'Identical Messages';
    const VERSION = '1.0 (2012.08.28)';

    const POST_LIFETIME            = 21600; // 6 hour
    const POST_MIN_LENGTH          = 20;
    const USER_MAX_POSTS_FOR_CHECK = 5;

    public function getName()
    {
        return self::NAME;
    }

    public function getVersion()
    {
        return self::VERSION;
    }

    public function isEnabled()
    {
        return $this->pluginEnabled(self::ID);
    }

    public function renderMainOptionsBlock(array $forum_page)
    {
        return $this->renderMainOptionsBlockHelper($forum_page, self::ID);
    }

    public function saveOptions(array $form)
    {
        $form = $this->saveBooleanFormOptions($form, 'fancy_stop_spam_plugin_enabled_' . self::ID);
        return $form;
    }

    public function check(array $user, $message, array $errors) {
        if ($this->isMayBeSpammer($user) && $this->isMayBeSpamMessage($message)) {
            $this->pruneExpired();

            $query = array(
                'SELECT' => 'COUNT(f.id)',
                'FROM'   => 'fancy_stop_spam_identical_posts AS f',
                'WHERE'  => 'f.poster_id = ' . intval($user['id'], 10) . ' AND post_hash = \'' . $this->makeHash($message) .'\''
            );
            $result = $this->db->query_build($query) or error(__FILE__, __LINE__);

            $numberOfIdenticalMessages = $this->db->result($result, 0);
            if ($numberOfIdenticalMessages > 0) {
                $errors[] = $this->language['Error identical message'];
            }
        }

        return $errors;
    }

    public function addMessage(array $user, array $postInfo, $postId) {
        if (!$this->isMayBeSpammer($user) || !$this->isMayBeSpamMessage($postInfo['message'])) {
            return;
        }

        $query = array(
            'INSERT' => 'poster_id, post_id, post_hash, posted',
            'INTO'   => 'fancy_stop_spam_identical_posts',
            'VALUES' =>  sprintf("'%d', '%d', '%s', '%s'",
                            (integer) $postInfo['poster_id'],
                            (integer) $postId,
                            $this->makeHash($postInfo['message']),
                            $postInfo['posted']
                        ),
        );
        $this->db->query_build($query) or error(__FILE__, __LINE__);
    }

    private function pruneExpired() {
        $query = array(
            'DELETE'    => 'fancy_stop_spam_identical_posts',
            'WHERE'     => 'posted < ' . (time() - self::POST_LIFETIME)
        );
        $this->db->query_build($query) or error(__FILE__, __LINE__);
    }

    private function makeHash($message) {
        return sha1($this->cleanMessage($message));
    }

    private function cleanMessage($message) {
        return forum_trim($message);
    }

    private function isMayBeSpammer(array $user) {
        return !($user['is_admmod'] || ($user['num_posts'] > self::USER_MAX_POSTS_FOR_CHECK));
    }

    private function isMayBeSpamMessage($message) {
        return (utf8_strlen($message) > self::POST_MIN_LENGTH);
    }
}