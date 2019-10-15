<?php
global $event, $wpdb;

$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $event . "'" );
$getpost= get_post($postid);

?>

<header class="header">
    <button class="ch-tab" style="background:<?php echo esc_attr( get_option('event_background_color_button_show_form')); ?>; color:<?php echo esc_attr( get_option('event_text_color_button_show_form')); ?>;" onclick="myFunction()"><?php esc_html_e( 'Complete the form to register for this event!', 'k7-event' );?></button>
</header>

<form id="event-participant-form" style="display: none;" action="#" method="post" data-url="<?php echo admin_url('admin-ajax.php'); ?>">

    <div class="field-container">
        <input id="post_event_id" class="field-input" type="hidden" name="post_event_id" value="<?php echo $getpost->ID;?>">
    </div>

    <div class="field-container">
        <input type="text" class="field-input" placeholder="<?php esc_attr_e('Your Name', 'k7-event') ?>" id="name"
               name="name" >
        <small class="field-msg error" data-error="invalidName"><?php _e('Your Name is Required', 'k7-event') ?></small>
    </div>

    <div class="field-container">
        <input type="email" class="field-input" placeholder="<?php esc_attr_e('Your Email', 'k7-event') ?>" id="email"
               name="email" >
        <small class="field-msg error"
               data-error="invalidEmail"><?php _e('The Email address is not valid', 'k7-event') ?></small>
    </div>
    <div class="field-container">
        <input type="text" class="field-input" placeholder="<?php esc_attr_e('Your Telephone', 'k7-event') ?>" id="telephone"
               name="telephone" >
        <small class="field-msg error"
               data-error="invalidTelephone"><?php _e('The Telephone is not valid', 'k7-event') ?></small>
    </div>

        <div class="field-container">
        <textarea name="message" id="message" class="field-input"
                  placeholder="<?php esc_attr_e('Your Message', 'k7-event') ?>" ></textarea>
        <small class="field-msg error" data-error="invalidMessage"><?php _e('A Message is Required', 'k7-event') ?></small>
    </div>

        <div class="meta-container">
            <label class="meta-label w-50 text-left"
                   for="party"><?php _e('I will participate', 'k7-event'); ?></label>
            <div class="text-right w-10 inline">
                <div class="ui-toggle inline field-input">
                  <input type="checkbox" id="party" name="party">
                    <label for="party">
                        <div></div>
                       <small class="field-msg error"
               data-error="invalidChecked"><?php _e('The Check is not valid', 'k7-event') ?></small>

                    </label>
                </div>
            </div>
        </div>
    <div class="field-container">
        <div>
            <button type="stubmit" class="btn btn-default btn-lg btn-sunset-form"><?php esc_html_e('Submit','k7-event');?></button>
        </div>
        <br>
        <small class="field-msg js-form-submission"><?php apply_filters( 'participant_form_submission_process_wait', _e('Submission in process, please wait&hellip;', 'k7-event') ) ?></small>
        <br>
        <small class="field-msg success js-form-success"><?php apply_filters( 'participant_form_successfully_submitted_thank', _e('Message Successfully submitted, thank you!<br><h4>Your request is awaiting approval by the organizers.</h4></p>', 'k7-event') ); ?></small>
        <small class="field-msg error js-form-error"><?php apply_filters('participant_form_error', _e('There was a problem with the Contact Form, please try again!', 'k7-event')); ?></small>

    </div>

    <input type="hidden" name="action" value="submit_participant">
    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce("participant-nonce") ?>">

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