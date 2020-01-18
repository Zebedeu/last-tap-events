<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/api/callbacks
 */

defined('ABSPATH') || exit;


class LastTap_AdminCallbacks extends LastTap_BaseController
{

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
        
        echo '<input type="text" class="ch-color-picker" name="event_border_color" value="' . $value .'" placeholder="eg.#FFFFFF">';
    }

        public function lt_event_textFields_status_started()
    {
        $value = esc_attr( get_option( 'event_status_started' ) );
        
        echo '<input type="text" class="regular-text" name="event_status_started" value="' . $value .'" placeholder="eg.#FFFFFF">';
    }



    public function lt_event_textFields_status_soon_finished(){
        $value = esc_attr( get_option( 'event_status_finished' ) );
        
        echo '<input type="text" class="regular-text" name="event_status_finished" value="' . $value .'" placeholder="eg.#FFFFFF">';
    }
    public function lt_event_textFields_status_soon(){
        $value = esc_attr( get_option( 'event_status_soon' ) );
        
        echo '<input type="text" class="regular-text" name="event_status_soon" value="' . $value .'" placeholder="eg.#FFFFFF">';
    }

   public function lt_event_button_class(){
        $value = esc_attr( get_option( 'event_status_button' ) );
        
        echo '<input type="text" class="regular-text button primary" name="event_status_button" value="' . $value .'" class="ch-color-picker">';
    }


public function lt_chanche_background_color_button()
{
        $value = esc_attr( get_option( 'event_background_color_button_show_form' ));
        
 echo '<label>
            <input type="text" name="event_background_color_button_show_form" value="'.$value.'" class="ch-color-picker">
      </label>';
}
public function lt_chanche_text_color_button()
{
        $value = esc_attr( get_option( 'event_text_color_button_show_form' ));
        
 echo '<label>
            <input type="text" name="event_text_color_button_show_form" value="'.$value.'" class="ch-color-picker">
      </label>';
}

 public function lt_currency(){
        $value = esc_attr( get_option( 'event_currency' ) );
        $currency_list = new LastTap_Currency();

        ?>
        <select name="event_currency">
            <option value="event_currency" <?php selected( $value, ""); ?>> <?php echo $value; ?> <option>
            <?php foreach (apply_filters( 'event_currency_list', $currency_list->lt_currency_list() ) as $key => $curr) {?>
               <option  value="<?php echo $key;?>"><?php selected( $value, $key );?> <?php echo $curr;?></option>;
            <?php } ?>
        </select>
        <?php
    }

    public function lt_adminDashboard()
    {
        return require_once("$this->plugin_path/templates/admin/admin.php");
    }

    public function lt_adminCpt()
    {
        return require_once("$this->plugin_path/templates/cpt.php");
    }

    public function lt_adminTaxonomy()
    {
        return require_once("$this->plugin_path/templates/taxonomy.php");
    }

    public function lt_adminWidget()
    {
        return require_once("$this->plugin_path/templates/widget.php");
    }

    public function lt_adminGallery()
    {
        echo "<h1>Gallery Manager</h1>";
    }

    public function lt_adminTestimonial()
    {
        echo "<h1>Testimonial Manager</h1>";
    }

    public function lt_adminTemplates()
    {
        echo "<h1>Templates Manager</h1>";
    }

    public function lt_adminAuth()
    {
        echo "<h1>Templates Manager</h1>";
    }

    public function lt_adminMembership()
    {
        echo "<h1>Membership Manager</h1>";
    }

    public function painel()
    {
        return require_once("$this->plugin_path/templates/panel.php");
    }
}