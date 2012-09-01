<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM')) {
    exit;
}

require dirname(__FILE__) . '/lib/StopForumSpam.php';

class FancyStopSpamPluginStopForumSpam extends FancyStopSpamPlugin
{
    const ID      = 'stop_forum_spam';
    const NAME    = 'Stop Forum Spam';
    const VERSION = '1.0 (2012.08.30)';

    const LIFETIME_IP_1_FREQ_ACTIVITY = 432000;     // 5 days
    const LIFETIME_IP_ACTIVITY        = 15552000;   // 180 days

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

    public function check(array $errors, $email, $ip)
    {
        $stopForumSpam = new FancyStopSpamStopForumSpam;
        $response = $stopForumSpam->request(array(
            'email' => $email,
            'ip'    => $ip
        ));

        if ($stopForumSpam->isSuccessfullResponse($response)) {
            if ($this->isSpamIp($response)) {

            }

            if ($this->isSpamEmail($response)) {

            }
        } else {

        }

        return $errors;
    }

    public function eventUserProfile(array $data)
    {
        $user = $data['user'];
        $userStatus = $this->getUserStatus($user['email'], $user['registration_ip']);

        if (!empty($userStatus['email'])) {
        ?>
            <div class="ct-set data-set set<?php echo ++$forum_page['item_count'] ?>">
                <div class="ct-box data-box">
                    <h4 class="ct-legend hn"><span><?php echo $this->language['SFS email status'] ?></span></h4>
                    <ul class="data-box"><?php echo implode('', $userStatus['email']) ?></ul>
                </div>
            </div>
        <?php
        }

        if (!empty($userStatus['ip'])) {
        ?>
            <div class="ct-set data-set">
                <div class="ct-box data-box set<?php echo ++$forum_page['item_count'] ?>">
                    <h4 class="ct-legend hn"><span><?php echo $this->language['SFS IP status'] ?></span></h4>
                    <ul class="data-box"><?php echo implode('', $userStatus['ip']) ?></ul>
                </div>
            </div>
        <?php
        }
    }

    private function getUserStatus($email, $ip)
    {
        $status = array();

        $stopForumSpam = new FancyStopSpamStopForumSpam;
        $response = $stopForumSpam->request(array(
            'email' => $email,
            'ip'    => $ip
        ));

        if ($stopForumSpam->isSuccessfullResponse($response)) {
            $status['email'] = $this->parseEmailStatus($response, $email);
            $status['ip']    = $this->parseIpStatus($response, $ip);
        }

        return $status;
    }

    private function parseEmailStatus(array $response, $email)
    {
        $info = array();
        $info[] = '<li><a href="mailto:'.forum_htmlencode($email).'">'.forum_htmlencode($email).'</a></li>';

        if (isset($response['email']) && is_array($response['email'])) {
            if (!empty($response['email']['appears'])) {
                $info[] = '<li>'.$this->language['Status'].': '.$this->language['Status found'].'</li>';

                if (!empty($response['email']['lastseen'])) {
                    $info[] = '<li>'.$this->language['Last seen'].': '.forum_htmlencode(format_time($response['email']['lastseen'])).'</li>';
                }

                if (!empty($response['email']['confidence'])) {
                    $info[] = '<li>'.$this->language['Confidence'].': '.floatval($response['email']['confidence']).'%</li>';
                }

                if (!empty($response['email']['frequency'])) {
                    $info[] = '<li>'.$this->language['Frequency'].': '.intval($response['email']['frequency'], 10).'</li>';
                }
            } else {
                $info[] = '<li>'.$this->language['Status'].': '.$this->language['Status not found'].'</li>';
            }
        } else {
            $info[] = '<li>'.$this->language['Status error'].'</li>';
        }

        return $info;
    }

    private function parseIpStatus(array $response, $ip)
    {
        $info = array();
        $info[] = '<li><a href="'.forum_link($forum_url['get_host'], forum_htmlencode($ip)).'">'.forum_htmlencode($ip).'</a><li>';

        if (isset($response['ip']) && is_array($response['ip'])) {
            if (!empty($response['ip']['appears'])) {
                $info[] = '<li>'.$this->language['Status'].': '.$this->language['Status found'].'</li>';

                if (!empty($response['ip']['lastseen'])) {
                    $info[] = '<li>'.$this->language['Last seen'].': '.forum_htmlencode(format_time($response['ip']['lastseen'])).'</li>';
                }

                if (!empty($response['ip']['confidence'])) {
                    $info[] = '<li>'.$this->language['Confidence'].': '.floatval($response['ip']['confidence']).'%</li>';
                }

                if (!empty($response['ip']['frequency'])) {
                    $info[] = '<li>'.$this->language['Frequency'].': '.intval($response['ip']['frequency'], 10).'</li>';
                }
            } else {
                $info[] = '<li>'.$this->language['Status'].': '.$this->language['Status not found'].'</li>';
            }
        } else {
            $info[] = '<li>'.$this->language['Status error'].'</li>';
        }

        return $info;
    }

    private function isSpamIp($response)
    {
        $isSpam = FALSE;
        if (!isset($response['ip']) || !is_array($response['ip']) || empty($response['ip']['appears'])) {
            return FALSE;
        }

        $frequency = isset($response['ip']['frequency']) ? (integer) $response['ip']['frequency'] : 0;
        $lastSeen  = !empty($response['ip']['lastseen']) ? (integer) $response['ip']['lastseen']  : 0;

        if ($frequency == 1) {
            $isSpam = $lastSeen > (time() - self::LIFETIME_IP_1_FREQ_ACTIVITY);
        } else if ($frequency > 1) {
            $isSpam = $lastSeen > (time() - self::LIFETIME_IP_ACTIVITY);
        }

        return $isSpam;
    }

    private function isSpamEmail($response)
    {
        if (isset($response['email']) && is_array($response['email']) && !empty($response['email']['appears'])) {
            return TRUE;
        }

        return FALSE;
    }
}