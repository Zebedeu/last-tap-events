<?php
/**
 * @version 1.0.15
 *
 * @package K7Events/inc/controller
 */

defined('ABSPATH') || exit;



class Event_EventController extends Event_BaseController

{

    public function ev_register()
    {

        $this->settings = new Event_SettingsApi();

        $this->callbacks = new Event_EventCallbacks();

        add_action('init' , array($this , 'ev_eventposts'));
        add_action('pre_get_posts' , array($this , 'ev_event_query'));
        add_action('admin_init' , array($this , 'ev_eventposts_metaboxes'));
        add_action('save_post' , array($this , 'ev_eventposts_save_meta') , 1 , 2);
        //         add_action('manage_event_posts_columns', array($this, 'ev_set_custom_columns'));
        // add_action('manage_event_posts_custom_column', array($this, 'ev_set_custom_columns_data'), 10, 2);
        // add_filter('manage_edit_event_sortable_columns', array($this, 'ev_set_event_custom_columns_sortable'));

        add_filter('the_content' , array($this , 'ev_prepend_event_meta_to_content')); //gets our meta data and dispayed it before the content
        add_shortcode('events' , array($this , 'ev_ev_event_shortcode_output'));



        $this->ev_setSettings();
        $this->ev_setSections();
        $this->ev_setFields();
        $this->ev_setSubpages();

    }

    public function ev_setSubpages()
    {
        $subpage = array(
            array(
                'parent_slug' => 'edit.php?post_type=event' ,
                'page_title' => 'Settings' ,
                'menu_title' => 'Settings' ,
                'capability' => 'manage_options' ,
                'menu_slug' => 'event_settings' ,
                'callback' => array($this->callbacks , 'ev_event_settings')
            ),

        );

        $this->settings->ev_addSubPages($subpage)->ev_register();
    }

    public function ev_setSettings()
    {
        $args = array(
            array(
                'option_group' => 'event_options_group' ,
                'option_name' => 'event_border_color' ,
                'callback' => array($this->callbacks , 'ev_event_sanitize_color')

            ),
            array(
                'option_group' => 'event_options_group' ,
                'option_name' => 'event_status_started'
            ) ,
            array(


                'option_group' => 'event_options_group' ,
                'option_name' => 'event_status_finished'
            ) ,
            array(
                'option_group' => 'event_options_group' ,
                'option_name' => 'event_status_soon'
            ),
            array(
                'option_group' => 'event_options_group' ,
                'option_name' => 'event_status_button'
            ),

            array(
                'option_group' => 'event_options_group' ,
                'option_name' => 'event_currency',
                'callback' => array($this->callbacks , 'ev_validate_currency')

            ),
            array(
                'option_group' => 'event_options_group' ,
                'option_name' => 'event_background_color_button_show_form',
                'callback' => array($this->callbacks , 'ev_event_sanitize_background_color')
            ),

            array('option_group' => 'event_options_group',
                'option_name' => 'event_text_color_button_show_form',
                'callback' => array($this->callbacks , 'ev_event_sanitize_text_color')

        ),


        );
        $this->settings->ev_setSettings($args);
    }

    public function ev_setSections()
    {
        $args = array(
            array(
                'id' => 'event_id' ,
                'title' => __( 'Settings', 'k7-event') ,
                'callback' => array($this->callbacks , 'ev_event_section') ,
                'page' => 'event_settings'

            ),
            array(
                'id' => 'event_color' ,
                'title' => __( 'Color Control', 'k7-event') ,
                'callback' => array($this->callbacks , 'ev_event_section_color') ,
                'page' => 'colors'

            )
        );

        $this->settings->ev_setSections($args);
    }

    public function ev_setFields()
    {
        $args = array(

            array(
                'id' => 'event_currency' ,
                'title' => __('Currency ', 'k7-event') ,
                'callback' => array($this->callbacks , 'ev_currency') ,
                'page' => 'event_settings' ,
                'section' => 'event_id' ,
                'args' => array(
                    'laber_for' => 'event_currency' ,
                    'class' => 'example-class'
                ),

            ),

            array(
                'id' => 'event_border_color' ,
                'title' => __('Border color', 'k7-event') ,
                'callback' => array($this->callbacks , 'ev_event_textFields_border') ,
                'page' => 'colors' ,
                'section' => 'event_color' ,
                'args' => array(
                    'laber_for' => 'event_border_color' ,
                    'class' => 'example-class'
                ),

            ),
            array(
                'id'=> 'event_background_color_button_show_form',
                 'title' => __('Color background button', 'k7-event'),
                 'callback' => array($this->callbacks, 'ev_chanche_background_color_button'),
                 'page' => 'colors',
                 'section'=> 'event_color',
                 'args' => array(
                    'label' => 'event_background_color_button_show_form',
                    'class' => 'exemple-class'
                 ),
            ),

            array(
                'id'=> 'event_text_color_button_show_form',
                 'title' => __('Text Color button', 'k7-event'),
                 'callback' => array($this->callbacks, 'ev_chanche_text_color_button'),
                 'page' => 'colors',
                 'section'=> 'event_color',
                 'args' => array(
                    'label' => 'event_text_color_button_show_form',
                    'class' => 'exemple-class'
                 ),
            ),

        );

        $this->settings->ev_setFields($args);
    }

    public function ev_eventposts()
    {
        /**
         * Enable the event custom post type
         * http://codex.wordpress.org/Function_Reference/register_post_type
         */
        //Labels for post type
        $labels = array(
            'name' => __('Event' , 'k7-event') ,
            'singular_name' => __('Event' , 'k7-event') ,
            'menu_name' => __('Events' , 'k7-event') ,
            'name_admin_bar' => __('Event' , 'k7-event') ,
            'add_new' => __('Add New' , 'k7-event') ,
            'add_new_item' => __('Add New Event' , 'k7-event') ,
            'new_item' => __('New Event' , 'k7-event') ,
            'edit_item' => __('Edit Event' , 'k7-event') ,
            'view_item' => __('View Event' , 'k7-event') ,
            'all_items' => __('All Events' , 'k7-event') ,
            'searev_items' => __('Search Events' , 'k7-event') ,
            'parent_item_colon' => __('Parent Event:' , 'k7-event') ,
            'not_found' => __('No Event found.', 'k7-event') ,
            'not_found_in_trash' => __('No Events found in Trash.' , 'k7-event') ,
        );
        //arguments for post type
        $args = array(
            'labels' => $labels ,
            'public' => true ,
            'publicly_queryable' => true ,
            'show_ui' => true ,
            'show_in_nav' => true ,
            'query_var' => true ,
            'hierarchical' => true ,
            'supports' => array('title' , 'thumbnail' , 'editor') ,
            'has_archive' => true ,
            'menu_position' => 20 ,
            'show_in_admin_bar' => true ,
            'menu_icon' => 'dashicons-calendar-alt' ,
            'rewrite' => array('slug' => 'event' , 'with_front' => 'true')
        );
        //register post type
        register_post_type('event' , $args);
    }

    /**
     * Adds event post metaboxes for start time and end time
     * http://codex.wordpress.org/Function_Reference/add_meta_box
     *
     * We want two time event metaboxes, one for the start time and one for the end time.
     * Two avoid repeating code, we'll just pass the $identifier in a callback.
     * If you wanted to add this to regular posts instead, just swap 'event' for 'post' in add_meta_box.
     */
    public function ev_eventposts_metaboxes()
    {
        add_meta_box('event_date_start' , __( 'Start Date and Time', 'k7-event') , array($this , 'event_date') , 'event' , 'side' , 'default' , array('id' => '_start'));
        add_meta_box('event_date_end' , __('End Date and Time', 'k7-event') , array($this , 'event_date') , 'event' , 'side' , 'default' , array('id' => '_end'));

        add_meta_box(
            'event_location' ,
            __( 'Event Location', 'k7-event'),
            array($this , 'event_location') ,
            'event' , 'normal' ,
            'default' , array('id' => '_end'));
    }

    // Metabox HTML
    public function event_date($post , $args)
    {
        $metabox_id = '_ch'. $args['args']['id'];

        global $post , $wp_locale , $postEvent;

        $postEvent = $post;
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__) , 'ev_eventposts_nonce');
        $time_adj = current_time('timestamp');
        $month = get_post_meta($post->ID , $metabox_id . '_month' , true);
        if (empty($month)) {
            $month = gmdate('m' , $time_adj);
        }
        $day = get_post_meta($post->ID , $metabox_id . '_day' , true);
        if (empty($day)) {
            $day = gmdate('d' , $time_adj);
        }
        $year = get_post_meta($post->ID , $metabox_id . '_year' , true);
        if (empty($year)) {
            $year = gmdate('Y' , $time_adj);
        }

        $hour = get_post_meta($post->ID , $metabox_id . '_hour' , true);

        if (empty($hour)) {
            $hour = gmdate('H' , $time_adj);
        }

        $min = get_post_meta($post->ID , $metabox_id . '_minute' , true);

        if (empty($min)) {
            $min = '00';
        }
        $month_s = '<select name="' . $metabox_id . '_month">';
        for ($i = 1; $i < 13; $i = $i + 1) {
            $month_s .= "\t\t\t" . '<option value="' . zeroise($i , 2) . '"';
            if ($i == $month)
                $month_s .= ' selected="selected"';
            $month_s .= '>' . $wp_locale->get_month_abbrev($wp_locale->get_month($i)) . "</option>\n";
        }
        $month_s .= '</select>';
        echo $month_s;
        echo '<input type="text" name="' . $metabox_id . '_day" value="' . $day . '" size="2" maxlength="2" />';
        echo '<input type="text" name="' . $metabox_id . '_year" value="' . $year . '" size="4" maxlength="4" /> @ ';
        echo '<input type="text" name="' . $metabox_id . '_hour" value="' . $hour . '" size="2" maxlength="2"/>:';
        echo '<input type="text" name="' . $metabox_id . '_minute" value="' . $min . '" size="2" maxlength="2" />';


    }

    public function event_location()
    {
        global $post;
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__) , 'ev_eventposts_nonce');
        // The metabox HTML
        $event_country = get_post_meta($post->ID , '_ev_event_country' , true);
        $event_city = get_post_meta($post->ID , '_ev_event_city' , true);
        $event_address = get_post_meta($post->ID , '_ev_event_address' , true);
        $event_email = get_post_meta($post->ID , '_ev_event_email' , true);
        $event_organizer = get_post_meta($post->ID , '_ev_event_organizer' , true);
        $event_phone = get_post_meta($post->ID , '_ev_event_phone' , true);
        $event_phone_2 = get_post_meta($post->ID , '_ev_event_phone_2' , true);
        $event_street = get_post_meta($post->ID , '_ev_event_street' , true);
        $event_partici = get_post_meta($post->ID , '_ev_event_partic_limit' , true); 
        $event_price = get_post_meta($post->ID , '_ev_event_price' , true); ?>


        <div class="field-container">

            <div class="field">
                <div class="ch-col-12">
                    <div class="ch-col-1">
                        <label for="_ev_event_price"><?php _e('Event price :'); ?></label>
                        <input type="text" name="_ev_event_price"  value="<?php echo $event_price; ?>" />

                    </div>
                    <div class="ch-col-5">
                        <label for="_ev_event_price"><?php _e('Event Currency :'); ?></label>
                            <?php echo $value = esc_attr( get_option( 'event_currency' ) );?>
                    </div>
                    <div class="ch-col-12">
                         <label for="_ev_event_partic_limit"><?php _e('Event Participe Limits:'); ?></label>
                        <input type="text" name="_ev_event_partic_limit" value="<?php echo $event_partici; ?>"/>
            
                    </div>
                </div>
            </div>
            <hr>
            <div class="field">
                <div class="ch-col-12">
                    <div class="ch-col-5">
                        <label for="_ev_event_country"><?php _e('Event Country:'); ?></label>
                        <input type="text" name="_ev_event_country" value="<?php echo $event_country; ?>"/>
                    </div>
                    <div class="ch-col-5">
                        <label for="_ev_event_city"><?php _e('Event City:'); ?></label>
                        <input type="text" name="_ev_event_city" value="<?php echo $event_city; ?>"/>
                    </div>
                    <div class="ch-col-5">
                        <label for="_ev_event_address"><?php _e('Event Address:'); ?></label>
                        <input type="text" name="_ev_event_address" value="<?php echo $event_address; ?>"/>
                    </div>
                    <div class="ch-col-5">
                         <label for="_ev_event_street"><?php _e('Event Street:'); ?></label>
                         <input type="text" name="_ev_event_street" value="<?php echo $event_street; ?>"/>
                    </div>

                </div>
            </div>
            <hr>
            <div class="field">
                <div class="ch-col-12">
                    <div class="ch-col-5">
                        <label for="_ev_event_email"><?php _e('Event Email:'); ?></label>
                        <input type="email" name="_ev_event_email" value="<?php echo $event_email; ?>"/>
                    </div>
                    <div class="ch-col-5">
                        <label for="_ev_event_organizer"><?php _e('Event Organizers email:'); ?></label>
                        <input type="email" name="_ev_event_organizer" value="<?php echo $event_organizer; ?>"/>
                    </div>
                    <div class="ch-col-5">
                        <label for="_ev_event_phone"><?php _e('Event Phone:'); ?></label>
                        <input type="tel" name="_ev_event_phone" value="<?php echo $event_phone; ?>"/>
                    </div>
                </div>
            <div class="field">
                <label for="_ev_event_phone_2"><?php _e('Event Phone 2:'); ?></label>
                <input type="tel" name="_ev_event_phone_2" value="<?php echo $event_phone_2; ?>"/>
            </div>
        </div>
        </div>
        <?php
    }

   // Save the Metabox Data
    public function ev_eventposts_save_meta($post_id , $post)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;
        if (!isset($_POST['ev_eventposts_nonce']))
            return;
        if (!wp_verify_nonce($_POST['ev_eventposts_nonce'] , plugin_basename(__FILE__)))
            return;
        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post' , $post->ID))
            return;
        // OK, we're authenticated: we need to find and save the data
        // We'll put it into an array to make it easier to loop though

        $metabox_ids = array('_ev_start' , '_ev_end');
        foreach ($metabox_ids as $key) {

            $aa = $_POST[$key . '_year'];
            $mm = $_POST[$key . '_month'];
            $jj = $_POST[$key . '_day'];
            $hh = $_POST[$key . '_hour'];
            $mn = $_POST[$key . '_minute'];

            $aa = ($aa <= 0) ? date('Y') : $aa;
            $mm = ($mm <= 0) ? date('n') : $mm;
            $jj = sprintf('%02d' , $jj);
            $jj = ($jj > 31) ? 31 : $jj;
            $jj = ($jj <= 0) ? date('j') : $jj;
            $hh = sprintf('%02d' , $hh);
            $hh = ($hh > 23) ? 23 : $hh;
            $mn = sprintf('%02d' , $mn);
            $mn = ($mn > 59) ? 59 : $mn;

            $events_meta[$key . '_year'] = $aa;
            $events_meta[$key . '_month'] = $mm;
            $events_meta[$key . '_day'] = $jj;
            $events_meta[$key . '_hour'] = $hh;
            $events_meta[$key . '_minute'] = $mn;
            $events_meta[$key . '_eventtimestamp'] = $aa . '-' . $mm . '-' . $jj . ' ' . $hh . ':' . $mn;

        }

        // Save Locations Meta

        $events_meta['_ev_event_country'] = sanitize_text_field($_POST['_ev_event_country']);
        $events_meta['_ev_event_city'] = sanitize_text_field($_POST['_ev_event_city']);
        $events_meta['_ev_event_address'] = sanitize_text_field($_POST['_ev_event_address']);
        $events_meta['_ev_event_email'] = sanitize_email($_POST['_ev_event_email']);
        $events_meta['_ev_event_organizer'] = sanitize_text_field($_POST['_ev_event_organizer']);
        $events_meta['_ev_event_phone'] = $this->callbacks->ev_sanitize_number($_POST['_ev_event_phone']);
        $events_meta['_ev_event_phone_2'] = $this->callbacks->ev_sanitize_number($_POST['_ev_event_phone_2']);
        $events_meta['_ev_event_street'] = sanitize_text_field($_POST['_ev_event_street']);
        $events_meta['_ev_event_partic_limit'] = $this->callbacks->ev_sanitize_number($_POST['_ev_event_partic_limit']);
        $events_meta['_ev_event_price'] = $this->callbacks->ev_sanitize_number($_POST['_ev_event_price']);


        // Add values of $events_meta as custom fields
        foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
            if ($post->post_type == 'revision') return; // Don't store custom data twice
            $value = implode(',' , (array)$value); // If $value is an array, make it a CSV (unlikely)
            if (get_post_meta($post->ID , $key , FALSE)) { // If the custom field already has a value
                update_post_meta($post->ID , $key , $value);
            } else { // If the custom field doesn't have a value
                add_post_meta($post->ID , $key , $value);
            }
            if (!$value) delete_post_meta($post->ID , $key); // Delete if blank
        }
    }
    /**
     * Helpers to display the date on the front end
     */
    // Get the Month Abbreviation

    public function ev_get_the_month_abbr($month)
    {
        global $wp_locale;
        for ($i = 1; $i < 13; $i = $i + 1) {
            if ($i == $month)
                $monthabbr = $wp_locale->get_month_abbrev($wp_locale->get_month($i));
        }
        return $monthabbr;
    }

    // Display the date

    public function ev_get_the_event_date()
    {
        global $post;


        $eventdate = '';
        $month = get_post_meta($post->ID , '_month' , true);
        $eventdate = ev_get_the_month_abbr($month);
        $eventdate .= ' ' . get_post_meta($post->ID , '_day' , true) . ',';
        $eventdate .= ' ' . get_post_meta($post->ID , '_year' , true);
        $eventdate .= ' at ' . get_post_meta($post->ID , '_hour' , true);
        $eventdate .= ':' . get_post_meta($post->ID , '_minute' , true);
        return $eventdate;
    }

    /**
     * Customize Event Query using Post Meta
     *
     * @link http://www.billerickson.net/customize-the-wordpress-query/
     * @param object $query data
     *
     */
    public function ev_event_query($query)
    {

        // http://codex.wordpress.org/Function_Reference/current_time
        $current_time = current_time('mysql');
        list($today_year , $today_month , $today_day , $hour , $minute , $second) = preg_split('([^0-9])' , $current_time);
        $current_timestamp = $today_year . $today_month . $today_day . $hour . $minute;
        global $wp_the_query;

        if ($wp_the_query === $query && !is_admin() && is_post_type_archive('events')) {
            $meta_query = array(
                array(
                    'key' => '_ev_start_eventtimestamp' ,
                    'value' => $current_timestamp ,
                    'compare' => '>'
                )
            );
            $query->set('meta_query' , $meta_query);
            $query->set('orderby' , 'meta_value_num');
            $query->set('meta_key' , '_ev_start_eventtimestamp');
            $query->set('order' , 'ASC');
            $query->set('posts_per_page' , '2');
        }

    }

    //shortcode display
    public function ev_ev_event_shortcode_output($atts , $content = '' , $tag)
    {

        //build default arguments
        $arguments = shortcode_atts(array(
                'event_id' => '' ,
                'number_of_event' => -1)
            , $atts , $tag);

        //uses the main output function of the location class
        return $this->ev_get_event_output($arguments);

    }

    public function ev_prepend_event_meta_to_content($content)
    {
        global $post , $post_type , $wp_locale;

        $event = $post;


        $current_time = current_time('mysql');
        list($today_year , $today_month , $today_day , $hour , $minute , $second) = preg_split('([^0-9])' , $current_time);
        $current_timestamp = $today_year . '-' . $today_month . '-' . $today_day . ' ' . $hour . ':' . $minute;


        if ($post_type == 'event' && is_singular('event')) { ?>
            <body onload='verHora()'><h3 id='relogio'></h3>
            <?php

            ?>
            <div class="ch-col-12">
                <div class="ch-row">
                        <div class="ch-col-7">

            <img src="<?php echo $this->plugin_url . "/assets/icon/compose.svg"; ?>" style="width:20px; height:20px;">
            <strong><?php echo "\t\n" . __('Publish date:' , 'k7-event'); ?></strong><?php echo the_date('M d Y'); ?><br>
            <?php
            // Gets the event start month from the meta field
            $month = get_post_meta($event->ID , '_ev_start_month' , true);
            // Converts the month number to the month name
            $month = $wp_locale->get_month_abbrev($wp_locale->get_month($month));
            // Gets the event start day
            $day = get_post_meta($event->ID , '_ev_start_day' , true);
            // Gets the event start year
            $year = get_post_meta($event->ID , '_ev_start_year' , true);
            $event_partici = get_post_meta($event->ID , '_ev_event_partic_limit' , true);
            ?>

            <?php

            $endEvent = get_post_meta($event->ID , '_ev_end_eventtimestamp' , true);

            ?>
            <img src="<?php echo $this->plugin_url . "/assets/icon/clock.svg"; ?>" style="width:20px; height:20px;">
            <strong><?php echo "\t\n" . __('Event start date:' , 'k7-event'); ?></strong><?php echo "\t\n" . $month . ' ' . $day . ' ' . $year; ?>
            <br>
            <img src="<?php echo $this->plugin_url . "/assets/icon/timestampdate.svg"; ?>"
                 style="width:20px; height:20px;">

            <strong><?php echo "\t\n" . __('Start event timestamp:' , 'k7-event'); ?></strong><?php echo "\t\n" . $this->callbacks->formatDate(get_post_meta($event->ID , '_ev_start_eventtimestamp' , true)); ?>
            <br>
            <img src="<?php echo $this->plugin_url . "/assets/icon/finish.svg"; ?>" style="width:20px; height:20px;">
            <strong><?php echo "\t\n" . __('End event timestamp:' , 'k7-event'); ?></strong><?php echo "\t\n" . $this->callbacks->formatDate($endEvent); ?><br>
                </div>
                    <div class="ch-col-5">
                        <?php $price = get_post_meta($event->ID , '_ev_event_price' , true);?>
                        <strong><?php printf( __('Price: %s %s ', 'k7-event'), $price, get_option( 'event_currency', true ) );?>  </strong>
                    </div>
                    <hr>
                    <div class="ch-col-5">
                        <?php 

                            $all_post_ids = get_posts(array(
                                'fields'          => 'post_id',
                                'posts_per_page'  => -1,
                                'post_type' => 'participant'
                            ));
                            $total = [];
                        foreach ($all_post_ids as $k => $v) {
                            $count = get_post_meta( $v->ID, '_event_participant_key', false );
                                foreach ($count as $key => $value) {
                                        if($value['post_event_id'] == $event->ID && $value['approved'] == 1){
                                            $total[] = $value['post_event_id'];
                                    ?>
                                <?php 
                                } }
                            }

                            $number = 0;
                            if($event_partici != 0){
                                $number = ($event_partici - count($total));
                            }else{
                                $number = 0;
                            }
                            ?>

                            <strong><?php printf( __('Nº of places available:  %s ', 'k7-event'), $number); ?></strong><br><hr>
                                <strong><?php printf( 
                                    __('Nº of participants: %s', 'k7-event'), count($total) 
                                    ); ?></strong>
                    </div>
                </div>
            </div>

            <div class="ch-col-12">
                <div class="ch-row">
                    <div class="ch-col-6">

                        <label class="ch-center"><img
                                    src="<?php echo $this->plugin_url . "/assets/icon/location.svg"; ?>"
                                    style="width:40px; height:40px;">
                            <h2><?php _e(' Location' , 'k7-event'); ?></h2></label>

                        <hr>
                        <strong><?php _e('Event County:' , 'k7-event'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ev_event_country' , true); ?></small>
                        <br>
                        <strong><?php _e('Event City/Province:' , 'k7-event'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ev_event_city' , true); ?></small>
                        <br>

                        <hr>
                        <strong><?php _e('Event Address:' , 'k7-event'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ev_event_address' , true); ?></small>
                        <br>
                        <strong><?php _e('Event Street:' , 'k7-event'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ev_event_street' , true); ?></small>
                        <br>

                    </div>
                    <div class="ch-col-6">

                        <label class="ch-center"><img
                                    src="<?php echo $this->plugin_url . "/assets/icon/contacteditor.svg"; ?>"
                                    style="width:40px; height:40px;">
                            <h2><?php _e(' Contact' , 'k7-event'); ?></h2></label>
                        <hr>
                        <strong><?php _e('Event Phone:' , 'k7-event'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ev_event_phone' , true); ?></small>
                        <br>
                        <strong><?php _e('Event Phone 2:' , 'k7-event'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ev_event_phone_2' , true); ?></small>
                        <br>

                        <hr>
                        <strong><?php _e('Event Email:' , 'k7-event'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ev_event_email' , true); ?></small>
                        <br>
                        <strong><?php _e('Event or organizer email:' , 'k7-event'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID , '_ev_event_organizer' , true); ?></small>
                        <br>
                    </div>

                </div>
            </div>

            
            <?php $event_permalink = get_permalink($event);
            $html = '';
            $html .= '<h2 class="ch-title">';
            $html .= '<a href="' . esc_url($event_permalink) . '" title="' . esc_attr__('view Event' , 'k7-event') . '">';
            $html .= '</a>';
            $html .= '</h2>'; ?>

            <?php

            $html .= '<div class="ch-row"><div class="ch-col-6">';
            $html .= $content;
            $html .= '</div><div class="ch-col-6">';
            $html .= get_the_post_thumbnail($event->ID , 'thumbnail');
            $html .= "</div></div>"; 

            echo  $html;
            ?>
            
            <div class="ch-col-12">
                <div class="ch-row">
                    <hr>
                    <div class="ch-col-12">
                        <?php 
                        if($event_partici == count($total)){?>
                            <header class="header">
                                <button class="ch-tab" style="background: red;" onclick="myFunction()"><?php esc_html_e( 'INSCRIPTIONS ARE CLOSED! We have reached the maximum number of members, and that is why registration is closed.!', 'k7-event' );?></button>
                            </header>
                        <?php }else{
                            $this->get_participe_event_form();
                        } ?>
                    </div>
                </div>
            </div>
            <?php 


        } else {
            return $content;

        }
    }

    public function ev_get_event_output($arguments = "")
    {

        global $post;

        $wp_locale = new WP_Locale();

        add_image_size( 'event-thumb', 270, 175, false );



        //default args
        $default_args = array(
            'event_id' => '' ,
            'number_of_event' => -1
        );

        //update default args if we passed in new args
        if (!empty($arguments) && is_array($arguments)) {
            //go through each supplied argument
            foreach ($arguments as $arg_key => $arg_val) {
                //if this argument exists in our default argument, update its value
                if (array_key_exists($arg_key , $default_args)) {
                    $default_args[$arg_key] = $arg_val;
                }
            }
        }


        //find event
        $event_args = array(
            'post_type' => 'event' ,
            'posts_per_page' => $default_args['number_of_event'] ,
            'post_status' => 'publish' ,
            'meta_key' => '_ev_start_eventtimestamp' ,
            'orderby' => 'meta_value_num' ,

        );
        //if we passed in a single event to display
        if (!empty($default_args['event_id'])) {
            $event_args['include'] = $default_args['event_id'];
        }

        //output
        $html = '';
        $events = get_posts($event_args);

        // http://codex.wordpress.org/Function_Reference/current_time
        $current_time = current_time('mysql');
        list($today_year , $today_month , $today_day , $hour , $minute , $second) = preg_split('([^0-9])' , $current_time);
        $current_timestamp = $today_year . '-' . $today_month . '-' . $today_day . ' ' . $hour . ':' . $minute;

        //if we have event
        if ($events) {
            //foreach event
            foreach ($events as $key => $event) {
                                //title
                //collect event data
                $event_id = $event->ID;
                $event_title = get_the_title($event_id);
                $event_permalink = get_permalink($event_id);

                $html .= '<article class="ch-col-12 " style="border: 12px solid '.get_option( 'event_border_color').'">';
                $html .= '<div class="ch-row">';

                $html .= '<h2 class="ch-title">';
                $html .= '<a href="' . esc_url($event_permalink) . '" title="' . esc_attr__('view Event' , 'k7-event') . '">';
                $html .= $event_title;
                $html .= '</a>';
                $html .= '</h2>';


                $html .= '<section class="ch-col-6 sermon" >';

                $event_thumbnail = get_the_post_thumbnail($event_id , 'event-thumb');
                $html .= '<div class="ch-col-1 image_content">';

                $event_content = apply_filters('the_content' , $event->post_content);
                $html .= '</div>';

                if (!empty($event_content)) {
                    $event_content = strip_shortcodes(wp_trim_words($event_content , 40 , '...'));
                }


                // http://codex.wordpress.org/Function_Reference/current_time
                $current_time = current_time('mysql');
                list($today_year , $today_month , $today_day , $hour , $minute , $second) = preg_split('([^0-9])' , $current_time);
                $current_timestamp = $today_year . '-' . $today_month . '-' . $today_day . ' ' . $hour . ':' . $minute;
                //(lets third parties hook into the HTML output to output data)
                $html = apply_filters('event_before_main_content' , $html);

                //image & content
                if (!empty($event_thumbnail) || !empty($event_content)) {

                    if (!empty($event_thumbnail)) {
                        $html .= '<p class="ch-col-3 image_content">';
                        $html .= $event_thumbnail;

                        $html .= '</p>';
                    }else{
                    $html .= '<p class="ch-col-3 image_content">';
                    $html .= '<img src="' . $this->plugin_url . '/assets/images/no-image-available-icon-6.png">';
                    $html .= '</p>';


                    }
                    if (!empty($event_content)) {
                        $html .= '<img src="' . $this->plugin_url . '/assets/icon/compose.svg" style="width:20px; height:20px;"><strong>' . "\t\n" . __('Publish date:'."\t\n" , 'k7-event') . '</strong>' . get_the_date('M d Y') . '<br>';

                        ?>
                        <body onload='verHora()'><h3 id='relogio'></h3></body><?php


                        // Gets the event start month from the meta field
                        $month = get_post_meta($event_id , '_ev_start_month' , true);
                        // Converts the month number to the month name
                        $month = $wp_locale->get_month_abbrev($wp_locale->get_month($month));
                        // Gets the event start day
                        $day = get_post_meta($event_id , '_ev_start_day' , true);
                        // Gets the event start year
                        $year = get_post_meta($event_id , '_ev_start_year' , true);

                        $endEvent = get_post_meta($event_id , '_ev_end_eventtimestamp' , true);

                        $current_timestamp = $current_timestamp;


                        $html .= '<img src="' . $this->plugin_url . '/assets/icon/clock.svg" style="width:20px; height:20px;"><strong>' . "\t\n" . __('Event start date:' , 'k7-event') . '</strong>' . "\t\n" . $month . ' ' . $day . ' ' . $year . '<br>';

                        $html .= '<img src="' . $this->plugin_url . '/assets/icon/timestampdate.svg" style="width:20px; height:20px;"><strong>' . "\t\n" . __('Start event timestamp:' , 'k7-event') . '</strong>' . "\t\n" . $this->callbacks->formatDate(get_post_meta($event_id , '_ev_start_eventtimestamp' , true)) . '<br>';

                        $html .= '<img src="' . $this->plugin_url . '/assets/icon/finish.svg" style="width:20px; height:20px;"><strong>' . "\t\n" . __('End event timestamp:' , 'k7-event') . '</strong>' . "\t\n" . $this->callbacks->formatDate($endEvent) . '<br>';
                    $html .= '</section>';
                    $html .= '<div class="ch-col-6">';
                    $html .= $event_content;
                    $html .= '</div>';


                    }

                }
                ?>



<script type="text/javascript">
    function myFunction() {
  var x = document.getElementById("event-participant-form");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      defaultDate: '2019-08-12',
      navLinks: true, // can click day/week names to navigate views
      selectable: true,
      selectMirror: true,
      select: function(arg) {
        var title = prompt('Event Title:');
        if (title) {
          calendar.addEvent({
            title: title,
            start: arg.start,
            end: arg.end,
            allDay: arg.allDay
          })
        }
        calendar.unselect()
      },
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      events: [
        {
          title: 'All Day Event',
          start: '2019-08-01'
        },
        {
          title: 'Long Event',
          start: '2019-08-07',
          end: '2019-08-10'
        },
        {
          groupId: 999,
          title: 'Repeating Event',
          start: '2019-08-09T16:00:00'
        },
        {
          groupId: 999,
          title: 'Repeating Event',
          start: '2019-08-16T16:00:00'
        },
        {
          title: 'Conference',
          start: '2019-08-11',
          end: '2019-08-13'
        },
        {
          title: 'Meeting',
          start: '2019-08-12T10:30:00',
          end: '2019-08-12T12:30:00'
        },
        {
          title: 'Lunch',
          start: '2019-08-12T12:00:00'
        },
        {
          title: 'Meeting',
          start: '2019-08-12T14:30:00'
        },
        {
          title: 'Happy Hour',
          start: '2019-08-12T17:30:00'
        },
        {
          title: 'Dinner',
          start: '2019-08-12T20:00:00'
        },
        {
          title: 'Birthday Party',
          start: '2019-08-13T07:00:00'
        },
        {
          title: 'Click for Google',
          url: 'http://google.com/',
          start: '2019-08-28'
        }
      ]
    });

    calendar.render();
  });



</script>


  <div id='calendar'></div>
<?php

                //apply the filter after the main content, before it ends
                //(lets third parties hook into the HTML output to output data)
                $html = apply_filters('event_after_main_content' , $html);

                //readmore
                $html .= '<a class="link" href="' . esc_url($event_permalink) . '" title="' . esc_attr__('view Event' , 'k7-event') . '">' . __('View Event' , 'k7-event') . '</a>';
            $html .= '</section>';
            $html .= '</article>';
            $html .= '<div class="cf"></div>';

            }// and foreach
        } // and if

        return $html;
    }

    public function get_participe_event_form(){
       echo  do_shortcode( '[particip-form]', false );

}

    // public function ev_set_event_custom_columns($columns)
    // {
    //     $title = $columns['title'];
    //     $date = $columns['date'];
    //     unset($columns['title'], $columns['date']);

    //     $columns['name'] = __('Event Name', 'k7-event');
    //     $columns['title'] = $title;
    //     $columns['Price'] =  __('Price', 'k7-event');
    //     $columns['Currency'] = __('Currency', 'k7-event');
    //     $columns['date'] = $date;

    //     return $columns;
    // }

    // public function ev_set_event_custom_columns_data($column, $post_id)
    // {
    //     global $total;
    //     $name = isset($total) ? $data['name'] : '';
    //     $email = isset($data['email']) ? $data['email'] : '';
    //     $telephone = isset($data['telephone']) ? $data['telephone'] : '';
    //     $approved = isset($data['approved']) && $data['approved'] === 1 ? '<strong>'. __( 'YES', 'k7-event').'</strong>' : __(  'NO', 'k7-event');
    //     $party = isset($data['party']) && $data['party'] === 1 ? '<strong>'. __( 'YES', 'k7-event').'</strong>' : __(  'NO', 'k7-event');

    //     switch ($column) {
    //         case 'name':
    //             echo '<strong>' . $name . '</strong><br/><a href="mailto:' . $email . '">' . $email . '</a>';
    //             break;

    //         case 'telephone':
    //             echo $telephone;
    //             break;
    //         case 'approved':
    //             echo $approved;
    //             break;

    //         case 'party':
    //             echo $party;
    //             break;
    //     }
    // }

    // public function ev_set_event_custom_columns_sortable($columns)
    // {
    //     $columns['name'] = __( 'name', 'k7-event');
    //     $columns['approved'] = __( 'approved', 'k7-event');
    //     $columns['party'] = __( 'partic', 'k7-event');

    //     return $columns;
    // }


}