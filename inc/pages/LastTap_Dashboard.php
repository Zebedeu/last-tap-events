<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/controller
 */

defined('ABSPATH') || exit;


class LastTap_Dashboard extends LastTap_BaseController
{
    public $settings;

    public $callbacks;

    public $callbacks_mngr;

    public $pages = array();

    public function lt_register()
    {
        $this->settings = new LastTap_SettingsApi();

        $this->callbacks = new LastTap_AdminCallbacks();

        $this->callbacks_mngr = new LastTap_ManagerCallbacks();


        $this->lt_setPages();

        $this->lt_setSettings();
        $this->lt_setSections();
        $this->lt_setFields();

        $this->settings->lt_addPages($this->pages)->lt_withSubPage('Dashboard')->lt_register();

    }

    public function lt_setPages()
    {
        $this->pages = array(
            array(
                'page_title' => 'Last Tap Events',
                'menu_title' => 'Last Tap Events',
                'capability' => 'manage_options',
                'menu_slug' => 'event_plugin',
                'callback' => array($this->callbacks, 'lt_adminDashboard'),
                'icon_url' => 'dashicons-store',
                'position' => 110
            )
        );
    }

    public function lt_setSettings()
    {
        $args = array(
            array(
                'option_group' => 'event_plugin_settings',
                'option_name' => 'event_plugin',
                'callback' => array($this->callbacks_mngr, 'lt_checkboxSanitize')
           )
        );

        $this->settings->lt_setSettings($args);
    }

    public function lt_setSections()
    {
        $args = array(
            array(
                'id' => 'event_admin_index',
                'title' => 'Settings Manager',
                'callback' => array($this->callbacks_mngr, 'lt_adminSectionManager'),
                'page' => 'event_plugin'
            )
        );

        $this->settings->lt_setSections($args);
    }

    public function lt_setFields()
    {
        $args = array();

        foreach ($this->managers as $key => $value) {
            $args[] = array(
                'id' => $key,
                'title' => $value,
                'callback' => array($this->callbacks_mngr, 'lt_checkboxField'),
                'page' => 'event_plugin',
                'section' => 'event_admin_index',
                'args' => array(
                    'option_name' => 'event_plugin',
                    'label_for' => $key,
                    'class' => 'ui-toggle'
                )
            );
        }

        $this->settings->lt_setFields($args);
    }

}