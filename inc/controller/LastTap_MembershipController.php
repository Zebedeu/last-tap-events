<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/controller
 * @see LastTap_BaseController
 */

defined('ABSPATH') || exit;


class LastTap_MembershipController extends LastTap_BaseController
{
    public $callbacks;

    public $subpages = array();

    public function lt_register()
    {
        // if (!$this->lt_activated('membership_manager')) return;

        $this->settings = new LastTap_SettingsApi();

        $this->callbacks = new LastTap_AdminCallbacks();

        $this->lt_setSubpages();

        $this->settings->lt_addSubPages($this->subpages)->lt_register();
    }

    public function lt_setSubpages()
    {
        $this->subpages = array(
            array(
                'parent_slug' => __('event_plugin', 'last-tap-events'),
                'page_title' => __('Membership Manager', 'last-tap-events'),
                'menu_title' => __('Membership Manager', 'last-tap-events'),
                'capability' => 'manage_options',
                'menu_slug' => 'event_membership',
                'callback' => array($this->callbacks, 'lt_adminMembership')
            )
        );
    }
}