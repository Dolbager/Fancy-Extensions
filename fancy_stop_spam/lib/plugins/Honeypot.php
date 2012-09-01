<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM')) {
    exit;
}

class FancyStopSpamPluginHoneypot extends FancyStopSpamPlugin
{
    const ID            = 'honeypot';
    const NAME          = 'Honeypot';
    const VERSION       = '1.0 (2012.08.29)';

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

    public function eventPostFormSubmited(array $data)
    {
        if (!isset($_POST['preview'])) {
            $this->checkHoneypot(NULL);
        }
    }

    public function eventRegisterFormSubmited(array $data)
    {
        $this->checkHoneypot(NULL);
    }

    public function makeKey() {
        return uniqid();
    }

    public function createHiddenField($forum_page, $key)
    {
        ?>
            <div class="sf-set set hidden">
                <div class="sf-box text">
                    <label for="fld<?php echo ++$forum_page['fld_count'] ?>">
                        <span><?php echo $this->language['Honey field'] ?></span>
                        <small><?php echo $this->language['Honey field help'] ?></small>
                    </label>
                    <span class="fld-input">
                        <input type="text"
                               id="fld<?php echo $forum_page['fld_count'] ?>"
                               name="email_confirm_xxx_<?php echo $key ?>"
                               size="35"
                               autocomplete="off"
                        />
                    </span>
                </div>
            </div>
        <?php

        return $forum_page;
    }

    private function checkHoneypot($logEvent) {
        if (!isset($_POST['form_honey_key_id'])) {
            message($this->language['Error honeypot message']);
        } else {
            $honeyPotKey = $this->getHoneypotKey();
            if (!empty($_POST[$honeyPotKey])) {
                message($this->language['Error honeypot message']);
            }
        }
    }

    private function getHoneypotKey() {
        return 'email_confirm_xxx_' . forum_htmlencode(forum_trim($_POST['form_honey_key_id']));
    }
}