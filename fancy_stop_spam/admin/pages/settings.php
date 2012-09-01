<?php
    // Make sure no one attempts to run this script "directly"
    if (!defined('FORUM'))
        exit;

    if (isset($_POST['form_sent'])) {
        $form = empty($_POST['form'])
              ? array()
              : array_map('trim', $_POST['form']);

        foreach ($fancy_stop_spam->getAvailablePlugins() as $plugin) {
            $form = $plugin->saveOptions($form);
        }

        foreach ($form as $key => $input) {
            if (array_key_exists('o_'.$key, $forum_config) && $forum_config['o_'.$key] != $input) {
                if ($input != '' || is_int($input))
                    $value = '\''.$forum_db->escape($input).'\'';
                else
                    $value = 'NULL';

                $query = array(
                    'UPDATE'    => 'config',
                    'SET'       => 'conf_value='.$value,
                    'WHERE'     => 'conf_name=\'o_'.$forum_db->escape($key).'\''
                );

                $forum_db->query_build($query) or error(__FILE__, __LINE__);
            }
        }

        // Regenerate the config cache
        if (!defined('FORUM_CACHE_FUNCTIONS_LOADED')) {
            require FORUM_ROOT.'include/cache.php';
        }

        generate_config_cache();
        $forum_flash->add_info($lang_admin_settings['Settings updated']);
        redirect(forum_link($forum_url['fancy_stop_spam_admin_settings']), $lang_admin_settings['Settings updated']);
    }

    $forum_page['group_count'] = $forum_page['item_count'] = $forum_page['fld_count'] = 0;
    $forum_page['crumbs'] = array(
        array($forum_config['o_board_title'], forum_link($forum_url['index'])),
        array($lang_admin_common['Forum administration'], forum_link($forum_url['admin_index'])),
        array($lang_fancy_stop_spam['Admin section antispam'], forum_link($forum_url['fancy_stop_spam_admin_section'])),
        $lang_fancy_stop_spam['Admin submenu settings']
    );

    define('FORUM_PAGE', 'admin-fancy_stop_spam_settings');
    require FORUM_ROOT.'header.php';
    ob_start();
?>
    <div class="main-subhead">
        <h2 class="hn"><span><?php echo $lang_fancy_stop_spam['Admin submenu settings header'] ?></span></h2>
    </div>
    <div class="main-content main-frm">
        <div class="content-head">
            <h2 class="hn"><span>Main options</span></h2>
        </div>

        <form class="frm-form" method="post" accept-charset="utf-8" action="<?php echo forum_link($forum_url['fancy_stop_spam_admin_settings']) ?>">
            <div class="hidden">
                <input type="hidden" name="csrf_token" value="<?php echo generate_form_token(forum_link($forum_url['fancy_stop_spam_admin_settings'])) ?>" />
                <input type="hidden" name="form_sent" value="1" />
            </div>

            <fieldset class="frm-group group<?php echo ++$forum_page['group_count'] ?>">
                <fieldset class="mf-set set1">
                    <div class="mf-box">
                    <?php
                        foreach ($fancy_stop_spam->getAvailablePlugins() as $plugin) {
                            $forum_page = $plugin->renderMainOptionsBlock($forum_page);
                        }
                    ?>
                    </div>
                </fieldset>
                <?php
                    foreach ($fancy_stop_spam->getAvailablePlugins() as $plugin) {
                        if ($plugin->isEnabled()) {
                            $forum_page = $plugin->renderOptionsBlock($forum_page);
                        }
                    }
                ?>
            </fieldset>

            <div class="frm-buttons">
                <span class="submit primary">
                    <input type="submit" name="save" value="<?php echo $lang_admin_common['Save changes'] ?>" />
                </span>
            </div>
        </form>
    </div>
<?php
    $tpl_temp = forum_trim(ob_get_contents());
    $tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
    ob_end_clean();
    require FORUM_ROOT.'footer.php';