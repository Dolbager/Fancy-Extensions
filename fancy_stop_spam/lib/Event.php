<?php

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM'))
    exit;

class FancyStopSpamEvent {
    const SYSTEM_EVENT                 = 0;

    // LOGS EVENTS REGISTER
    const REGISTER_SUBMIT              = 1;
    const REGISTER_TIMEOUT             = 2;
    const REGISTER_TIMEZONE            = 3;
    const REGISTER_HONEYPOT            = 4;
    const REGISTER_HONEYPOT_EMPTY      = 5;
    const REGISTER_HONEYPOT_REPEATED   = 11;
    const REGISTER_EMAIL_SFS           = 6;
    const REGISTER_EMAIL_SFS_CACHED    = 7;
    const REGISTER_EMAIL_SFS_IP_CACHED = 8;
    const REGISTER_IP_SFS              = 9;
    const REGISTER_IP_SFS_CACHED       = 10;

    // LOGS EVENTS POST
    const POST_SUBMIT                  = 20;
    const POST_TIMEOUT                 = 21;
    const POST_HONEYPOT                = 22;
    const POST_HONEYPOT_EMPTY          = 23;
    const POST_MAX_LINKS               = 24;

    // LOGS EVENTS LOGIN
    const LOGIN_HONEYPOT               = 40;
    const LOGIN_HONEYPOT_EMPTY         = 41;

    // LOGS EVENTS SIGNATURE
    const SIGNATURE_HIDDEN             = 60;

    // LOGS IDENTICAL POSTS
    const IDENTICAL_POST               = 30;

    // LOGS EVENTS ACTIVATE
    const ACTIVATE_SUBMIT              = 70;
    const ACTIVATE_HONEYPOT            = 71;
    const ACTIVATE_HONEYPOT_EMPTY      = 72;
}