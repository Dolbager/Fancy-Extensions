<?php
    // Make sure no one attempts to run this script "directly"
    if (!defined('FORUM'))
        exit;

    $forum_page['group_count'] = $forum_page['item_count'] = $forum_page['fld_count'] = 0;
    $forum_page['crumbs'] = array(
        array($forum_config['o_board_title'], forum_link($forum_url['index'])),
        array($lang_admin_common['Forum administration'], forum_link($forum_url['admin_index'])),
        array($lang_fancy_stop_spam['Admin section antispam'], forum_link($forum_url['fancy_stop_spam_admin_section'])),
        $lang_fancy_stop_spam['Admin submenu logs']
    );

    define('FORUM_PAGE_SECTION', 'fancy_stop_spam');
    define('FORUM_PAGE', 'admin-fancy_stop_spam_logs');
    require FORUM_ROOT.'header.php';
    ob_start();
?>
    <div class="main-subhead">
        <h2 class="hn"><span><?php echo $lang_fancy_stop_spam['Admin submenu logs header'] ?></span></h2>
    </div>
    <div class="main-content main-frm">
<?php if ($forum_config['o_fancy_stop_spam_use_logs'] == '0') { ?>
        <div class="ct-box info-box">
            <p><?php echo sprintf($lang_fancy_stop_spam['Admin logs disabled message'],
                '<a href="'.forum_link($forum_url['admin_settings_features']).'#fancy_stop_spam_settings">'.
                    $lang_fancy_stop_spam['Admin logs disabled message settings'].'</a>')  ?>
            </p>
        </div>
<?php } ?>
        <?php
            $fancy_stop_spam = Fancy_stop_spam::singleton();
            echo $fancy_stop_spam->print_logs();
        ?>
    </div>
<?php
    $tpl_temp = forum_trim(ob_get_contents());
    $tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
    ob_end_clean();
    require FORUM_ROOT.'footer.php';