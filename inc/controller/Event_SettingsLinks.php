<?php
/**
 * @version 1.0.13
 *
 * @package K7Events/inc/controller
 */

defined('ABSPATH') || exit;


class Event_SettingsLinks extends Event_BaseController
{
    public function ev_register()
    {
        add_filter("plugin_action_links_$this->plugin", array($this, 'ev_settings_link'));
    }

    public function ev_settings_link($links)
    {
        $settings_link = '<a href="admin.php?page=event_plugin">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }
}


