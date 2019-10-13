<?php
/**
 * @version 1.0.13
 *
 * @package K7Events/inc/api/callbacks
 */

defined('ABSPATH') || exit;


class Event_LocationCallbacks extends Event_BaseController

{
    public function ev_locationSettings()
    {
        return require_once("$this->plugin_path/templates/admin/location.php");
    }
}