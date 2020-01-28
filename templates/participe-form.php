<?php
global $event, $wpdb;

$lastTap_postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $event . "'" );
$lastTap_getpost= get_post($lastTap_postid);
$lastTap_title = $lastTap_getpost->post_title;
$lastTap_current_user = wp_get_current_user();

if(! ( $lastTap_current_user instanceof WP_User ) ) {
                return;
  }

$lastTap_name = $lastTap_current_user->display_name;
$lastTap_email = $lastTap_current_user->user_email;
$lastTap_user_id = $lastTap_current_user->ID;


?>

<header class="header">
    <button class="ch-tab" style="background:<?php echo esc_attr( get_option('event_background_color_button_show_form')); ?>; color:<?php echo esc_attr( get_option('event_text_color_button_show_form')); ?>;" onclick="myFunction()"><?php esc_html_e( 'Complete the form to register for this event!', 'last-tap-events' );?></button>
</header>

<form id="event-participant-form" style="display: none;" action="#" method="post" data-url="<?php echo admin_url('admin-ajax.php'); ?>">
    <div class="field-container">
    <input id="lastTap_user_id" class="field-input" type="hidden" name="lastTap_user_id" value="<?php echo $lastTap_user_id;?>">
    </div>

    <div class="field-container">
        <input id="post_event_id" class="field-input" type="hidden" name="post_event_id" value="<?php echo $lastTap_getpost->ID;?>">
    </div>

    <div class="field-container">
        <input id="lastTap_title" class="field-input" type="hidden" name="lastTap_title" value="<?php echo $lastTap_title;?>">
    </div>

    <div class="field-container">
        <input type="text" class="field-input" placeholder="<?php esc_attr_e('Your Name', 'last-tap-events') ?>" id="name"
               name="name" value="<?php echo $lastTap_name ?? "";?>" >
        <small class="field-msg error" data-error="invalidName"><?php _e('Your Name is Required', 'last-tap-events') ?></small>
    </div>

    <div class="field-container">
        <input type="email" class="field-input" placeholder="<?php esc_attr_e('Your Email', 'last-tap-events') ?>" id="email"
               name="email" value="<?php echo $lastTap_email ?? "";?>" >
        <small class="field-msg error"
               data-error="invalidEmail"><?php _e('The Email address is not valid', 'last-tap-events') ?></small>
    </div>
    <div class="field-container">
        <input type="text" class="field-input" placeholder="<?php esc_attr_e('Your Telephone', 'last-tap-events') ?>" id="telephone"
               name="telephone" value="" >
        <small class="field-msg error"
               data-error="invalidTelephone"><?php _e('The Telephone is not valid', 'last-tap-events') ?></small>
    </div>

        <div class="field-container">
        <textarea name="message" id="message" class="field-input"
                  placeholder="<?php esc_attr_e('Your Message', 'last-tap-events') ?>" ></textarea>
        <small class="field-msg error" data-error="invalidMessage"><?php _e('A Message is Required', 'last-tap-events') ?></small>
    </div>

        <div class="meta-container">
            <label class="meta-label w-50 text-left"
                   for="party"><?php _e('I will participate', 'last-tap-events'); ?></label>
            <div class="text-right w-10 inline">
                <div class="ui-toggle inline field-input">
                  <input type="checkbox" id="party" name="party">
                    <label for="party">
                        <div></div>
                       <small class="field-msg error"
               data-error="invalidChecked"><?php _e('The Check is not valid', 'last-tap-events') ?></small>

                    </label>
                </div>
            </div>
        </div>
    <div class="field-container">
        <div>
            <button type="stubmit" class="btn btn-default btn-lg btn-sunset-form"><?php esc_html_e('Submit','last-tap-events');?></button>
        </div>
        <br>
        <small class="field-msg js-form-submission"><?php apply_filters( 'participant_form_submission_process_wait', _e('Submission in process, please wait&hellip;', 'last-tap-events') ) ?></small>
        <br>
        <small class="field-msg success js-form-success"><?php apply_filters( 'participant_form_successfully_submitted_thank', _e('Message Successfully submitted, thank you!<br><h4>Your request is awaiting approval by the organizers.</h4></p>', 'last-tap-events') ); ?></small>
        <small class="field-msg error js-form-error"><?php apply_filters('participant_form_error', _e('There was a problem with the Contact Form, please try again!', 'last-tap-events')); ?></small>

    </div>

    <input type="hidden" name="action" value="submit_participant">
    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce("adv-nonce") ?>">

</form>



<script type="text/javascript">
    function myFunction() {
  var x = document.getElementById("event-participant-form");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
</script>