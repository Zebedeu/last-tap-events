<?php
/**
 * @version 1.0.13
 *
 * @package K7Events/inc/controller
 */

defined('ABSPATH') || exit;


class Event_TestimonialController extends Event_BaseController
{
    public $settings;

    public $callbacks;

    public function ev_register()
    {
        if (!$this->ev_activated('testimonial_manager')) return;

        $this->settings = new Event_SettingsApi();

        $this->callbacks = new Event_TestimonialCallbacks();

        add_action('init', array($this, 'ev_testimonial_cpt'));
        add_action('add_meta_boxes', array($this, 'ev_add_meta_boxes'));
        add_action('save_post', array($this, 'ev_save_meta_box'));
        add_action('manage_testimonial_posts_columns', array($this, 'ev_set_custom_columns'));
        add_action('manage_testimonial_posts_custom_column', array($this, 'ev_set_custom_columns_data'), 10, 2);
        add_filter('manage_edit-testimonial_sortable_columns', array($this, 'ev_set_custom_columns_sortable'));

        $this->ev_setShortcodePage();

        add_shortcode('testimonial-form', array($this, 'ev_testimonial_form'));
        add_shortcode('testimonial-slideshow', array($this, 'ev_testimonial_slideshow'));
        add_action('wp_ajax_submit_testimonial', array($this, 'ev_submit_testimonial'));
        add_action('wp_ajax_nopriv_submit_testimonial', array($this, 'ev_submit_testimonial'));
    }

    public function ev_setShortcodePage()
    {
        $subpage = array(
            array(
                'parent_slug' => 'edit.php?post_type=testimonial',
                'page_title' => 'Shortcodes',
                'menu_title' => 'Shortcodes',
                'capability' => 'manage_options',
                'menu_slug' => 'event_testimonial_shortcode',
                'callback' => array($this->callbacks, 'ev_shortcodePage')
            )
        );

        $this->settings->ev_addSubPages($subpage)->ev_register();
    }

    public function ev_submit_testimonial()
    {
        if (!DOING_AJAX || !check_ajax_referer('testimonial-nonce', 'nonce')) {
            return $this->return_json('error');
        }

        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $message = sanitize_textarea_field($_POST['message']);

        $data = array(
            'name' => $name,
            'email' => $email,
            'approved' => 0,
            'featured' => 0,
        );

        $args = array(
            'post_title' => __( 'Testimonial from ' . $name, 'k7-event'),
            'post_content' => $message,
            'post_author' => 1,
            'post_status' => 'publish',
            'post_type' => 'testimonial',
            'meta_input' => array(
                '_event_testimonial_key' => $data
            )
        );

        $postID = wp_insert_post($args);

        if ($postID) {
            return $this->ev_return_json('success');
        }

        return $this->ev_return_json('error');
    }

    public function ev_return_json($status)
    {
        $return = array(
            'status' => $status
        );
        wp_send_json($return);

        wp_die();
    }

    public function ev_testimonial_form()
    {
        ob_start();
        echo "<link rel=\"stylesheet\" href=\"$this->plugin_url/assets/form.css\" type=\"text/css\" media=\"all\" />";
        require_once("$this->plugin_path/templates/contact-form.php");
        echo "<script src=\"$this->plugin_url/assets/form.js\"></script>";
        return ob_get_clean();
    }

    public function ev_testimonial_slideshow()
    {
        ob_start();
        echo "<link rel=\"stylesheet\" href=\"$this->plugin_url/assets/slider.css\" type=\"text/css\" media=\"all\" />";
        require_once("$this->plugin_path/templates/slider.php");
        echo "<script src=\"$this->plugin_url/assets/slider.js\"></script>";
        return ob_get_clean();
    }

    public function ev_testimonial_cpt()
    {
        $labels = array(
            'name' => 'Testimonials',
            'singular_name' => __('Testimonial', 'k7-event')
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => false,
            'menu_icon' => 'dashicons-testimonial',
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'supports' => array('title', 'editor'),
            'show_in_rest' => true
        );

        register_post_type('testimonial', $args);
    }

    public function ev_add_meta_boxes()
    {
        add_meta_box(
            'testimonial_author',
            __( 'Testimonial Options', 'k7-event'),
            array($this, 'ev_render_features_box'),
            'testimonial',
            'side',
            'default'
        );
    }

    public function ev_render_features_box($post)
    {
        wp_nonce_field('event_testimonial', 'event_testimonial_nonce');

        $data = get_post_meta($post->ID, '_event_testimonial_key', true);
        $name = isset($data['name']) ? $data['name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $approved = isset($data['approved']) ? $data['approved'] : false;
        $featured = isset($data['featured']) ? $data['featured'] : false;
        ?>
        <p>
            <label class="meta-label" for="event_testimonial_author"><?php _e('Author Name', 'k7-event'); ?></label>
            <input type="text" id="event_testimonial_author" name="event_testimonial_author" class="widefat"
                   value="<?php echo esc_attr($name); ?>">
        </p>
        <p>
            <label class="meta-label" for="event_testimonial_email"><?php _e('Author Email', 'k7-event'); ?></label>
            <input type="email" id="event_testimonial_email" name="event_testimonial_email" class="widefat"
                   value="<?php echo esc_attr($email); ?>">
        </p>
        <div class="meta-container">
            <label class="meta-label w-50 text-left"
                   for="event_testimonial_approved"><?php _e('Approved', 'k7-event'); ?></label>
            <div class="text-right w-50 inline">
                <div class="ui-toggle inline"><input type="checkbox" id="event_testimonial_approved"
                                                     name="event_testimonial_approved"
                                                     value="1" <?php echo $approved ? 'checked' : ''; ?>>
                    <label for="event_testimonial_approved">
                        <div></div>
                    </label>
                </div>
            </div>
        </div>
        <div class="meta-container">
            <label class="meta-label w-50 text-left"
                   for="event_testimonial_featured"><?php _e('Featured', 'k7-event'); ?></label>
            <div class="text-right w-50 inline">
                <div class="ui-toggle inline"><input type="checkbox" id="event_testimonial_featured"
                                                     name="event_testimonial_featured"
                                                     value="1" <?php echo $featured ? 'checked' : ''; ?>>
                    <label for="event_testimonial_featured">
                        <div></div>
                    </label>
                </div>
            </div>
        </div>
        <?php
    }

    public function ev_save_meta_box($post_id)
    {
        if (!isset($_POST['event_testimonial_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['event_testimonial_nonce'];
        if (!wp_verify_nonce($nonce, 'event_testimonial')) {
            return $post_id;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        $data = array(
            'name' => sanitize_text_field($_POST['event_testimonial_author']),
            'email' => sanitize_email($_POST['event_testimonial_email']),
            'approved' => isset($_POST['event_testimonial_approved']) ? 1 : 0,
            'featured' => isset($_POST['event_testimonial_featured']) ? 1 : 0,
        );
        update_post_meta($post_id, '_event_testimonial_key', $data);
    }

    public function ev_set_custom_columns($columns)
    {
        $title = $columns['title'];
        $date = $columns['date'];
        unset($columns['title'], $columns['date']);

        $columns['name'] = __('Author Name', 'k7-event');
        $columns['title'] = $title;
        $columns['approved'] = __('Approved', 'k7-event');
        $columns['featured'] = __('Featured', 'k7-event');
        $columns['date'] = $date;

        return $columns;
    }

    public function ev_set_custom_columns_data($column, $post_id)
    {
        $data = get_post_meta($post_id, '_event_testimonial_key', true);
        $name = isset($data['name']) ? $data['name'] : '';
        $email = isset($data['email']) ? $data['email'] : '';
        $approved = isset($data['approved']) && $data['approved'] === 1 ? '<strong>'. __( 'YES', 'k7-event').'</strong>' : __(  'NO', 'k7-event');
        $featured = isset($data['featured']) && $data['featured'] === 1 ? '<strong>'. __( 'YES', 'k7-event').'</strong>' : __(  'NO', 'k7-event');

        switch ($column) {
            case 'name':
                echo '<strong>' . $name . '</strong><br/><a href="mailto:' . $email . '">' . $email . '</a>';
                break;

            case 'approved':
                echo $approved;
                break;

            case 'featured':
                echo $featured;
                break;
        }
    }

    public function ev_set_custom_columns_sortable($columns)
    {
        $columns['name'] = __( 'name', 'k7-event');
        $columns['approved'] = __( 'approved', 'k7-event');
        $columns['featured'] = __( 'featured', 'k7-event');

        return $columns;
    }
}