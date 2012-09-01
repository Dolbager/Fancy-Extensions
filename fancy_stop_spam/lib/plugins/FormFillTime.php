<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM')) {
    exit;
}

class FancyStopSpamPluginFormFillTime extends FancyStopSpamPlugin
{
    const ID                 = 'form_fill_time';
    const NAME               = 'Form Fill Time';
    const VERSION            = '1.0 (2012.08.29)';
    const FORM_FILL_MIN_TIME = 2;

    const EVENT_EMPTY_FILL_TIME     = 1;
    const EVENT_TOO_SMALL_FILL_TIME = 2;

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

    public function eventRegisterFormSubmited(array $data)
    {
        if (!isset($_POST['form_fancy_stop_spam_time'])) {
            $this->logger->log(self::ID, self::EVENT_EMPTY_FILL_TIME, FORUM_GUEST, $data['ip']);
            message($this->language['Error empty form fill timeout']);
        } else {
            $fillTime = time() - (integer) $_POST['form_fancy_stop_spam_time'];
            if ($fillTime < self::FORM_FILL_MIN_TIME) {
                $this->logger->log(self::ID, self::EVENT_TOO_SMALL_FILL_TIME, FORUM_GUEST, $data['ip'], sprintf('Timeout: %d', $fillTime));
                $this->addValidationError($this->language['Error small form fill timeout']);
            }
        }
    }
}