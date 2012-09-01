<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM')) {
	exit;
}

$forum_url['fancy_stop_spam_profile_section']        = 'profile.php?section=fancy_stop_spam_profile_section&amp;id=$1';
$forum_url['fancy_stop_spam_admin_section']          = 'extensions/fancy_stop_spam/admin/index.php';
$forum_url['fancy_stop_spam_admin_info']             = 'extensions/fancy_stop_spam/admin/index.php?section=info';
$forum_url['fancy_stop_spam_admin_settings']         = 'extensions/fancy_stop_spam/admin/index.php?section=settings';
$forum_url['fancy_stop_spam_admin_logs']             = 'extensions/fancy_stop_spam/admin/index.php?section=logs';
$forum_url['fancy_stop_spam_admin_new_users']        = 'extensions/fancy_stop_spam/admin/index.php?section=new_users';
$forum_url['fancy_stop_spam_admin_suspicious_users'] = 'extensions/fancy_stop_spam/admin/index.php?section=suspicious_users';