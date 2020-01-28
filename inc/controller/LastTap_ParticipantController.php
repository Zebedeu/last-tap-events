<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/controller
 * @see LastTap_BaseController
 */


class LastTap_ParticipantController extends LastTap_BaseController
{
    public $settings;

    public $callbacks;

    public function lt_register()
    {
        if (!$this->lt_activated('participant_manager')) return;

    

        $this->settings = new LastTap_SettingsApi();

        $this->callbacks = new LastTap_ParticipantCallbacks();

        add_action('init', array($this, 'lt_participant_cpt'));
        add_action('add_meta_boxes', array($this, 'lt_add_meta_boxes'));
        add_action('save_post', array($this, 'lt_save_meta_box'));
        add_action('manage_participant_posts_columns', array($this, 'lt_set_partici_custom_columns'));
        add_action('manage_participant_posts_custom_column', array($this, 'lt_set_partici_custom_columns_data'), 10, 2);
        add_filter('manage_edit-participant_sortable_columns', array($this, 'lt_set_partici_custom_columns_sortable'));
        add_action('admin_menu', array($this, 'lastTap_count_participant'));
        add_shortcode('particip-form', array($this, 'lt_participant_form'));
        add_shortcode('particip-slideshow', array($this, 'lt_participant_slideshow'));
        add_action('wp_ajax_submit_participant', array($this, 'lt_submit_participant'));
        add_action('wp_ajax_nopriv_submit_participant', array($this, 'lt_submit_participant'));
    }

    public function lt_submit_participant()
    {
        if (!DOING_AJAX || !check_ajax_referer('participant-nonce', 'nonce')) {
            return $this->return_json('error');
        }


        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $telephone = sanitize_text_field($_POST['telephone']);
        $message = sanitize_text_field($_POST['message']);
        $lastTap_title = sanitize_text_field($_POST['lastTap_title']);
        $party = isset($_POST['party']) ? 1 : 0;
        $post_event_id = sanitize_text_field($_POST['post_event_id']);
        $lastTap_user_id = sanitize_text_field($_POST['lastTap_user_id']);
         
        $data = array(
            'lastTap_user_id' => $lastTap_user_id,
            'post_event_id' => $post_event_id,
            'name' => $name,
            'email' => $email,
            'telephone' => $telephone,
            'approved' => 0,
            'party' => $party,
        );


        $args = array(
            'post_title' => $lastTap_title,
            'post_content' => $message,
            'post_author' => 1,
            'post_status' => 'publish',
            'post_type' => 'participant',
            'meta_input' => array(
                '_event_participant_key' => $data
            )
        );

            $event_organizer_email = get_post_meta( $post_event_id, '_event_detall_info', true );


            $to = ' <'.$event_organizer_email['_lt_event_organizer'].'>';

            $subject = sprintf( apply_filters( 'lt_subject_participant', __('Hei! Yeah, I will participate in %s', 'last-tap-events')), $lastTap_title);

            $message = apply_filters( 'lt_message_participant', sprintf(  __('Hi! %s,', 'last-tap-events'), "<br>". $message) );

           (new LastTap_EmailController())->lt_send_email($to, $subject, $message);
            

        $postID = wp_insert_post($args);

        if ($postID) {
            return $this->lt_return_json('success');
        }

        return $this->lt_return_json('error');
    }

    public function lt_return_json($status)
    {
        $return = array(
            'status' => $status
        );
        wp_send_json($return);

        wp_die();
    }

    public function lt_participant_form()
    {

        require_once("$this->plugin_path/templates/participe-form.php");
    }

    public function lt_participant_slideshow()
    {
        require_once("$this->plugin_path/templates/slider.php");
    }

    public function lt_participant_cpt()
    {
        $labels = array(
            'name' => __('Participants','last-tap-events'),
            'singular_name' => __('Participant', 'last-tap-events')
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => false,
            'menu_icon' => 'dashicons-admin-site-alt',
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'supports' => array('thumbnail', 'title', 'editor'),
            'show_in_rest' => false
        );

        register_post_type('participant', $args);
    }

    public function lt_add_meta_boxes()
    {
        add_meta_box(
            'participant_author',
            __( 'participant Options', 'last-tap-events'),
            array($this, 'lt_render_features_box'),
            'participant',
            'side',
            'default'
        );
    }

    public function lt_render_features_box($post)
    {
        wp_nonce_field('event_participant', 'event_participant_nonce');

        $data = get_post_meta($post->ID, '_event_participant_key', true);
        $current_user = wp_get_current_user();

        if ( ! ( $current_user instanceof WP_User ) ) {
                return;
            }
        
              
        $name = $current_user->display_name;
        $email = $current_user->user_email;
        $telephone = isset($data['telephone']) ? $data['telephone'] : '';
        $approved = isset($data['approved']) ? $data['approved'] : false;
        $party = isset($data['party']) ? $data['party'] : false;
        $post_event_id = isset($data['post_event_id']) ? $data['post_event_id'] : false;
        $lastTap_user_id = isset($data['lastTap_user_id']) ? $data['lastTap_user_id'] : false;
        ?>
        <p>

            <?php



            ?>
            <input type="hidden" id="post_event_id" name="post_event_id" class="widefat"
                   value="<?php echo esc_attr($post_event_id); ?>">
            <input type="hidden" id="lastTap_user_id" name="lastTap_user_id" class="widefat"
                   value="<?php echo esc_attr($lastTap_user_id); ?>">
            <label class="meta-label" for="event_participant_author"><?php _e('Author Name', 'last-tap-events'); ?></label>
            <input type="text" id="event_participant_author" name="event_participant_author" class="widefat"
                   value="<?php echo esc_attr($name); ?>">
        </p>
        <p>
            <label class="meta-label" for="event_participant_email"><?php _e('Author Email', 'last-tap-events'); ?></label>
            <input type="email" id="event_participant_email" name="event_participant_email" class="widefat"
                   value="<?php echo esc_attr($email); ?>">
        </p>
        <p>
            <label class="meta-label" for="event_participant_telephone"><?php _e('Author Telephone', 'last-tap-events'); ?></label>
            <input type="text" id="event_participant_telephone" name="event_participant_telephone" class="widefat"
                   value="<?php echo esc_attr($telephone); ?>">
        </p>

        <div class="meta-container">
            <label class="meta-label w-50 text-left"
                   for="event_participant_approved"><?php _e('Approved', 'last-tap-events'); ?></label>
            <div class="text-right w-50 inline">
                <div class="ui-toggle inline"><input type="checkbox" id="event_participant_approved"
                                                     name="event_participant_approved"
                                                     value="1" <?php echo $approved ? 'checked' : ''; ?>>
                    <label for="event_participant_approved">
                        <div></div>
                    </label>
                </div>
            </div>
        </div>
        <div class="meta-container">
            <label class="meta-label w-50 text-left"
                   for="event_participant_party"><?php _e('Partic', 'last-tap-events'); ?></label>
            <div class="text-right w-50 inline">
                <div class="ui-toggle inline"><input type="checkbox" id="event_participant_party"
                                                     name="event_participant_party"
                                                     value="1" <?php echo $party ? 'checked' : ''; ?>>
                    <label for="event_participant_party">
                        <div></div>
                    </label>
                </div>
            </div>
        </div>
        <?php
    }

    public function lt_save_meta_box($post_id)
    {

        if (!isset($_POST['event_participant_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['event_participant_nonce'];
        if (!wp_verify_nonce($nonce, 'event_participant')) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        $data = array(
            'lastTap_user_id' => sanitize_text_field($_POST['lastTap_user_id']),
            'post_event_id' => sanitize_text_field($_POST['post_event_id']),
            'name' => sanitize_text_field($_POST['event_participant_author']),
            'email' => sanitize_email($_POST['event_participant_email']),
            'telephone' => sanitize_text_field($_POST['event_participant_telephone']),
            'approved' => isset($_POST['event_participant_approved']) ? 1 : 0,
            'party' => isset($_POST['event_participant_party']) ? 1 : 0,
        );
        update_post_meta($post_id, '_event_participant_key', $data);
    }

    public function lt_set_partici_custom_columns($columns)
    {
        $date = $columns['date'];
        unset($columns['title'], $columns['date']);

        $columns['name'] = __('Partic Name', 'last-tap-events');
        $columns['title'] = __('Event Name');
        $columns['telephone'] =  __('Telphone', 'last-tap-events');
        $columns['approved'] = __('Approved', 'last-tap-events');
        $columns['party'] = __('Partic', 'last-tap-events');
        $columns['date'] = $date;

        return $columns;
    }

    public function lt_set_partici_custom_columns_data($column, $post_id)
    {
        $data = get_post_meta($post_id, '_event_participant_key', true);
        $name = isset($data['name']) ? $data['name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $telephone = isset($data['telephone']) ? $data['telephone'] : '';
        $approved = isset($data['approved']) && $data['approved'] === 1 ? '<strong>'. __( 'YES', 'last-tap-events').'</strong>' : __(  'NO', 'last-tap-events');
        $party = isset($data['party']) && $data['party'] === 1 ? '<strong>'. __( 'YES', 'last-tap-events').'</strong>' : __(  'NO', 'last-tap-events');


        switch ($column) {
            case 'name':
                echo '<strong>' . $name . '</strong><br/><a href="mailto:' . $email . '">' . $email . '</a>';
                break;

            case 'telephone':
                echo $telephone;
                break;
            case 'approved':
                echo $approved;
                break;

            case 'party':
                echo $party;
                break;
            
        }

    }

    public function lt_set_partici_custom_columns_sortable($columns)
    {
        $columns['name'] = __( 'name', 'last-tap-events');
        $columns['approved'] = __( 'approved', 'last-tap-events');
        $columns['party'] = __( 'partic', 'last-tap-events');

        return $columns;
    }

    /*
    *total de numeros de participantes 
    */
    public function lastTap_count_participant() {
        global $menu;

         // get poty_type participant
        $all_post_ids = get_posts(array(

                'fields'          => 'post_id',
                'posts_per_page'  => -1,
                'post_type' => 'participant'
            ));
        $count_participant = [];

        // get post meta from post type participant
        foreach ($all_post_ids as $k => $v) {
            $count = get_post_meta( $v->ID, '_event_participant_key', false );
                foreach ($count as $key => $value) {
                    if( $value['approved'] == 0){
                        $count_participant[] = $value['post_event_id'];
                    }
                }
        }
                       
        // only display the number of pending posts over a certain amount
        if ( count($count_participant) > 0 ) {
            foreach ( $menu as $key => $value ) {
                if ( $menu[$key][2] == 'edit.php?post_type=participant' ) {
                    $menu[$key][0] .= ' <span class="update-plugins count-2"><span class="update-count">' . count($count_participant) . '</span></span>';
                    return;
                }
            }
        }
    }


     
}