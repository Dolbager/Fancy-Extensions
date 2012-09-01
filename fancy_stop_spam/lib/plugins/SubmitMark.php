<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM')) {
    exit;
}

class FancyStopSpamPluginSubmitMark extends FancyStopSpamPlugin
{
    const ID      = 'submit_mark';
    const NAME    = 'Submit Mark';
    const VERSION = '1.0 (2012.08.28)';
    const MARKER  = ' ';

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

    public function injectMark($submitValue) {
        return $submitValue . self::MARKER;
    }

    private function isFakeSubmit($submitValue)
    {
        return (utf8_substr($submitValue, -1) != self::MARKER);
    }

    public function eventPostFormSubmited(array $data)
    {
        if (!isset($_POST['preview'])) {
            if ($this->isFakeSubmit($_POST['submit_button'])) {
                message($this->language['Post bot message']);
            }
        }
    }

    public function eventRegisterFormSubmited(array $data)
    {
        if ($this->isFakeSubmit($_POST['register'])) {
           message($this->language['Post bot message']);
        }
    }
}