<?php
/**
 * @version 1.0.13
 *
 * @package K7Events/inc/controller
 */

defined('ABSPATH') || exit;

 
class Event_NotificationController extends Event_BaseController
{
    
    private $notification;


    public function __construct(){

        $this->notification = new Event_Country();
    }

    public function ev_register()
    {
        if ( ! $this->ev_activated( 'notify_manager' ) ) return;

        $this->callbacks = new Event_NotificationCallbacks();

        add_action('personal_options_update', array($this,  'ev_save_user_meta_fields'));

        add_action('edit_user_profile_update', array($this, 'ev_save_user_meta_fields'));

        add_action('show_user_profile', array($this, 'ev_show_user_meta_fields'));

        add_action('edit_user_profile', array($this, 'ev_show_user_meta_fields'));

        add_action('add_meta_boxes', array($this,  'ev_add_meta_box'));

        add_action('save_post', array($this, 'ev_save_custom_fields'));

        add_action('publish_post', array($this,  'ev_notify_new_post', 10, 2));

        add_filter( 'post_updated_messages', array( $this, 'ev_updated_messages') );
    }


    /**
     * Show custom user profile fields.
     *
     * @param obj $user the user object
     */
    public function ev_show_user_meta_fields($user)
    {
        ?>
<h3><?php _e('Notification for receiving messages by country', 'k7-event'); ?></h3>

<table class="form-table">

    <tr>
        <th scope="row"><?php _e('Country', 'k7-event'); ?></th>

        <td>
            <label for="country">
                <select name="country">
                    <option value="" <?php selected(get_user_meta($user->ID, 'country', true), ''); ?>>Select
                    </option>
                    <?php foreach ($this->notification->notification_countrys as $key => $value) {
            ?>
                    <option value="<?php echo $key; ?>"
                        <?php selected(esc_attr(get_user_meta($user->ID, 'country', true)), $key); ?>>
                        <?php echo $value; ?></option>
                    <?php
        } ?>
                </select>
                <?php _e('Select country', 'k7-event'); ?>
            </label>
        </td>
    </tr>

    <tr>
        <th scope="row"><?php _e('Notifications', 'k7-event'); ?></th>
        <td>
            <label for="notification">
                <input id="notification" type="checkbox" name="notification" value="true"
                    <?php checked(esc_attr(get_user_meta($user->ID, 'notification', true)), 'true'); ?> />
                <?php _e('Subscribe to email notifications', 'k7-event'); ?>
            </label>
        </td>
    </tr>

    <tr>
        <th scoper="row"><label for="telefono"><?php _e('Phone', 'k7-event'); ?></label></th>
        <td>
            <input type="text" name="phone" id="phone"
                value="<?php echo esc_attr(get_user_meta($user->ID, 'phone', true)); ?>"
                class="regular-text" /><br />
            <span class="description"><?php _e('Phone number', 'k7-event'); ?></span>
        </td>
    </tr>

</table>
<?php
    }

    /**
     * Store data in wp_usermeta table.
     *
     * @param int $user_id the user unique id
     */
    public function ev_save_user_meta_fields($user_id)
    {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }

        if (isset($_POST['country'])) {
            update_user_meta($user_id, 'country', sanitize_text_field($_POST['country']));
        }

        if (!isset($_POST['notification'])) {
            $_POST['notification'] = 'false';
        }

        update_user_meta($user_id, 'notification', sanitize_text_field($_POST['notification']));

        if (isset($_POST['phone'])) {
            update_user_meta($user_id, 'phone', sanitize_text_field($_POST['phone']));
        }
    }

    /*
     * Adds the meta_box
     */
    public function ev_add_meta_box()
    {
        /** possible values: 'post', 'page', 'dashboard', 'link', 'attachment', 'custom_post_type' **/
        
        $screens = array('post', 'locations', 'participant'); 

        foreach ($screens as $screen) {
            add_meta_box(
                'ev_metabox',             // $id - meta_box ID
                __('Venue information', 'k7-event'),      // $title - a title for the meta_box container
                array($this->callbacks, 'ev_meta_box_callback'),   // $callback - the callback which outputs the html for the meta_box
                $screen,                        // $post_type - where to show the meta_box. Possible values: 'post', 'page', 'dashboard', 'link', 'attachment', 'custom_post_type'
                'advanced',                       // $context - possible values: 'normal', 'advanced', 'side'
                'low'                          // $priority - possible values: 'high', 'core', 'default', 'low'
                );
        }
    }

    /*
     * Save the custom field values
     *
     * @param int $post_id The current post id
     */
    public function ev_save_custom_fields($post_id)
    {
        // Check WP nonce
        if (!isset($_POST['ev_meta_box_nonce']) || !wp_verify_nonce($_POST['ev_meta_box_nonce'], 'ev_meta_box')) {
            return;
        }

        // Return if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // check the post_type and set the correspondig capability value
        $capability = (isset($_POST['post_type']) && 'page' == $_POST['post_type']) ? 'edit_page' : 'edit_post';

        // Return if the user lacks the required capability
        if (!current_user_can($capability, $post_id)) {
            return;
        }

        if (!isset($_POST['venue']['disable'])) {
            $_POST['venue']['disable'] = 'false';
        }

        // validate custom field values
        $fields = (isset($_POST['venue'])) ? (array) $_POST['venue'] : array();
        $fields = array_map('sanitize_text_field', $fields);

        foreach ($fields as $key => $value) {
            // store data
            update_post_meta($post_id, $key, $value);
        }
    }

    /*
     * Save the custom field values
     *
     * @param int $post_id The current post id
     */
    public function ev_notify_new_post($post_ID, $post)
    {
        $url = get_permalink($post_ID);

        $country = get_post_meta($post_ID, 'country', true);

        if ('true' == get_post_meta($post_ID, 'disable', true)) {
            return;
        }

        // build the meta query to retrieve subscribers
        $args = array(
            'meta_query' => array(
                    array('key' => 'country', 'value' => $country, 'compare' => '='),
                    array('key' => 'notification', 'value' => 'true', 'compare' => '='),
                ),
            'fields' => array('display_name', 'user_email'),
        );
        // retrieve users to notify the new post
        $users = get_users($args);
        $num = 0;
        foreach ($users as $user) {
            $to = $user->display_name.' <'.$user->user_email.'>';

            $subject = sprintf(__('Hei! We have news for you from %s', 'k7-event'), $this->notification->notification_countrys[$country]);

            $message = sprintf(__('Hi %s!', 'k7-event'), $user->display_name)."\r\n".
        sprintf(__('We have a new post from %s', 'k7-event'), $this->notification->notification_countrys[$country])."\r\n".
        sprintf(__('Read more on %s', 'k7-event'), $url).'.'."\r\n";

           (new Event_EmailController())->ev_send_email($to, $subject, $message);
        }
        // a hidden custom field
        update_post_meta($post_ID, '_notified_users', $num);

        return $post_ID;
    }

    /**
     * Post update messages.
     *
     * See /wp-admin/edit-form-advanced.php
     *
     * @param array $messages existing post update messages
     *
     * @return array amended post update messages with new update messages
     */
    public function ev_updated_messages($msgs)
    {
        $post = get_post();

        $post_type = get_post_type($post);
        $post_type_object = get_post_type_object($post_type);

        $num = get_post_meta($post->ID, '_notified_users', true);

        if ($post_type_object->publicly_queryable) {
            @$msgs[$post_type][6] .= ' - '.$num.__(' notifications sent.', 'k7-event');

        }

        return $msgs;
    }
}
