<?php
/**
 * @version 1.0.13
 *
 * @package K7Events/inc/controller
 */

defined('ABSPATH') || exit;


class Event_Deactivate
{
    public static function ev_deactivate()
    {
        flush_rewrite_rules();
    }
}