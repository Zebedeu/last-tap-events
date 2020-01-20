<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/controller
 * @see LastTap_BaseController
 */

defined('ABSPATH') || exit;

class LastTap_LocationController extends LastTap_BaseController

{
    public $settings;

    public $callbacks;

    private static $location_hour_days = array();

    public function lt_register()
    {
        if (!$this->lt_activated('location_manager')) return;

        $this->settings = new LastTap_SettingsApi();

        $this->callbacks = new LastTap_LocationCallbacks();


        add_action('init' , array($this , 'lt_set_hour_days')); //sets the default cult hour days (used by the content type)
        add_action('init' , array($this , 'lt_Location_cpt')); //register location content type
        add_action('add_meta_boxes' , array($this , 'lt_add_location_meta_boxes')); //add meta boxes
        add_action('save_post_locations' , array($this , 'lt_save_location')); //save location
        add_filter('the_content' , array($this , 'lt_prepend_location_meta_to_content')); //gets our meta data and dispayed it before the content
        // $this->lt_setLocationSettingsPage();

        add_shortcode('locations' , array($this , 'lt_location_shortcode_output'));
    }


    //shortcode display
    public function lt_location_shortcode_output($atts , $content = '' , $tag)
    {

        //build default arguments
        $arguments = shortcode_atts(array(
                'location_id' => '' ,
                'number_of_locations' => -1)
            , $atts , $tag);


        //uses the main output function of the location class
        return $this->lt_get_locations_output($arguments);

    }

    // public function lt_setLocationSettingsPage()
    // {
    //     $subpage = array(
    //         array(
    //             'parent_slug' => 'edit.php?post_type=locations' ,
    //             'page_title' => __('Settings', 'last-tap-events') ,
    //             'menu_title' => __('Settings', 'last-tap-events') ,
    //             'capability' => 'manage_options' ,
    //             'menu_slug' => 'event_location_settings' ,
    //             'callback' => array($this->callbacks , 'lt_locationSettings')
    //         )
    //     );

    //     $this->settings->lt_addSubPages($subpage)->lt_register();
    // }


    /**
     * @param array $location_hour_days
     */
    public static function lt_set_hour_days()
    {
        self::$location_hour_days = apply_filters('location_hours_days' ,
            array('monday' => __('Monday' , 'last-tap-events') ,
                'tuesday' => __('Tuesday' , 'last-tap-events') ,
                'wednesday' => __('Wednesday' , 'last-tap-events') ,
                'thursday' => __('Thursday' , 'last-tap-events') ,
                'friday' => __('Friday' , 'last-tap-events') ,
                'saturday' => __('Saturday' , 'last-tap-events') ,
                'sunday' => __('Sunday' , 'last-tap-events') ,
            )
        );

    }

    public static function lt_Location_cpt()
    {

        //Labels for post type
        $labels = array(
            'name' => __('Location' , 'last-tap-events') ,
            'singular_name' => __('Location' , 'last-tap-events') ,
            'menu_name' => __('Locations' , 'last-tap-events') ,
            'name_admin_bar' => __('Location' , 'last-tap-events') ,
            'add_new' => __('Add New' , 'last-tap-events') ,
            'add_new_item' => __('Add New Location' , 'last-tap-events') ,
            'new_item' => __('New Location' , 'last-tap-events') ,
            'edit_item' => __('Edit Location' , 'last-tap-events') ,
            'view_item' => __('View Location' , 'last-tap-events') ,
            'all_items' => __('All Locations' , 'last-tap-events') ,
            'searlt_items' => __('Search Locations' , 'last-tap-events') ,
            'parent_item_colon' => __('Parent Location:' , 'last-tap-events') ,
            'not_found' => 'No Locations found.' ,
            'not_found_in_trash' => __('No Locations found in Trash.' , 'last-tap-events') ,
        );
        //arguments for post type
        $args = array(
            'labels' => $labels ,
            'public' => true ,
            'publicly_queryable' => true ,
            'show_ui' => true ,
            'show_in_nav' => true ,
            'query_var' => true ,
            'hierarchical' => false ,
            'supports' => array('title' , 'thumbnail' , 'editor', 'excerpt') ,
            'has_archive' => true ,
            'menu_position' => 20 ,
            'show_in_admin_bar' => true ,
            'menu_icon' => 'dashicons-location-alt' ,
            'rewrite' => array('slug' => 'locations' , 'with_front' => 'true')
        );
        //register post type
        register_post_type('locations' , $args);
    }

    //adding meta boxes for the location content type*/
    public function lt_add_location_meta_boxes()
    {

        add_meta_box(
            'location_meta_box' , //id
            __('Location Information' , 'last-tap-events') , //name
            array($this , 'lt_location_meta_box_display') , //display function
            'locations' , //post type
            'normal' , //location
            'default' //priority
        );
    }

    //display function used for our custom location meta box*/
    public function lt_location_meta_box_display($post)
    {

        //set nonce field
        wp_nonce_field('location_nonce' , 'location_nonce_field');

        //collect variables
        $location_phone = get_post_meta($post->ID , '_lt_location_phone' , true);
        $location_email = get_post_meta($post->ID , '_lt_location_email' , true);
        $location_address = get_post_meta($post->ID , '_lt_location_address' , true);
        $location_country = get_post_meta($post->ID , '_lt_location_country' , true);
        $location_city = get_post_meta($post->ID , '_lt_location_city' , true);
        $location_address = get_post_meta($post->ID , '_lt_location_address' , true);
        $location_street = get_post_meta($post->ID , '_lt_location_street' , true);

        ?>
        <p><?php _e('Enter additional information about your location' , 'last-tap-events'); ?></p>
        <div class="field-container">
            <?php
            //before main form elementst hook
            do_action('location_admin_form_start');
            ?>
            <div class="field">
                <label for="_lt_location_phone"><?php _e('Contact Phone' , 'last-tap-events'); ?></label><br/>
                <small><?php _e('main contact number' , 'last-tap-events'); ?></small>
                <input type="tel" name="_lt_location_phone" spellcheck="true" id="location_phone"
                       value="<?php echo $location_phone; ?>" autocomplete="off"/>
            </div>
            <hr>
            <div class="field">
                <label for="_lt_location_email"><?php _e('Contact Email' , 'last-tap-events'); ?></label><br/>
                <small><?php _e('Email contact' , 'last-tap-events'); ?></small>
                <input type="email" name="_lt_location_email" id="location_email"
                       value="<?php echo $location_email; ?>" autocomplete="off"/>
            </div>
            <hr>
            <div class="field">
                <label for="_lt_location_country"><?php _e('Location Country:'); ?></label><br>
                <small><?php _e('location where the cult will take place' , 'last-tap-events'); ?></small>
                <input type="text" name="_lt_location_country" value="<?php echo $location_country; ?>"/>
            </div>

            <hr>
            <div class="field">
                <label for="_city"><?php _e('City:'); ?></label><br>
                <small><?php _e('City/Province where the cult will take place' , 'last-tap-events'); ?></small>
                <input type="text" name="_lt_location_city" value="<?php echo $location_city; ?>"/>
            </div>

            <hr>
            <div class="field">
                <label for="_lt_location_address"><?php _e('Address:'); ?></label><br>
                <small><?php _e('Location Address' , 'last-tap-events'); ?></small>
                <input type="text" name="_lt_location_address" value="<?php echo $location_address; ?>"/>
            </div>
            <div class="field">
                <label for="_lt_location_street"><?php _e('Location Street:'); ?></label><br>
                <small><?php _e('Location Street' , 'last-tap-events'); ?></small>
                <input type="text" name="_lt_location_street" value="<?php echo $location_street; ?>"/>
            </div>
            <?php
            //cult hours
            if (!empty(self::$location_hour_days)) {
                echo '<div class="field">';
                echo '<label>'. __( 'Location Hours', 'last-tap-events'). '</label>';
                echo '<small>' . __('Location hours for the location (e.g 9am - 5pm) ' , 'last-tap-events') . '</small>';
                //go through all of our registered cult hour days
                foreach (self::$location_hour_days as $day_key => $day_value) {
                    //collect cult hour meta data
                    $location_hour_value = get_post_meta($post->ID , '_lt_location_hours_' . $day_key , true);
                    //dsiplay label and input
                    echo '<br>';
                    echo '<label for="_lt_location_hours_' . $day_key . '">' . ucfirst($day_key) . '</label>';
                    echo '<input type="text" name="_lt_location_hours_' . $day_key . '" id="location_hours_' . $day_key . '" value="' . $location_hour_value . '" autocomplete="off"/>';
                }
                echo '</div>';
            }
            ?>
            <?php
            //after main form elementst hook
            do_action('location_admin_form_end');
            ?>
        </div>
        <?php

    }
    /*
    * filter return string
    * string example return  'class="col-lg-12" style="color: orange; border: 12px, solid #000000;"';
    */

    public function lt_prepend_location_meta_to_content($content)
    {

        global $post , $post_type;

        //display meta only on our locations (and if its a single location)
        if ($post_type == 'locations' && is_singular('locations')) {

            //collect variables
        $location_id = $post->ID;
        $location_phone = get_post_meta($post->ID , '_lt_location_phone' , true);
        $location_email = get_post_meta($post->ID , '_lt_location_email' , true);
        $location_address = get_post_meta($post->ID , '_lt_location_address' , true);
        $location_country = get_post_meta($post->ID , '_lt_location_country' , true);
        $location_city = get_post_meta($post->ID , '_lt_location_city' , true);
        $location_street = get_post_meta($post->ID , '_lt_location_street' , true); 

            //display
            $html = '';

            $html .= '<section '.apply_filters( "section_two_location", "class='ch-col-12'"). '>';
            $html .= '<div '.apply_filters( "row_two_location", "class='ch-row'"). '>';
            $html .= $content;
            $html .= '</div>';
            $html .= '</section>';


            $html .= '<section '.apply_filters( "section_location", "class='ch-col-12 meta-data'"). '>';

            //hook for outputting additional meta data (at the start of the form)
            do_action('location_meta_data_output_start' , $location_id);

            $html .= '<div '.apply_filters( "before_row_location", "class='ch-col-12'"). '>';
            $html .= '<div '.apply_filters( "row_location", "class='ch-row'"). '>';
            $html .= '<div '.apply_filters( "before_row_location", "class='ch-col-6'"). '>';
            //phone
            if (!empty($location_phone)) {
                $html .= '<img src="' . $this->plugin_url . '/assets/icon/phone2.svg" style="width:20px; height:20px;">'."\t\n".'<b>' . __('Phone:' , 'last-tap-events') . '</b> ' . __($location_phone) . '<br>';
            }
            //email
            if (!empty($location_email)) {
                $html .= '<img src="' . $this->plugin_url . '/assets/icon/mail.svg" style="width:20px; height:20px;">'."\t\n". '<b>' . __('Email:' , 'last-tap-events') . '</b> ' . __($location_email) . '<br>';
            }
            //address
            if (!empty($location_address)) {
                $html .= '<hr><img src="' . $this->plugin_url . '/assets/icon/location.svg" style="width:20px; height:20px;">'."\t\n".'<b class="address">' . __( 'Address:' , 'last-tap-events') . '</b><br>'. __($location_country) . '<br>'. __($location_city). '<br>'. __($location_street). '<br>'. __($location_address) . '<br>';
            }
            $html .= '</div>';
            $html .= '<div class="ch-col-6">';
            //location
            if (!empty(self::$location_hour_days)) {
                $html .= '<p>';
                $html .= '<b>' . apply_filters( 'location_title', __( 'Location Hours' , 'last-tap-events') ). ' </b></br><br>';
                foreach (self::$location_hour_days as $day_key => $day_value) {
                    $cult_hours = get_post_meta($post->ID , '_lt_location_hours_' . $day_key , true);
                    $html .= '<img src="' . $this->plugin_url . '/assets/icon/clock.svg" style="width:20px; height:20px;">'."\t\n".'<span class="day">' . ucfirst(__($day_key)) . ": \t" . '</span><span class="hours">' . $cult_hours . '</span></br>';
                }
                $html .= '</p>';

            }
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';        

            //hook for outputting additional meta data (at the end of the form)
            do_action('location_meta_data_output_end' , $location_id);

            $html .= '</section>';
            

            return $html;


        } else {
            return $content;
        }

    }

    //main function for displaying locations (used for our shortcodes and widgets)
    public function lt_get_locations_output($arguments = "")
    {

        //default args
        $default_args = array(
            'post_type' => 'locations',
            'number_of_locations' => -1 ,
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

        //find locations
        $location_args = array(
            'post_type' => 'locations' ,
            'posts_per_page' => $default_args['number_of_locations'] ,
            'post_status' => 'publish'
        );
        //if we passed in a single location to display
        if (!empty($default_args['location_id'])) {
            $location_args['include'] = $default_args['location_id'];
        }

        //output
        $html = '';
        $locations = get_posts($location_args);
        //if we have locations
        if ($locations) {
            $html .= '<articlelocation_list cf">';
            //foreach location
            foreach ($locations as $location) {


                $html .= '<section class="location">';
                //collect location data
                $location_id = $location->ID;
                $location_title = get_the_title($location_id);
                $location_thumbnail = get_the_post_thumbnail($location_id);
                $location_content = apply_filters('the_content' , $location->post_content);
                if (!empty($location_content)) {
                    $location_content = strip_shortcodes(wp_trim_words($location->post_excerpt , 20 , '...'));
                }
                $location_permalink = get_permalink($location_id);
                $location_phone = get_post_meta($location_id , '_lt_location_phone' , true);
                $location_email = get_post_meta($location_id , '_lt_location_email' , true);
                $location_email = get_post_meta($location_id , '_lt_location_email' , true);
                $location_country = get_post_meta($location_id , '_lt_location_country' , true);

                //apply the filter before our main content starts
                //(lets third parties hook into the HTML output to output data)
                $html = apply_filters('location_before_main_content' , $html);

                //title
                $html .= '<a href="' . esc_url($location_permalink) . '" title="' . esc_attr__('view location' , 'last-tap-events') . '">';

                $html .= '<h2 class="ch-title">';
                $html .= $location_title;
                $html .= '</h2>';
                $html .= '</a>';


                //image & content
                if (!empty($location_thumbnail) || !empty($location_content)) {

                    if (!empty($location_thumbnail)) {
                        $html .= '<p class="ch-col-12">';
                        $html .= $location_thumbnail;
                        $html .= '</p>';
                    }
                    if (!empty($location_content)) {
                        $html .= '<p>';
                        $html .= $location_content;
                        $html .= '</p>';
                    }

                }

                //phone & email output
                if (!empty($location_phone) || !empty($location_email) || !$location_country) {
                    $html .= '<div class="phone_email">';
                    $html .= '<p>';
                    if (!empty($location_country)) {
                        $html .= '<b>' . __('Country' , 'last-tap-events') . ': </b>' . $location_country . '</br>';
                    }
                    if (!empty($location_phone)) {
                        $html .= '<b>' . __('Phone' , 'last-tap-events') . ': </b>' . $location_phone . '</br>';
                    }
                    if (!empty($location_email)) {
                        $html .= '<b>' . __('Email' , 'last-tap-events') . ': </b>' . $location_email;
                    }
                    $html .= '</p>';
                    $html .= '</dv>';
                }

                //apply the filter after the main content, before it ends
                //(lets third parties hook into the HTML output to output data)
                $html = apply_filters('location_after_main_content' , $html);

                //readmore
                $html .= '<a class="link" href="' . esc_url($location_permalink) . '" title="' . esc_attr__('view location' , 'last-tap-events') . '">' . __('View Location' , 'last-tap-events') . '</a>';
                $html .= '</section>';
            
            $html .= '</article>';
            $html .= '<div class="cf"></div>';
        } // end foresch
    }
        

        echo $html;
    }

    //triggered when adding or editing a location
    public function lt_save_location($post_id)
    {

        //check for nonce
        if (!isset($_POST['location_nonce_field'])) {
            return $post_id;
        }
        //verify nonce
        if (!wp_verify_nonce($_POST['location_nonce_field'] , 'location_nonce')) {
            return $post_id;
        }
        //check for autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        //get our phone, email and address fields
        $location_phone = isset($_POST['_lt_location_phone']) ? sanitize_text_field($_POST['_lt_location_phone']) : '';
        $location_email = isset($_POST['_lt_location_email']) ? sanitize_email($_POST['_lt_location_email']) : '';
        $location_address = isset($_POST['_lt_location_address']) ? sanitize_text_field($_POST['_lt_location_address']) : '';
        $location_street = isset($_POST['_lt_location_street']) ? sanitize_text_field($_POST['_lt_location_street']) : '';
        $location_country = isset($_POST['_lt_location_country']) ? sanitize_text_field($_POST['_lt_location_country']) : '';
        $location_city = isset($_POST['_lt_location_city']) ? sanitize_text_field($_POST['_lt_location_city']) : '';
        //update phone, memil and address fields
        update_post_meta($post_id , '_lt_location_phone' , $location_phone);
        update_post_meta($post_id , '_lt_location_email' , $location_email);
        update_post_meta($post_id , '_lt_location_address' , $location_address);
        update_post_meta($post_id , '_lt_location_street' , $location_street);
        update_post_meta($post_id , '_lt_location_country' , $location_country);
        update_post_meta($post_id , '_lt_location_city' , $location_city);

        $sanitize_data = array_map( 'sanitize_text_field', wp_unslash( $_POST ) );

        //search for our cult hour data and update
        foreach ($sanitize_data as $key => $value) {
            //if we found our cult hour data, update it
            if (preg_match('/^_lt_location_hours_/' , $key)) {
                update_post_meta($post_id , $key , $value);
            }
        }

        //location save hook
        //used so you can hook here and save additional post fields added via 'location_meta_data_output_end' or 'location_meta_data_output_end'

        do_action('location_admin_save' , $post_id , $sanitize_data);


    }

}