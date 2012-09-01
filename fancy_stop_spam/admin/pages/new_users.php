<?php
    // Make sure no one attempts to run this script "directly"
    if (!defined('FORUM'))
        exit;

    $forum_page['group_count'] = $forum_page['item_count'] = $forum_page['fld_count'] = 0;
    $forum_page['crumbs'] = array(
        array($forum_config['o_board_title'], forum_link($forum_url['index'])),
        array($lang_admin_common['Forum administration'], forum_link($forum_url['admin_index'])),
        array($lang_fancy_stop_spam['Admin section antispam'], forum_link($forum_url['fancy_stop_spam_admin_section'])),
        $lang_fancy_stop_spam['Admin submenu new users']
    );

    define('FORUM_PAGE_SECTION', 'fancy_stop_spam');
    define('FORUM_PAGE', 'admin-fancy_stop_spam_new_users');
    require FORUM_ROOT.'header.php';
    ob_start();
?>
    <div class="main-subhead">
        <h2 class="hn"><span><?php echo $lang_fancy_stop_spam['Admin submenu new users header'] ?></span></h2>
    </div>
    <div class="main-content main-frm">
        <?php
            $fancy_stop_spam = Fancy_stop_spam::singleton();
            $fancy_stop_spam->print_new_users();
        ?>
    </div>
<?php
    $tpl_temp = forum_trim(ob_get_contents());
    $tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
    ob_end_clean();
    require FORUM_ROOT.'footer.php';