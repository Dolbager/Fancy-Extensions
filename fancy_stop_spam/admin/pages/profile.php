<?php
    // Make sure no one attempts to run this script "directly"
    if (!defined('FORUM'))
        exit;

    if ($forum_user['g_id'] != FORUM_ADMIN) {
        message($lang_common['Bad request']);
    }

    $forum_page['group_count'] = $forum_page['item_count'] = 0;
    $forum_page['crumbs'] = array(
        array($forum_config['o_board_title'], forum_link($forum_url['index'])),
        array(sprintf($lang_profile['Users profile'], $user['username']), forum_link($forum_url['user'], $id)),
        $lang_fancy_stop_spam['Section antispam'],
    );

    define('FORUM_PAGE', 'profile-fancy_stop_spam_profile_section');
    require FORUM_ROOT.'header.php';
    ob_start();
?>
    <div class="main-subhead">
        <h2 class="hn">
            <span><?php printf(($forum_user['id'] == $id) ?
                $lang_fancy_stop_spam['Section antispam welcome'] :
                $lang_fancy_stop_spam['Section antispam welcome user'], forum_htmlencode($user['username'])) ?>
            </span>
        </h2>
    </div>
    <div class="main-content main-frm">
        <div class="ct-group">
            <?php
                $data['user'] = $user;
                $fancy_stop_spam->triggerEvent('UserProfile', $data);
            ?>
        </div>
    </div>
<?php
    $tpl_temp = forum_trim(ob_get_contents());
    $tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
    ob_end_clean();
    require FORUM_ROOT.'footer.php';