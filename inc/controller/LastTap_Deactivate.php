<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/controller
 */

defined('ABSPATH') || exit;


class LastTap_Deactivate
{
    public static function lt_deactivate()
    {
        flush_rewrite_rules();
    }
}