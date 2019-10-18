<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/controller
 */

defined('ABSPATH') || exit;


class LastTap_Enqueue extends LastTap_BaseController
{
    public function lt_register()
    {
        add_action('wp_head', array($this, 'lt_enqueue_public'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_js'));
        add_action('admin_enqueue_scripts', array($this, 'lt_enqueue'));
    }

    public function lt_enqueue()
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
    wp_enqueue_script( 'lt_custom_js', $this->plugin_url .  'assets/js/jquery.custom.js', array( 'jquery', 'wp-color-picker' ), '', true  );
}
    public function lt_enqueue_public()
    {
        wp_enqueue_style('my_css3', $this->plugin_url . 'assets/css/my-account.css');
        wp_enqueue_style('form_css', $this->plugin_url . 'assets/form.css', array(), false, 'all' );
        wp_enqueue_style('slider_css', $this->plugin_url . 'assets/slider.css', array(), false, 'all' );
        wp_enqueue_script('form', $this->plugin_url. 'assets/form.js', array('jquery'), '1.0.0', false);
        wp_enqueue_script('slide', $this->plugin_url. 'assets/slider.js', array('jquery'), '1.0.0', false);
        wp_enqueue_script('custom_jsww', $this->plugin_url . 'assets/js/my-account.js',array( 'jquery'));


    }
}