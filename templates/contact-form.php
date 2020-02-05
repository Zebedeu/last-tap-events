<form id="church-testimonial-form" action="#" method="post" data-url="<?php echo admin_url('admin-ajax.php'); ?>">

    <div class="field-container">
        <input type="text" class="field-input" placeholder="<?php esc_attr_e( 'Your Name', 'last-tap-events' ) ?>" id="name"
               name="name" required>
        <small class="field-msg error" data-error="invalidName"><?php _e( 'Your Name is Required', 'last-tap-events' ) ?></small>
    </div>

    <div class="field-container">
        <input type="email" class="field-input" placeholder="<?php esc_attr_e( 'Your Email', 'last-tap-events' ) ?>" id="email"
               name="email" required>
        <small class="field-msg error"
               data-error="invalidEmail"><?php _e( 'The Email address is not valid', 'last-tap-events' ) ?></small>
    </div>

    <div class="field-container">
        <textarea name="message" id="message" class="field-input"
                  placeholder="<?php esc_attr_e( 'Your Message', 'last-tap-events' ) ?>" required></textarea>
        <small class="field-msg error" data-error="invalidMessage"><?php _e( 'A Message is Required', 'last-tap-events' ) ?></small>
    </div>

    <div class="field-container">
        <div>
            <button type="stubmit" class="btn btn-default btn-lg btn-sunset-form"><?php _e('Submit', 'last-tap-events');?></button>
        </div>
        <small class="field-msg js-form-submission"><?php _e( 'Submission in Process, please wait&hellip;', 'last-tap-events' ) ?></small>
        <small class="field-msg success js-form-success"><?php _e( 'Your Message Successfully submitted, Thank you!', 'last-tap-events' ) ?></small>
        <small class="field-msg error js-form-error"><?php _e( 'There was a problem with the Contact Form, please try again!', 'last-tap-events' ) ?></small>
    </div>

    <input type="hidden" name="action" value="submit_testimonial">
    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce("testimonial-nonce") ?>">

</form>