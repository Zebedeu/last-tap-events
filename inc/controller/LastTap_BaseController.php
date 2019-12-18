<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/controller
 */

defined('ABSPATH') || exit;


class LastTap_BaseController
{
    public $plugin_path;

    public $plugin_url;

    public $plugin;

    public $managers = array();

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/last-tap-plugin.php';
        add_action('init', array( $this, 'lt_load_textdomain'));


        $this->managers = array(
            'participant_manager' => __('Activate Custom Participe', 'last-tap-event'),
            'testimonial_manager' => __('Activate Testimonial Manager', 'last-tap-event'),
            'cpt_manager' => __('Activate CPT Manager', 'last-tap-event'),
            'taxonomy_manager' => __('Activate Taxonomy Manager', 'last-tap-event'),
            'location_manager' => __('Activate Localion Manager', 'last-tap-event'),
            'media_widget' => __('Activate Media Widget', 'last-tap-event'),
            'location_widget' => __('Activate Location Widget', 'last-tap-event'),
            'notify_manager' => __('Activate Notification', 'last-tap-event'),
            'templates_manager' => __('Activate Custom Templates', 'last-tap-event'),
            'membership_manager' => __('Activate Membership Manager', 'k7-church'),

        );
    }

    public function lt_activated(string $key)
    {
        $option = get_option('event_plugin');

        return isset($option[$key]) ? $option[$key] : false;
    }

    
// Load plugin textdomain.

    public function lt_load_textdomain()
    {
        unload_textdomain('last-tap-event');
        load_plugin_textdomain('last-tap-event', false, plugin_basename(dirname(__FILE__, 3)) . '/languages');
    }

}