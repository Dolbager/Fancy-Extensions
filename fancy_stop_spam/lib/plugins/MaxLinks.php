<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM')) {
    exit;
}

class FancyStopSpamPluginMaxLinks extends FancyStopSpamPlugin
{
    const ID      = 'max_links';
    const NAME    = 'Max Links';
    const VERSION = '1.0 (2012.08.27)';

    const USER_MAX_POSTS_FOR_CHECK = 3;

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

    public function renderOptionsBlock(array $forum_page)
    {
        $this->renderOptionsBlockHeader($forum_page, $this->getName());
        ?>
            <div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
                <div class="sf-box sf-short text">
                    <label for="fld<?php echo ++$forum_page['fld_count'] ?>">
                        <span><?php echo $this->language['First Post Max Links'] ?></span>
                        <small><?php echo $this->language['First Post Max Links Help'] ?></small>
                    </label>
                    <span class="fld-input">
                        <input type="text"
                               id="fld<?php echo $forum_page['fld_count'] ?>"
                               name="form[fancy_stop_spam_settings_max_links]"
                               size="3"
                               maxlength="3"
                               value="<?php echo forum_htmlencode($this->config['o_fancy_stop_spam_settings_max_links']) ?>"
                        />
                    </span>
                </div>
            </div>

            <div class="sf-set set<?php echo ++$forum_page['item_count'] ?>">
                <div class="sf-box sf-short text">
                    <label for="fld<?php echo ++$forum_page['fld_count'] ?>">
                        <span><?php echo $this->language['First Post Guest Max Links'] ?></span>
                        <small><?php echo $this->language['First Post Guest Max Links Help'] ?></small>
                    </label>
                    <span class="fld-input">
                        <input type="text"
                               id="fld<?php echo $forum_page['fld_count'] ?>"
                               name="form[fancy_stop_spam_settings_max_links_for_guest]"
                               size="3"
                               maxlength="3"
                               value="<?php echo forum_htmlencode($this->config['o_fancy_stop_spam_settings_max_links_for_guest']) ?>"
                        />
                    </span>
                </div>
            </div>
        <?php
        $this->renderOptionsBlockFooter();
        return $forum_page;
    }

    public function saveOptions(array $form)
    {
        $form = $this->saveBooleanFormOptions($form, 'fancy_stop_spam_plugin_enabled_' . self::ID);

        if (isset($form['fancy_stop_spam_settings_max_links']) && is_numeric($form['fancy_stop_spam_settings_max_links'])) {
            $form['fancy_stop_spam_settings_max_links'] = (integer) $form['fancy_stop_spam_settings_max_links'];
        } else {
            $form['fancy_stop_spam_settings_max_links'] = '1';
        }

        if (isset($form['fancy_stop_spam_settings_max_links_for_guest']) && is_numeric($form['fancy_stop_spam_settings_max_links_for_guest'])) {
            $form['fancy_stop_spam_settings_max_links_for_guest'] = (integer) $form['fancy_stop_spam_settings_max_links_for_guest'];
        } else {
            $form['fancy_stop_spam_settings_max_links_for_guest'] = '1';
        }

        return $form;
    }

    public function check(array $user, $message, array $errors)
    {
        $max_links = (integer) $this->config['o_fancy_stop_spam_settings_max_links'];
        if ($user['is_guest']) {
            $max_links = (integer) $this->config['o_fancy_stop_spam_settings_max_links_for_guest'];
        }

        if ($max_links > 0) {
            if ($this->isMayBeSpammer($user)) {
                $numberOfLinks = $this->getNumberOfLinksInMessage($message);
                if ($numberOfLinks > $max_links) {
                    $errors[] = sprintf($this->language['Error too many links'], $max_links);
                }
            }
        }

        return $errors;
    }

    private function getNumberOfLinksInMessage($message)
    {
        $num_links_http = $num_links_www = 0;

        if (function_exists('mb_substr_count')) {
            $num_links_http = mb_substr_count($message, 'http', 'UTF-8');
            $num_links_www = mb_substr_count($message, 'www', 'UTF-8');
        } else {
            $num_links_http = substr_count($message, 'http');
            $num_links_www = substr_count($message, 'www');
        }

        return max($num_links_http, $num_links_www);
    }

    private function isMayBeSpammer(array $user)
    {
        if ($user['is_admmod'] || ($user['num_posts'] > self::USER_MAX_POSTS_FOR_CHECK)) {
            return FALSE;
        }

        return TRUE;
    }
}