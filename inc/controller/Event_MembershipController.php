<?php
/**
 * @version 1.0.13
 *
 * @package K7Events/inc/controller
 */

defined('ABSPATH') || exit;


class Event_MembershipController extends Event_BaseController
{
    public $callbacks;

    public $subpages = array();

    public function ev_register()
    {
        if (!$this->ev_activated('membership_manager')) return;

        $this->settings = new Event_SettingsApi();

        $this->callbacks = new Event_AdminCallbacks();

        $this->ev_setSubpages();

        $this->settings->ev_addSubPages($this->subpages)->ev_register();
    }

    public function ev_setSubpages()
    {
        $this->subpages = array(
            array(
                'parent_slug' => __('event_plugin', 'k7-event'),
                'page_title' => __('Membership Manager', 'k7-event'),
                'menu_title' => __('Membership Manager', 'k7-event'),
                'capability' => 'manage_options',
                'menu_slug' => 'event_membership',
                'callback' => array($this->callbacks, 'ev_adminMembership')
            )
        );
    }
}