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

    $fancyStopSpamInfo = $fancy_stop_spam->getInfo();

    require FORUM_ROOT.'header.php';
    ob_start();
?>
    <div class="main-subhead">
        <h2 class="hn"><span><?php echo $lang_fancy_stop_spam['Admin submenu info header'] ?></span></h2>
    </div>
    <div class="main-content main-frm">
        <div class="ct-group">
            <div class="ct-set group-item1">
                <div class="ct-box">
                    <h3 class="ct-legend hn">Fancy Stop SPAM</h3>
                    <ul class="data-list">
                        <li><span><?php printf("Version: %s", $fancyStopSpamInfo->getVersion()) ?></span></li>
                        <li><span><?php printf("Author: %s", $fancyStopSpamInfo->getAuthor()) ?></span></li>
                        <li><span><?php printf("Support: %s",
                            '<a href="' . forum_htmlencode($fancyStopSpamInfo->getSupportUrl()) . '">' . forum_htmlencode($fancyStopSpamInfo->getSupportUrl()) . '</a>' )
                            ?></span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
<?php
    $tpl_temp = forum_trim(ob_get_contents());
    $tpl_main = str_replace('<!-- forum_main -->', $tpl_temp, $tpl_main);
    ob_end_clean();
    require FORUM_ROOT.'footer.php';