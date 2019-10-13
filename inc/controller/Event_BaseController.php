<?php
/**
 * @version 1.0.13
 *
 * @package K7Events/inc/controller
 */

defined('ABSPATH') || exit;


class Event_BaseController
{
    public $plugin_path;

    public $plugin_url;

    public $plugin;

    public $managers = array();

    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
        $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
        $this->plugin = plugin_basename(dirname(__FILE__, 3)) . '/church-plugin.php';
        add_action('init', array( $this, 'ev_load_textdomain'));


        $this->managers = array(
            'cpt_manager' => __('Activate CPT Manager', 'k7-event'),
            'taxonomy_manager' => __('Activate Taxonomy Manager', 'k7-event'),
            'location_manager' => __('Activate Localion Manager', 'k7-event'),
            'location_widget' => __('Activate Location Widget', 'k7-event'),
            'media_widget' => __('Activate Media Widget', 'k7-event'),
            'testimonial_manager' => __('Activate Testimonial Manager', 'k7-event'),
            'notify_manager' => __('Activate Notification', 'k7-event'),
            'templates_manager' => __('Activate Custom Templates', 'k7-event'),
            'participant_manager' => __('Activate Custom Participe', 'k7-event'),
        );
    }

    public function ev_activated(string $key)
    {
        $option = get_option('event_plugin');

        return isset($option[$key]) ? $option[$key] : false;
    }

    
// Load plugin textdomain.

    public function ev_load_textdomain()
    {
        unload_textdomain('k7-event');
        load_plugin_textdomain('k7-event', false, plugin_basename(dirname(__FILE__, 3)) . '/languages');
    }

}