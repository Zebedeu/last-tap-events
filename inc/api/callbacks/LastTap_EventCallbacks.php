<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/api/callbacks
 */

defined('ABSPATH') || exit;


class LastTap_EventCallbacks extends LastTap_BaseController

{

       public function lt_event_settings()
    {
    
        return require_once("$this->plugin_path/templates/admin/event.php");
     
    }

    public function lt_event_sanitize_color( $input ){

         $valid_fields = "";
             

            if(!isset($input)) {
                return   $value = esc_attr( get_option( 'event_border_color' ));
            }
            // Validate Background Color
            $background = trim( $input );
            $background = strip_tags( stripslashes( $background ) );
             
            // Check if is a valid hex color
            if( FALSE === $this->check_color( $background ) ) {
             
                // Set the error message
                add_settings_error( 'lt_settings_options', 'lt_bg_error', 'Insert a valid color for Background', 'error' ); // $setting, $code, $message, $type
                 
                // Get the previous valid value
            $value = esc_attr( get_option( 'event_border_color' ));

                $valid_fields = $value;
             
            } else {
             
                $valid_fields = $background;  
             
            }
             
            return apply_filters( 'validate__background_options', $valid_fields, $input);
    }

    public function lt_event_sanitize_background_color( $input )
    {

            $valid_fields = "";
             

            if(!isset($input)) {
                return   $value = esc_attr( get_option( 'event_background_color_button_show_form' ));
            }
            // Validate Background Color
            $background = trim( $input );
            $background = strip_tags( stripslashes( $background ) );
             
            // Check if is a valid hex color
            if( FALSE === $this->check_color( $background ) ) {
             
                // Set the error message
                add_settings_error( 'lt_settings_options', 'lt_bg_error', 'Insert a valid color for Background', 'error' ); // $setting, $code, $message, $type
                 
                // Get the previous valid value
            $value = esc_attr( get_option( 'event_background_color_button_show_form' ));

                $valid_fields = $value;
             
            } else {
             
                $valid_fields = $background;  
             
            }
             
            return apply_filters( 'validate__background_options', $valid_fields, $input);

    }
    public function lt_event_sanitize_text_color( $input )
    {
        $valid_fields = "";
     

            if(!isset($input)) {
                return   $value = esc_attr( get_option( 'event_text_color_button_show_form' ));
            }
        // Validate Background Color
        $background = trim( $input );
        $background = strip_tags( stripslashes( $background ) );
         
        // Check if is a valid hex color
        if( FALSE === $this->check_color( $background ) ) {
         
            // Set the error message
            add_settings_error( 'lt_settings_options', 'lt_bg_error', 'Insert a valid color for Background', 'error' ); // $setting, $code, $message, $type
             
            // Get the previous valid value
            $value = esc_attr( get_option( 'event_text_color_button_show_form' ));

            $valid_fields = $value;
         
        } else {
         
            $valid_fields = $background;  
         
        }
         
        return apply_filters( 'validate_color_options', $valid_fields, $input);

}

public function lt_validate_currency( $input )
{
        $output = get_option('event_currency');

    if( isset( $input ) ){
         $output = sanitize_text_field( $input );
    }
    return $output;

}

/**
 * Function that will check if value is a valid HEX color.
 */
public function check_color( $value ) { 
     
    if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #     
        return true;
    }
     
    return false;
}

    public function lt_event_section()
    {

    }

    public function lt_event_section_color(){

    }

    public function formatDate($date){
        return $newData = date("F j Y H:i", strtotime($date));

    }
    public function lt_sanitize_number($number){
    return preg_replace('/[^0-9]/', '', $number);

    }

    public function lt_event_textFields_border()
    {
    	$value = esc_attr( get_option( 'event_border_color' ) );
        if( !isset( $value)) {
             $value  = $value;
        }else{
            $value = "#FFFFFF";
        }
    	echo '<input type="text" class="ch-color-picker" name="event_border_color" value="' . $value .'" placeholder="eg.#FFFFFF">';
    }

        public function lt_event_textFields_status_started()
    {
    	$value = esc_attr( get_option( 'event_status_started' ) );
        if( !isset( $value)) {
             $value  = $value;
        }else{
            $value = "#FFFFFF";
        }
    	echo '<input type="text" class="regular-text" name="event_status_started" value="' . $value .'" placeholder="eg.#FFFFFF">';
    }



    public function lt_event_textFields_status_soon_finished(){
    	$value = esc_attr( get_option( 'event_status_finished' ) );
        if( !isset( $value)) {
             $value  = $value;
        }else{
            $value = "#FFFFFF";
        }
    	echo '<input type="text" class="regular-text" name="event_status_finished" value="' . $value .'" placeholder="eg.#FFFFFF">';
    }
	public function lt_event_textFields_status_soon(){
		$value = esc_attr( get_option( 'event_status_soon' ) );
        if( !isset( $value)) {
             $value  = $value;
        }else{
            $value = "#FFFFFF";
        }
    	echo '<input type="text" class="regular-text" name="event_status_soon" value="' . $value .'" placeholder="eg.#FFFFFF">';
	}

   public function lt_event_button_class(){
		$value = esc_attr( get_option( 'event_status_button' ) );
        if( !isset( $value)) {
            return $value  = $value;
        }else{
            $value = "#FFFFFF";
        }
    	echo '<input type="text" class="regular-text button primary" name="event_status_button" value="' . $value .'" class="ch-color-picker">';
	}


public function lt_chanche_background_color_button()
{
        $value = esc_attr( get_option( 'event_background_color_button_show_form' ));
        if( !isset( $value)) {
             $value  = $value;
        }else{
            $value = "#cccccc";
        }
 echo '<label>
            <input type="text" name="event_background_color_button_show_form" value="'.$value.'" class="ch-color-picker">
      </label>';
}
public function lt_chanche_text_color_button()
{
        $value = esc_attr( get_option( 'event_text_color_button_show_form' ));
        if( !isset( $value)) {
             $value  = $value;
        }else{
            $value = "#FFFFFF";
        }
 echo '<label>
            <input type="text" name="event_text_color_button_show_form" value="'.$value.'" class="ch-color-picker">
      </label>';
}

 public function lt_currency(){
        $value = esc_attr( get_option( 'event_currency' ) );
        $currency_list = new LastTap_Currency();


        ?>
        <select name="event_currency">
            <option value="" <?php selected( $value, ""); ?>> <?php echo $value; ?> <option>
            <?php foreach (apply_filters( 'event_currency_list', $currency_list->lt_currency_list() ) as $key => $curr) {?>
               <option  value="<?php echo $key;?>"><?php selected( $value, $key );?> <?php echo $curr;?></option>;
            <?php } ?>
        </select>
        <?php
    }


  public function comments($value='')
    {
            global $post;

         if ( 'open' == $post->comment_status ) : ?>
        
        <div id="respond">
        
        <h3><?php comment_form_title(); ?></h3>
        
        <?php cancel_comment_reply_link(); ?>
        
        <?php if ( get_option( 'comment_registration' ) && !$user_ID ) : ?>
        
        <p><?php _e('You must be','last-tap-event');?> <a href="<?php echo wp_login_url( get_permalink() ); ?>"><?php _e('logged in</a> to post a comment.', 'last-tap-event');?></p>
        
        <?php else : ?>
        
        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
        
        <?php if ( $user_ID ) : ?>
        
        <p>Logged in as <a href="<?php echo get_option( 'siteurl' ); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="Log out of this account">Log out &raquo;</a></p>
        
        <?php else : ?>
        
        <p>
        <input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
        <label for="author"><?php _e('Name ','last-tap-event');?><?php if ( $req ) echo "( required )"; ?></label>
        </p>
        
        <p>
        <input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
        <label for="email"><?php _e('Email', 'last-tap-event');?> ( <?php if ( $req ) echo "required, "; ?><?php _e(' never shared', 'last-tap-event' );?></label>
        </p>
        
        <p>
        <input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
        <label for="url"><?php _e('Website','last-tap-event');?></label>
        </p>
        
        <?php endif; ?>
        
        <p><textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea></p>
        
        <p><?php _e('Some HTML is ok', 'last-tap-event');?>: <code><?php echo allowed_tags(); ?></code></p>
        
        <p><input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Submit Comment', 'last-tap-event');?>" /></p>
        <?php do_action( 'comment_form', $post->ID ); comment_id_fields(); ?>
        
        </form>
        
        <?php endif; // If registration required and not logged in ?>
        </div>
        
        <?php endif; // If comments are open: delete this and the sky will fall on your head 
    }

}