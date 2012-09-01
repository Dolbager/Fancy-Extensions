<?php

if (!defined('FORUM_ROOT'))
    define('FORUM_ROOT', '../../../');

require FORUM_ROOT.'include/common.php';
require FORUM_ROOT.'include/common_admin.php';
require FORUM_ROOT.'lang/'.$forum_user['language'].'/admin_common.php';
require FORUM_ROOT.'lang/'.$forum_user['language'].'/admin_settings.php';

if ($forum_user['g_id'] != FORUM_ADMIN)
    message($lang_common['No permission']);

define("FANCY_STOP_SPAM_ADMIN_PAGES_ROOT", FORUM_ROOT.'extensions/fancy_stop_spam/admin/pages/');
define('FORUM_PAGE_SECTION', 'fancy_stop_spam');

$section = isset($_GET['section']) ? $_GET['section'] : 'info';

if ($section == 'info') {
    define('FORUM_PAGE', 'admin-fancy_stop_spam_' . $section);
    require FANCY_STOP_SPAM_ADMIN_PAGES_ROOT . 'info.php';
} else if ($section == 'logs') {
    define('FORUM_PAGE', 'admin-fancy_stop_spam_' . $section);
    require FANCY_STOP_SPAM_ADMIN_PAGES_ROOT . 'logs.php';
} else if ($section == 'new_users') {
    define('FORUM_PAGE', 'admin-fancy_stop_spam_' . $section);
    require FANCY_STOP_SPAM_ADMIN_PAGES_ROOT . 'new_users.php';
} else if ($section == 'suspicious_users') {
    define('FORUM_PAGE', 'admin-fancy_stop_spam_' . $section);
    require FANCY_STOP_SPAM_ADMIN_PAGES_ROOT . 'suspicious_users.php';
} else if ($section == 'settings') {
    define('FORUM_PAGE', 'admin-fancy_stop_spam_' . $section);
    require FANCY_STOP_SPAM_ADMIN_PAGES_ROOT . 'settings.php';
} else {
    message($lang_common['Bad request']);
}