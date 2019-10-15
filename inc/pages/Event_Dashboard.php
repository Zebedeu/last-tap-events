<?php
/**
 * @version 1.0.13
 *
 * @package K7Events/inc/controller
 */

defined('ABSPATH') || exit;


class Event_Dashboard extends Event_BaseController
{
    public $settings;

    public $callbacks;

    public $callbacks_mngr;

    public $pages = array();

    public function ev_register()
    {
        $this->settings = new Event_SettingsApi();

        $this->callbacks = new Event_AdminCallbacks();

        $this->callbacks_mngr = new Event_ManagerCallbacks();


        $this->ev_setPages();

        $this->ev_setSettings();
        $this->ev_setSections();
        $this->ev_setFields();

        $this->settings->ev_addPages($this->pages)->ev_withSubPage('Dashboard')->ev_register();

    }

    public function ev_setPages()
    {
        $this->pages = array(
            array(
                'page_title' => 'K7 Events',
                'menu_title' => 'Events',
                'capability' => 'manage_options',
                'menu_slug' => 'event_plugin',
                'callback' => array($this->callbacks, 'ev_adminDashboard'),
                'icon_url' => 'dashicons-store',
                'position' => 110
            )
        );
    }

    public function ev_setSettings()
    {
        $args = array(
            array(
                'option_group' => 'event_plugin_settings',
                'option_name' => 'event_plugin',
                'callback' => array($this->callbacks_mngr, 'ev_checkboxSanitize')
           )
        );

        $this->settings->ev_setSettings($args);
    }

    public function ev_setSections()
    {
        $args = array(
            array(
                'id' => 'event_admin_index',
                'title' => 'Settings Manager',
                'callback' => array($this->callbacks_mngr, 'ev_adminSectionManager'),
                'page' => 'event_plugin'
            )
        );

        $this->settings->ev_setSections($args);
    }

    public function ev_setFields()
    {
        $args = array();

        foreach ($this->managers as $key => $value) {
            $args[] = array(
                'id' => $key,
                'title' => $value,
                'callback' => array($this->callbacks_mngr, 'ev_checkboxField'),
                'page' => 'event_plugin',
                'section' => 'event_admin_index',
                'args' => array(
                    'option_name' => 'event_plugin',
                    'label_for' => $key,
                    'class' => 'ui-toggle'
                )
            );
        }

        $this->settings->ev_setFields($args);
    }

}