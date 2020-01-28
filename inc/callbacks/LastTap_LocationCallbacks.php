<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/api/callbacks
 */

defined('ABSPATH') || exit;


class LastTap_LocationCallbacks extends LastTap_BaseController

{
    public function lt_locationSettings()
    {
        return require_once("$this->plugin_path/templates/admin/location.php");
    }
}