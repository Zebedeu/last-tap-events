<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/controller
 * @see LastTap_BaseController
 */

defined('ABSPATH') || exit;


class LastTap_SettingsLinks extends LastTap_BaseController
{
    public function lt_register()
    {
        add_filter("plugin_action_links_$this->plugin", array($this, 'lt_settings_link'));
    }

    public function lt_settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=event_plugin">'.__('Settings','last-tap-events').'</a>';
        array_push($links, $settings_link);
        return $links;
    }
}


