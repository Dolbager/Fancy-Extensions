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
    const FORM_FILL_MIN_TIME = '2';

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
        global $errors;

        if (!isset($_POST['form_fancy_stop_spam_time'])) {
            message($this->language['Register bot message']);
        } else {
            $fillTime = time() - (integer) $_POST['form_fancy_stop_spam_time'];
            if ($fillTime < self::FORM_FILL_MIN_TIME) {
                $this->addValidationError($this->language['Register bot timeout message']);
            }
        }
    }
}