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
        add_action('wp_head', array($this, 'ev_enqueue_public'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_js'));
        add_action('admin_enqueue_scripts', array($this, 'ev_enqueue'));
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

    public function enqueue_admin_js() { 
     
    // Make sure to add the wp-color-picker dependecy to js file
    wp_enqueue_script( 'ev_custom_js', $this->plugin_url .  'assets/js/jquery.custom.js', array( 'jquery', 'wp-color-picker' ), '', true  );
}
    public function ev_enqueue_public()
    {
        wp_enqueue_style('my_css3', $this->plugin_url . 'assets/css/my-account.css');
        wp_enqueue_script('custom_jsww', $this->plugin_url . 'assets/js/my-account.js');
    }
}