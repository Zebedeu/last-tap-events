<form id="church-auth-form" action="#" method="post" data-url="<?php echo admin_url('admin-ajax.php'); ?>">
    <div class="auth-btn">
        <input class="submit_button" type="button" value="<?php esc_attr_e( 'Login', 'last-tap-events' ); ?>"
               id="church-show-auth-form">
    </div>
    <div id="church-auth-container" class="auth-container">
        <a id="church-auth-close" class="close" href="#"><?php _e( '&times;', 'last-tap-events' ); ?></a>
        <h2><?php _e('Site Login', 'last-tap-events'); ?></h2>
        <label for="username"><?php _e( 'Username', 'last-tap-events' ); ?></label>
        <input id="username" type="text" name="username">
        <label for="password"><?php _e( 'Password', 'last-tap-events' ); ?></label>
        <input id="password" type="password" name="password">
        <input type="hidden" name="pippin_login_nonce" value="<?php echo wp_create_nonce('pippin-login-nonce'); ?>"/>

        <input class="submit_button" type="submit" value="<?php esc_attr_e( 'Login', 'last-tap-events' ); ?>" name="submit">
        <p class="status" data-message="status">Satus the messages</p>

        <p class="actions">
            <a href="<?php echo wp_lostpassword_url(); ?>"><?php _e( 'Forgot Password?', 'last-tap-events' ); ?></a> - <a
                    href="<?php echo wp_registration_url(); ?>"><?php _e( 'Register', 'last-tap-events' ); ?></a>
        </p>

        <input type="hidden" name="action" value="event_login">

    </div>
</form>