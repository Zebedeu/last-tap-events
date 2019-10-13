<?php
/**
 * @version 1.0.13
 *
 * @package K7Events/inc/controller
 */

defined('ABSPATH') || exit;


class Event_Enqueue extends Event_BaseController
{
    public function ev_register()
    {
        add_action('admin_enqueue_scripts', array($this, 'ev_enqueue'));
        add_action('wp_head', array($this, 'ev_enqueue_public'));
    }

    public function ev_enqueue()
    {
        // enqueue all our scripts
        wp_enqueue_script('media-upload');
        wp_enqueue_media();
        wp_enqueue_style('mypluginstyle', $this->plugin_url . 'assets/mystyle.css');
        wp_enqueue_style('my_css3', $this->plugin_url . 'assets/css/my-account.css');
        wp_enqueue_script('mypluginscript', $this->plugin_url . 'assets/myscript.js');
    }

    public function ev_enqueue_public()
    {

        wp_enqueue_style('my_css3', $this->plugin_url . 'assets/css/my-account.css');
        wp_enqueue_script('custom_jsww', $this->plugin_url . 'assets/js/my-account.js');
    }
}