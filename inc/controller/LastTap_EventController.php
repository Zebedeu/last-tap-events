<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/controller
 * @see LastTap_BaseController
 */

defined('ABSPATH') || exit;



class LastTap_EventController extends LastTap_BaseController

{
    private  $is_user_logged_in;

    public function lt_register()
    {

        $this->settings = new LastTap_SettingsApi();

        $this->callbacks = new LastTap_EventCallbacks();

        add_action('init', array($this, 'lt_eventposts'));
        add_action('pre_get_posts', array($this, 'lt_event_query'));
        add_action('admin_init', array($this, 'lt_eventposts_metaboxes'));
        add_action('save_post', array($this, 'lt_eventposts_save_meta'), 1, 2);
        add_filter('the_content', array($this, 'lt_prepend_event_meta_to_content')); //gets our meta data and dispayed it before the content
        add_shortcode('events', array($this, 'lt_event_shortcode_output'));
        add_action('manage_event_posts_columns', array($this, 'lt_set_event_custom_columns'));
        add_action('manage_event_posts_custom_column', array($this, 'lt_set_event_custom_columns_data'), 10, 2);
        add_filter('manage_edit-event_sortable_columns', array($this, 'lt_set_event_custom_columns_sortable'));
        add_action('init', function(){ $this->is_user_logged_in = is_user_logged_in(); $this->is_user_logged_in; });
        add_filter( "views_edit-event", array($this, 'modified_views_event_detail') );


    }
    
    public function lt_eventposts()
    {
        /**
         * Enable the event custom post type
         * http://codex.wordpress.org/Function_Reference/register_post_type
         */
        //Labels for post type
        $labels = array(
            'name' => __('Event', 'last-tap-events'),
            'singular_name' => __('Event', 'last-tap-events'),
            'menu_name' => __('Events', 'last-tap-events'),
            'name_admin_bar' => __('Event', 'last-tap-events'),
            'add_new' => __('Add New', 'last-tap-events'),
            'add_new_item' => __('Add New Event', 'last-tap-events'),
            'new_item' => __('New Event', 'last-tap-events'),
            'edit_item' => __('Edit Event', 'last-tap-events'),
            'view_item' => __('View Event', 'last-tap-events'),
            'all_items' => __('All Events', 'last-tap-events'),
            'searlt_items' => __('Search Events', 'last-tap-events'),
            'parent_item_colon' => __('Parent Event:', 'last-tap-events'),
            'not_found' => __('No Event found.', 'last-tap-events'),
            'not_found_in_trash' => __('No Events found in Trash.', 'last-tap-events'),
        );
        //arguments for post type
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav' => true,
            'query_var' => true,
            'hierarchical' => true,
            'supports' => array('title', 'thumbnail', 'editor', 'excerpt'),
            'has_archive' => true,
            'menu_position' => 20,
            'show_in_admin_bar' => true,
            'menu_icon' => 'dashicons-calendar-alt',
            'rewrite' => array('slug' => 'event', 'with_front' => 'true')
        );
        //register post type
        register_post_type('event', $args);
    }

    /**
     * Adds event post metaboxes for start time and end time
     * http://codex.wordpress.org/Function_Reference/add_meta_box
     *
     * We want two time event metaboxes, one for the start time and one for the end time.
     * Two avoid repeating code, we'll just pass the $identifier in a callback.
     * If you wanted to add this to regular posts instead, just swap 'event' for 'post' in add_meta_box.
     */
    public function lt_eventposts_metaboxes()
    {
        add_meta_box('event_date_start', __( 'Start Date and Time', 'last-tap-events'), array($this, 'event_date'), 'event', 'side', 'default', array('id' => '_start'));
        add_meta_box('event_date_end', __('End Date and Time', 'last-tap-events'), array($this, 'event_date'), 'event', 'side', 'default', array('id' => '_end'));

        add_meta_box(
            'event_location',
            __( 'Event Location', 'last-tap-events'),
            array($this, 'event_location'),
            'event', 'normal',
            'default', array('id' => '_end'));
    }

    // Metabox HTML
    public function event_date($post, $args)
    {
        /*
         attribute prefix form name
         prefix _lt
        */

        $metabox_id = '_lt'. $args['args']['id'];

        global $post, $wp_locale, $postEvent;

        $postEvent = $post;
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'lt_eventposts_nonce');
        $time_adj = current_time('timestamp');
        $_event_detall_infos = get_post_meta($post->ID, '_event_detall_info', false);

            $month = gmdate('m', $time_adj);
            $day = gmdate('d', $time_adj);
            $year = gmdate('Y', $time_adj);
            $hour = gmdate('H', $time_adj);
            $min = '00';
 foreach ($_event_detall_infos as $key => $_event_detall_info) {
         

        $month = $_event_detall_info[$metabox_id.'_month'];
        
        $day = $_event_detall_info[$metabox_id.'_day'];
        
        $year = $_event_detall_info[$metabox_id.'_year'];
        
        $hour = $_event_detall_info[$metabox_id.'_hour'];

        $min = $_event_detall_info[$metabox_id.'_minute'];

    }
        echo '<div class="wrap">';
        $month_s = '<select name="' . $metabox_id . '_month">';
        for ($i = 1; $i < 13; $i = $i + 1) {
            $month_s .= "\t\t\t" . '<option value="' . zeroise($i, 2) . '"';
            if ($i == $month)
                $month_s .= ' selected="selected"';
            $month_s .= '>' . $wp_locale->get_month_abbrev($wp_locale->get_month($i)) . "</option>\n";
        }
        $month_s .= '</select>';
        echo $month_s;
        echo '<input class="small-text" type="number" step="1" min="1" name="' . $metabox_id . '_day" value="' . $day . '" size="2"  />';
        echo '<input class="small-text" type="number" step="1" min="1" name="' . $metabox_id . '_year" value="' . $year . '" size="4"  /> @ ';
        echo '<input class="small-text" type="number" step="1" min="1" name="' . $metabox_id . '_hour" value="' . $hour . '" size="2" />:';
        echo '<input class="small-text" type="number" step="1" min="1" name="' . $metabox_id . '_minute" value="' . $min . '" size="2"  />';
        echo '</div>';


    }

    public function event_location()
    {
        global $post;
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'lt_eventposts_nonce');
        // The metabox HTML
        $event_country = get_post_meta($post->ID, '_lt_event_country', true);
        $event_city = get_post_meta($post->ID, '_lt_event_city', true);
        $event_address = get_post_meta($post->ID, '_lt_event_address', true);
        $event_email = get_post_meta($post->ID, '_lt_event_email', true);
        $event_organizer = get_post_meta($post->ID, '_lt_event_organizer', true);
        $event_phone = get_post_meta($post->ID, '_lt_event_phone', true);
        $event_phone_2 = get_post_meta($post->ID, '_lt_event_phone_2', true);
        $event_street = get_post_meta($post->ID, '_lt_event_street', true);
        $event_partici = get_post_meta($post->ID, '_lt_event_partic_limit', true); 
        $event_price = get_post_meta($post->ID, '_lt_event_price', true); 

        $detal = get_post_meta($post->ID, '_event_detall_info', true); ?>


    <div class="wrap">
        <form>
        <table class="form-table">
            <tbody>
                <tr>

                    <th>

                        <label for="input-text"><?php _e('Event price :'); ?></label>
                    </th>
                    <td>

                        <input type="text" name="_lt_event_price"  value="<?php echo $detal["_lt_event_price"] ?? ""; ?>" />
                    </td>


                    <th>

                        <label for="input-text"><?php _e('Event Currency :'); ?></label>
                    </th>
                    <td>

                            <?php echo $value = esc_attr( get_option( 'event_currency' ) );?>

                    </td>
                </tr>
                <tr>
                    <th>

                         <label for="input-text"><?php _e('Event Participe Limits:'); ?></label>
                    </th>
                    <td>

                        <input type="text" name="_lt_event_partic_limit" value="<?php echo $detal["_lt_event_partic_limit"] ?? ""; ?>"/>
                    </td>
                    <th>

                        <label for="input-text"><?php _e('Event Country:'); ?></label>
                    </th>
                    <td>

                        <input type="text" name="_lt_event_country" value="<?php echo $detal["_lt_event_country"] ?? ""; ?>"/>
                    </td>
                </tr>
                <tr>
                    <th>

                        <label for="input-text"><?php _e('Event City:'); ?></label>
                    </th>
                    <td>

                        <input type="text" name="_lt_event_city" value="<?php echo $detal["_lt_event_city"] ?? ""; ?>"/>
                    </td>
                    <th>
                        <label for="input-text"><?php _e('Event Address:'); ?></label>

                    </th>
                    <td>
                        <input type="text" name="_lt_event_address" value="<?php echo $detal["_lt_event_address"] ?? ""; ?>"/>
                    </td>
                </tr>
                <tr>
                    <th>

                         <label for="input-text"><?php _e('Event Street:'); ?></label>
                    </th>
                    <td>

                         <input type="text" name="_lt_event_street" value="<?php echo $detal["_lt_event_street"] ?? ""; ?>"/>
                    </td>



                    <th>

                        <label for="input-text"><?php _e('Event Email:'); ?></label>
                    </th>
                    <td>

                        <input type="email" name="_lt_event_email" value="<?php echo $detal["_lt_event_email"] ?? ""; ?>"/>
                    </td>
                    <tr>
                    <th>

                        <label for="input-text"><?php _e('Event Organizers email:'); ?></label>
                    </th>
                    <td>

                        <input type="email" name="_lt_event_organizer" value="<?php echo $detal["_lt_event_organizer"] ?? ""; ?>"/>
                    </td>

                    <th>

                        <label for="input-text"><?php _e('Event Phone:'); ?></label>
                    </th>
                    <td>

                        <input type="tel" name="_lt_event_phone" value="<?php echo $detal["_lt_event_phone"] ?? ""; ?>"/>
                    </td>
                </tr>
                <tr>
                    <th>

                    <label for="input-text"><?php _e('Event Phone 2:'); ?></label>
                    </th>
                    <td>

                    <input type="tel" name="_lt_event_phone_2" value="<?php echo $detal["_lt_event_phone_2"] ?? ""; ?>"/>
                    </td>
               
                </tr>               
            </tbody>
        </table>
    </form>
</div>


        <?php
    }

   // Save the Metabox Data
    public function lt_eventposts_save_meta($post_id, $post)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;
        if (!isset($_POST['lt_eventposts_nonce']))
            return;
        if (!wp_verify_nonce($_POST['lt_eventposts_nonce'], plugin_basename(__FILE__)))
            return;
        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post->ID))
            return;
        // OK, we're authenticated: we need to find and save the data
        // We'll put it into an array to make it easier to loop though

        $metabox_ids = array('_lt_start', '_lt_end');
        foreach ($metabox_ids as $key) {



            $aa =  sanitize_text_field( $_POST[$key . '_year']);
            $mm =  sanitize_text_field( $_POST[$key . '_month']);
            $jj =  sanitize_text_field( $_POST[$key . '_day']);
            $hh =  sanitize_text_field( $_POST[$key . '_hour']);
            $mn =  sanitize_text_field( $_POST[$key . '_minute']);

            $aa = ($aa <= 0) ? date('Y') : $aa;
            $mm = ($mm <= 0) ? date('n') : $mm;
            $jj = sprintf('%02d', $jj);
            $jj = ($jj > 31) ? 31 : $jj;
            $jj = ($jj <= 0) ? date('j') : $jj;
            $hh = sprintf('%02d', $hh);
            $hh = ($hh > 23) ? 23 : $hh;
            $mn = sprintf('%02d', $mn);
            $mn = ($mn > 59) ? 59 : $mn;

            $events_meta[$key . '_year'] = $aa;
            $events_meta[$key . '_month'] = $mm;
            $events_meta[$key . '_day'] = $jj;
            $events_meta[$key . '_hour'] = $hh;
            $events_meta[$key . '_minute'] = $mn;
            $events_meta[$key . '_eventtimestamp'] = $aa . '-' . $mm . '-' . $jj . ' ' . $hh . ':' . $mn;

        }

        // Save Locations Meta

        $events_meta['_lt_event_country'] = sanitize_text_field($_POST['_lt_event_country']);
        $events_meta['_lt_event_city'] = sanitize_text_field($_POST['_lt_event_city']);
        $events_meta['_lt_event_address'] = sanitize_text_field($_POST['_lt_event_address']);
        $events_meta['_lt_event_email'] = sanitize_email($_POST['_lt_event_email']);
        $events_meta['_lt_event_organizer'] = sanitize_text_field($_POST['_lt_event_organizer']);
        $events_meta['_lt_event_phone'] = $this->callbacks->lt_sanitize_number($_POST['_lt_event_phone']);
        $events_meta['_lt_event_phone_2'] = $this->callbacks->lt_sanitize_number($_POST['_lt_event_phone_2']);
        $events_meta['_lt_event_street'] = sanitize_text_field($_POST['_lt_event_street']);
        $events_meta['_lt_event_partic_limit'] = $this->callbacks->lt_sanitize_number($_POST['_lt_event_partic_limit']);
        $events_meta['_lt_event_price'] = $this->callbacks->lt_sanitize_number($_POST['_lt_event_price']);

            if ($post->post_type == 'revision') return; // Don't store custom data twice

            $detal = get_post_meta($post->ID, '_event_detall_info', false); 
            if( $detal ) {
                update_post_meta($post->ID, '_event_detall_info', $events_meta);

            }else{
                add_post_meta($post->ID, '_event_detall_info', $events_meta);
 
            }

        // Add values of $events_meta as custom fields
        // foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
        //     if ($post->post_type == 'revision') return; // Don't store custom data twice
        //     $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
        //     if (get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
        //         update_post_meta($post->ID, $key, $value);
        //     } else { // If the custom field doesn't have a value
        //         add_post_meta($post->ID, $key, $value);
        //     }
        //     if (!$value) delete_post_meta($post->ID, $key); // Delete if blank
        // }
    }
    /**
     * Helpers to display the date on the front end
     */
    // Get the Month Abbreviation

    public function lt_get_the_month_abbr($month)
    {
        global $wp_locale;
        for ($i = 1; $i < 13; $i = $i + 1) {
            if ($i == $month)
                $monthabbr = $wp_locale->get_month_abbrev($wp_locale->get_month($i));
        }
        return $monthabbr;
    }

    // Display the date

    public function lt_get_the_event_date()
    {
        global $post;


        $eventdate = '';
        $month = get_post_meta($post->ID, '_month', true);
        $eventdate = lt_get_the_month_abbr($month);
        $eventdate .= ' ' . get_post_meta($post->ID, '_day', true) . ',';
        $eventdate .= ' ' . get_post_meta($post->ID, '_year', true);
        $eventdate .= ' at ' . get_post_meta($post->ID, '_hour', true);
        $eventdate .= ':' . get_post_meta($post->ID, '_minute', true);
        return $eventdate;
    }

    /**
     * Customize Event Query using Post Meta
     *
     * @link http://www.billerickson.net/customize-the-wordpress-query/
     * @param object $query data
     *
     */
    public function lt_event_query($query)
    {

        // http://codex.wordpress.org/Function_Reference/current_time
        $current_time = current_time('mysql');
        list($today_year, $today_month, $today_day, $hour, $minute, $second) = preg_split('([^0-9])', $current_time);
        $current_timestamp = $today_year . $today_month . $today_day . $hour . $minute;
        global $wp_the_query;

        if ($wp_the_query === $query && !is_admin() && is_post_type_archive('events')) {
            $meta_query = array(
                array(
                    'key' => '_lt_start_eventtimestamp',
                    'value' => $current_timestamp,
                    'compare' => '>'
                )
            );
            $query->set('meta_query', $meta_query);
            $query->set('orderby', 'meta_value_num');
            $query->set('meta_key', '_lt_start_eventtimestamp');
            $query->set('order', 'ASC');
            $query->set('posts_per_page', '2');
        }

    }

    //shortcode display
    public function lt_event_shortcode_output($atts, $content = '', $tag)
    {

        //build default arguments
        $arguments = shortcode_atts(array(
                'event_id' => '',
                'number_of_event' => -1)
           , $atts, $tag);

        //uses the main output function of the location class
        return $this->lt_get_event_output($arguments);

    }

    public function lt_prepend_event_meta_to_content($content)
    {
        global $post, $post_type, $wp_locale;

        $event = $post;

        $current_time = current_time('mysql');
        list($today_year, $today_month, $today_day, $hour, $minute, $second) = preg_split('([^0-9])', $current_time);
        $current_timestamp = $today_year . '-' . $today_month . '-' . $today_day . ' ' . $hour . ':' . $minute;


        if ( $post_type == 'event' && is_singular('event')) { ?>
            <body onload='verHora()'><h3 id='relogio'></h3>


            <?php    if ( !$this->is_user_logged_in ) {
                            echo '<div class="lastTap col-lg-12 links">'. __('Please login by', 'last-tap-events') .'<a href="'.wp_login_url().'">'. __(' clicking here','last-tap-events').'</a>.</div>';
                            }?>

            <div class="lastTap col-lg-12">
                <div class="lastTap row card-header-event">
                        <div class="lastTap col-lg-7">

            <img src="<?php echo $this->plugin_url . "/assets/icon/compose.svg"; ?>" style="width:20px; height:20px;">
            <strong><?php _e('Publish date:', 'last-tap-events'); ?></strong><p><?php echo the_date('M d Y'); ?></p>
            <?php
            // Gets the event start month from the meta field
            $month = get_post_meta($event->ID, '_event_detall_info', true)['_lt_start_month'];
            // Converts the month number to the month name
            $month = $wp_locale->get_month_abbrev($wp_locale->get_month($month));
            // Gets the event start day
            $day = get_post_meta($event->ID, '_event_detall_info', true)['_lt_start_day'];
            // Gets the event start year
            $year = get_post_meta($event->ID, '_event_detall_info', true)['_lt_start_year'];
            $event_partici = get_post_meta($event->ID, '_event_detall_info', true)['_lt_event_partic_limit'];
            ?>

            <?php

            $start = get_post_meta($event->ID, '_event_detall_info', true)['_lt_start_eventtimestamp'];
            $endEvent = get_post_meta($event->ID, '_event_detall_info', true)['_lt_end_eventtimestamp'];

            ?>
            <img src="<?php echo $this->plugin_url . "/assets/icon/clock.svg"; ?>" style="width:20px; height:20px;">
            <strong><?php echo "\t\n" . __('Event start date:', 'last-tap-events'); ?></strong><p><?php echo "\t\n" . $month . ' ' . $day . ' ' . $year; ?></p>
            
            <img src="<?php echo $this->plugin_url . "/assets/icon/timestampdate.svg"; ?>"
                 style="width:20px; height:20px;">

            <strong><?php echo "\t\n" . __('Start timestamp:', 'last-tap-events'); ?></strong><p><?php echo "\t\n" . $this->callbacks->formatDate($start); ?></p>
            <img src="<?php echo $this->plugin_url . "/assets/icon/finish.svg"; ?>" style="width:20px; height:20px;">
            <strong><?php echo "\t\n" . __('End timestamp:', 'last-tap-events'); ?></strong><p><?php echo "\t\n" . $this->callbacks->formatDate($endEvent); ?></p>
                </div>
                    <div class="lastTap col-lg-4">
                            <?php
                             $price = get_post_meta($event->ID, '_event_detall_info', true)['_lt_event_price'];

                             $currency = get_option( 'event_currency', true );

                             if("" == $currency ){
                                $currency = 'USD';
                             }

                             if( empty($price) ){
                                $price = __('Free', 'last-tap-events');
                                $currency = null;
                             }
                        ?>                        <strong><?php printf( __('Price: %s %s ', 'last-tap-events'), $price, $currency);?>  </strong>
                    <hr>
                        <?php 

                             $count_participant = $this->lastTap_count_event_participant($event->ID);

                            $number = 0;

                        if($event_partici != 0){
                            $number = ($event_partici - count($count_participant));
                        }else{
                            $number = 0;
                        }

                        ?>

                            <strong><?php printf( __('Nº of places available:  %s ', 'last-tap-events'), $number); ?></strong><br><hr>
                                <strong><?php printf( 
                                    __('Nº of participants: %s', 'last-tap-events'), count($count_participant) 
                                    ); ?></strong>
                    </div> <!-- col-lg-7 -->

                </div>
            </div>

            <div class="lastTap col-lg-12">
                <div class="lastTap row card-header-event">
                    <div class="lastTap col-lg-6 card-header-event">

                        <label class="lastTap center"><img
                                    src="<?php echo $this->plugin_url . "/assets/icon/location.svg"; ?>"
                                    style="width:40px; height:40px;">
                            <h2><?php _e(' Location', 'last-tap-events'); ?></h2></label>
                        <br>
                        <strong><?php _e('Event County:', 'last-tap-events'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID, '_event_detall_info', true)['_lt_event_country']; ?></small>
                        <br>
                        <strong><?php _e('Event City/Province:', 'last-tap-events'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID, '_event_detall_info', true)['_lt_event_city']; ?></small>
                        <br>

                        <hr>
                        <strong><?php _e('Event Address:', 'last-tap-events'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID, '_event_detall_info', true)['_lt_event_address']; ?></small>
                        <br>
                        <strong><?php _e('Event Street:', 'last-tap-events'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID, '_event_detall_info', true)['_lt_event_street']; ?></small>
                        <br>

                    </div>
                    <div class="lastTap col-lg-6">

                        <label class="lastTap center"><img
                                    src="<?php echo $this->plugin_url . "/assets/icon/contacteditor.svg"; ?>"
                                    style="width:40px; height:40px;">
                            <h2><?php _e(' Contact', 'last-tap-events'); ?></h2></label>
                        <br>
                        <strong><?php _e('Event Phone:', 'last-tap-events'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID, '_event_detall_info', true)['_lt_event_phone']; ?></small>
                        <br>
                        <strong><?php _e('Event Phone 2:', 'last-tap-events'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID, '_event_detall_info', true)['_lt_event_phone_2']; ?></small>
                        <br>
                        <hr>
                        <strong><?php _e('Event Email:', 'last-tap-events'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID, '_event_detall_info', true)['_lt_event_email']; ?></small>
                        <br>
                        <strong><?php _e('Event or organizer email:', 'last-tap-events'); ?></strong>
                        <small><?php echo "\t\n" . get_post_meta($event->ID, '_event_detall_info', true)['_lt_event_organizer']; ?></small>
                        <br>
                    </div>

                </div>
            </div>

            
            <?php $event_permalink = get_permalink($event);
            $html = '';
            $html .= '<h2 class="lastTap title">';
            $html .= '<a href="' . esc_url($event_permalink) . '" title="' . esc_attr__('view Event', 'last-tap-events') . '">';
            $html .= '</a>';
            $html .= '</h2>'; ?>

            <?php

            $html .= '<div class="lastTap row"><div class="lastTap col-lg-12">';
            $html .= $content;
            $html .= '</div><div class="lastTap col-lg-12">';
            // $html .= get_the_post_thumbnail($event->ID, 'thumbnail');
            $html .= "</div></div>"; 
            echo  $html;



    // how to build raw content - QRCode with detailed Business Card (VCard)

            ?>

            <div class="lastTap col-lg-12">
                <div class="lastTap row">
                    <hr>
                    <div class="lastTap col-lg-12">
                        <?php 
                        if($event_partici == count($count_participant)){?>
                            <header class="lastTap header">
                                <button class="lastTap tab" style="background: red;" onclick="myFunction()"><?php esc_html_e( 'INSCRIPTIONS ARE CLOSED! We have reached the maximum number of members, and that is why registration is closed.!', 'last-tap-events' );?></button>
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

    public function lt_get_event_output($arguments = "")
    {

        global $post;

        $wp_locale = new WP_Locale();

        add_image_size( 'event-thumb', 270, 175, false );



        //default args
        $default_args = array(
            'event_id' => '',
            'number_of_event' => -1
        );

        //update default args if we passed in new args
        if (!empty($arguments) && is_array($arguments)) {
            //go through each supplied argument
            foreach ($arguments as $arg_key => $arg_val) {
                //if this argument exists in our default argument, update its value
                if (array_key_exists($arg_key, $default_args)) {
                    $default_args[$arg_key] = $arg_val;
                }
            }
        }


        //find event
        $event_args = array(
            'post_type' => 'event',
            'posts_per_page' => $default_args['number_of_event'],
            'post_status' => 'publish',
            'meta_key' => '_lt_start_eventtimestamp',
            'orderby' => 'meta_value_num',

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
        list($today_year, $today_month, $today_day, $hour, $minute, $second) = preg_split('([^0-9])', $current_time);
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

                $html .= '<article class="lastTap col-lg-12 " style="border: 12px solid '.get_option( 'event_border_color').'">';
                $html .= '<div class="lastTap row">';

                $html .= '<h2 class="lastTap title">';
                $html .= '<a href="' . esc_url($event_permalink) . '" title="' . esc_attr__('view Event', 'last-tap-events') . '">';
                $html .= $event_title;
                $html .= '</a>';
                $html .= '</h2>';


                $html .= '<section class="lastTap col-lg-6 sermon" >';

                $event_thumbnail = get_the_post_thumbnail($event_id, 'event-thumb');
                $html .= '<div class="lastTap col-lg-1 image_content">';

                $event_content = apply_filters('the_content', $event->post_content);
                $html .= '</div>';

                if (!empty($event_content)) {
                    $event_content = strip_shortcodes(wp_trim_words($event_content, 40, '...'));
                }


                // http://codex.wordpress.org/Function_Reference/current_time
                $current_time = current_time('mysql');
                list($today_year, $today_month, $today_day, $hour, $minute, $second) = preg_split('([^0-9])', $current_time);
                $current_timestamp = $today_year . '-' . $today_month . '-' . $today_day . ' ' . $hour . ':' . $minute;
                //(lets third parties hook into the HTML output to output data)
                $html = apply_filters('event_before_main_content', $html);

                //image & content
                if (!empty($event_thumbnail) || !empty($event_content)) {

                    if (!empty($event_thumbnail)) {
                        $html .= '<p class="lastTap col-lg-3 image_content">';
                        $html .= $event_thumbnail;

                        $html .= '</p>';
                    }else{
                    $html .= '<p class="lastTap col-lg-3 image_content">';
                    $html .= '<img src="' . $this->plugin_url . '/assets/images/no-image-available-icon-6.png">';
                    $html .= '</p>';


                    }
                    if (!empty($event_content)) {
                        $html .= '<img src="' . $this->plugin_url . '/assets/icon/compose.svg" style="width:20px; height:20px;"><strong>' . "\t\n" . __('Publish date:'."\t\n", 'last-tap-events') . '</strong>' . get_the_date('M d Y') . '<br>';

                        ?>
                        <body onload='verHora()'><h3 id='relogio'></h3></body><?php


                        // Gets the event start month from the meta field
                        $month = get_post_meta($event_id, '_event_detall_info', true)['_lt_start_month'];
                        // Converts the month number to the month name
                        $month = $wp_locale->get_month_abbrev($wp_locale->get_month($month));
                        // Gets the event start day
                        $day = get_post_meta($event_id, '_event_detall_info', true)['_lt_start_day'];
                        // Gets the event start year
                        $year = get_post_meta($event_id, '_event_detall_info', true)['_lt_start_year'];
                        $startEvent = get_post_meta($event_id, '_event_detall_info', true)['_lt_start_eventtimestamp'];
                        $endEvent = get_post_meta($event_id, '_event_detall_info', true)['_lt_end_eventtimestamp'];

                        $current_timestamp = $current_timestamp;


                        $html .= '<img src="' . $this->plugin_url . '/assets/icon/clock.svg" style="width:20px; height:20px;"><strong>' . "\t\n" . __('Event start date:', 'last-tap-events') . '</strong>' . "\t\n" . $month . ' ' . $day . ' ' . $year . '<br>';

                        $html .= '<img src="' . $this->plugin_url . '/assets/icon/timestampdate.svg" style="width:20px; height:20px;"><strong>' . "\t\n" . __('Start event timestamp:', 'last-tap-events') . '</strong>' . "\t\n" . $this->callbacks->formatDate($startEvent) . '<br>';

                        $html .= '<img src="' . $this->plugin_url . '/assets/icon/finish.svg" style="width:20px; height:20px;"><strong>' . "\t\n" . __('End event timestamp:', 'last-tap-events') . '</strong>' . "\t\n" . $this->callbacks->formatDate($endEvent) . '<br>';
                    $html .= '</section>';
                    $html .= '<div class="lastTap col-lg-6">';
                    $html .= $event_content;
                    $html .= '</div>';


                    }

                }

                //apply the filter after the main content, before it ends
                //(lets third parties hook into the HTML output to output data)
                $html = apply_filters('event_after_main_content', $html);

                //readmore
                $html .= '<a class="lastTap link" href="' . esc_url($event_permalink) . '" title="' . esc_attr__('view Event', 'last-tap-events') . '">' . __('View Event', 'last-tap-events') . '</a>';
            $html .= '</section>';
            $html .= '</article>';
            $html .= '<div class="lastTap cf"></div>';

            }// and foreach
        } // and if

        return $html;
    }

    public function get_participe_event_form(){
        if ( $this->is_user_logged_in ) {
                echo  do_shortcode( '[particip-form]', false );
        }else{
             echo 'Please login by <a href="'.wp_login_url().'">clicking here</a>.';
        }

    }

    public function lastTap_count_event_participant($event_id){

        $all_post_ids = get_posts(array(
                                'fields'          => 'post_id',
                                'posts_per_page'  => -1,
                                'post_type' => 'participant'
                            ));
                            $count_participant = [];
                        foreach ($all_post_ids as $k => $v) {
                            $count = get_post_meta( $v->ID, '_event_participant_key', false );
                                foreach ($count as $key => $value) {
                                    if($value['post_event_id'] == $event_id && $value['approved'] == 1){
                                        $count_participant[] = $value['post_event_id'];
                                    }
                                }
                                 
                            }

                            return $count_participant;


    }

    public function lt_set_event_custom_columns($columns)
    {
        $title = $columns['title'];
        $date = $columns['date'];
        unset($columns['title'], $columns['date']);

        $columns['title'] = __( 'Event name', 'last-tap-events');
        $columns['telephone'] =  __('Telephone', 'last-tap-events');
        $columns['price'] =  __('Price', 'last-tap-events');
        $columns['email'] =  __('Event Organizers email', 'last-tap-events');
        $columns['location'] =  __('Location', 'last-tap-events');
        $columns['data'] = __('Date and Time', 'last-tap-events');

        return $columns;
    }

    public function lt_set_event_custom_columns_data($column, $post_id)
    {
        $_event_detall_info = get_post_meta($post_id, '_event_detall_info', true);

        $corrency = esc_attr( get_option( 'event_currency' ) );
        // $title = isset($title['name']) ? $title['name'] : '';
        $email = isset($_event_detall_info['_lt_event_organizer']) ? $_event_detall_info['_lt_event_organizer'] : '';
        $telephone = isset($_event_detall_info['_lt_event_phone']) ? $_event_detall_info['_lt_event_phone'] : '';
        $price = isset($_event_detall_info['_lt_event_price']) ? $_event_detall_info['_lt_event_price'] : '00.00';
        $startEvent = isset($_event_detall_info['_lt_start_eventtimestamp']) ? $_event_detall_info['_lt_start_eventtimestamp'] : '00:00';
        $andEvent = isset($_event_detall_info['_lt_end_eventtimestamp']) ? $_event_detall_info['_lt_end_eventtimestamp'] : '00:00';
        $_lt_event_street = isset($_event_detall_info['_lt_event_street']) ? $_event_detall_info['_lt_event_street'] : '00:00';
        $_lt_event_address = isset($_event_detall_info['_lt_event_address']) ? $_event_detall_info['_lt_event_address'] : '00:00';
        $_lt_event_city = isset($_event_detall_info['_lt_event_city']) ? $_event_detall_info['_lt_event_city'] : '00:00';

        $corrency = isset($corrency) ? $corrency : '';

        $upload_dir = wp_upload_dir();
        $upload_dir = $upload_dir['baseurl'] . '/2019/12/wp-header-logo.png' ;
        $a =preg_replace('/^https?:/', '', $upload_dir);


        switch ($column) {
            case 'title':
                echo esc_html('<strong>' . $title . '</strong><br/><a href="mailto:' . $email . '">' . $email . '</a>');
                break;

            case 'telephone':
                echo esc_html($telephone);
                break;
            case 'price':
                echo esc_html($price . ' '.$corrency);
                break;

            case 'email':
                echo esc_html($email);
                break;
            case 'location':
                echo "<strong>" .$_lt_event_street . ' ' .  $_lt_event_address . "</strong><p>". ' ' . $_lt_event_city . '</p>';
                break;
            case 'data':
                echo wp_kses_post( $this->callbacks->formatDate($startEvent, "F j Y" ) . ' - ' . $this->callbacks->formatDate($andEvent,  "F j Y") . '<p>'. __('Time:', 'last-tap-events'). $this->callbacks->formatDate($startEvent, "H:i" ) . ' - ' . $this->callbacks->formatDate($andEvent,  "H:i") . '</p>');
                break;

        }

    }


    public function lt_set_event_custom_columns_sortable($columns)
    {
        $columns['title'] = __( 'name', 'last-tap-events');
        $columns['telephone'] = __( 'Telephone', 'last-tap-events');
        $columns['price'] = __( 'price', 'last-tap-events');
        $columns['email'] = __( 'Email', 'last-tap-events');
        $columns['location'] = __( 'Location', 'last-tap-events');
        $columns['data'] = __( 'Date and Time', 'last-tap-events');

        return $columns;
    }

    function modified_views_event_detail( $views ) 
    {
        $views['all'] = str_replace( 'All ', 'All Events ', $views['all'] );

        if($views['publish']){
             $views['publish'] = str_replace( 'Published ', __('Event Published ', 'last-tap-events'), $views['publish'] );
        }

        return $views;

    }

}