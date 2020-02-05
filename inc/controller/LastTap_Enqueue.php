<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/controller
 * @see LastTap_BaseController
 */

defined('ABSPATH') || exit;


class LastTap_Enqueue extends LastTap_BaseController
{
    public function lt_register()
    {
        add_action('wp_head', array($this, 'lt_enqueue_public'));
        add_action('admin_enqueue_scripts', array($this, 'lt_enqueue_admin_js'));
        add_action('admin_enqueue_scripts', array($this, 'lt_enqueue'));
        add_action('init', array($this, 'lt_gutemberg_register'));

    }

    public function lt_enqueue()
    {
        // enqueue all our scripts
        wp_enqueue_script('media-upload');
        wp_enqueue_media();
    
        wp_enqueue_style('lastTap_pluginstyle', $this->plugin_url . 'assets/lt-style.css');
        wp_enqueue_style('lastTap_css3', $this->plugin_url . 'assets/css/lt-account.css');
        wp_enqueue_script('lastTap_pluginscript', $this->plugin_url . 'assets/lt-script.js');
        

    }


    public function lt_gutemberg_register(){

        wp_enqueue_script( 'my-bloc', $this->plugin_url . 'build/index.js', array('wp-blocks', 'wp-editor', 'wp-components', 'wp-i18n'), filemtime($this->plugin_path . 'build/index.js')  );
        register_block_type( 'blockevent/custom-cta', array( 

            'editor_script' => 'cta-block.js'
         ) );
    }

    public function lt_enqueue_admin_js() { 
     
    // Make sure to add the wp-color-picker dependecy to js file

    wp_enqueue_style('core_css', $this->plugin_url .'assets/libs/core/main.css' );
    wp_enqueue_style('daygrid_css', $this->plugin_url .'assets/libs/daygrid/main.css' );
    wp_enqueue_style('timegrid_css', $this->plugin_url .'assets/libs/timegrid/main.css' );
    wp_enqueue_style('list', $this->plugin_url .'assets/libs/list/main.css' );

    wp_enqueue_script('core_main_js', $this->plugin_url .'assets/libs/core/main.js', array('jquery'), '', true);
    wp_enqueue_script('interaction_js', $this->plugin_url .'assets/libs/interaction/main.js', array('jquery'), '', true);
    wp_enqueue_script('daygrid_js', $this->plugin_url .'assets/libs/daygrid/main.js', array('jquery'), '', true);
    wp_enqueue_script('locale_js', $this->plugin_url .'assets/libs/core/locale-all.js', array('jquery'), '', true);
    wp_enqueue_script('timegrid_js', $this->plugin_url .'assets/libs/timegrid/main.js', array('jquery'), '', true);
    wp_enqueue_script('list_js', $this->plugin_url .'assets/libs/list/main.js', array('jquery'), '', true);
    wp_enqueue_script('bootstrap', $this->plugin_url .'assets/libs/bootstrap/main.js', array('jquery'), '', true);
    wp_enqueue_script( 'lt_custom_js', $this->plugin_url .  'assets/js/jquery.custom.js', array( 'jquery', 'wp-color-picker' ), '', true  );
    wp_register_script( 'rkb-dynamic-qr-code', plugins_url( $this->plugin_url .'assets/qr_code/qrcode.min.js' , dirname(__FILE__) ), array('jquery'), '1.0', true);

}
    public function lt_enqueue_public()
    {
        wp_enqueue_style('boostrap-lastTap', $this->plugin_url . 'assets/css/bootstrap.css');
        wp_enqueue_style('awesomeicons', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
        wp_enqueue_style('membership', $this->plugin_url . 'assets/css/membership.css');
        wp_enqueue_style('event_css3', $this->plugin_url . 'assets/css/lt-account.css');
        wp_enqueue_style('mypluginstyle', $this->plugin_url . 'assets/lt-style.css');
        wp_enqueue_style('part_css', $this->plugin_url . 'assets/css/parti.css', array(), false, 'all');
        wp_enqueue_style('form_css', $this->plugin_url . 'assets/form.css', array(), false, 'all' );
        wp_enqueue_style('slider_css', $this->plugin_url . 'assets/slider.css', array(), false, 'all' );
        // wp_enqueue_script('form', $this->plugin_url. 'assets/form.js', array('jquery'), '1.0.0', false);
        wp_enqueue_script('form_parti', $this->plugin_url. 'src/js/parti.js', array('jquery'), '1.0.0', false);
        wp_enqueue_script('bootstrap_js', $this->plugin_url. 'assets/js/bootstrap.js', array('jquery'), '1.0.0', false);
        wp_enqueue_script('slide', $this->plugin_url. 'assets/slider.js', array('jquery'), '1.0.0', false);
        wp_enqueue_script('custom_jsww', $this->plugin_url . 'assets/js/lt-account.js',array( 'jquery'));
        wp_register_script( 'rkb-dynamic-qr-code', plugins_url( $this->plugin_url .'assets/qr_code/qrcode.min.js' , dirname(__FILE__) ), array('jquery'), '1.0', true);

        // wp_enqueue_script('qr_code_js_min', $this->plugin_url . 'assets/qr_code/qrcode.min.js', array(), '', true);
        // wp_enqueue_script('qr_code_js', $this->plugin_url . 'assets/qr_code/qrcode.js',array(), '', true);


    }
}