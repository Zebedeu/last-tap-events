<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/api/widgets
 */

defined('ABSPATH') || exit;


class LastTap_LocationWidget extends \WP_Widget
{

    public $widget_ID;

    public $widget_name;

    public $widget_options = array();

    public $control_options = array();

    function __construct()
    {

        $this->widget_ID = 'event_location_widget';
        $this->widget_name = 'Event Location Widget';

        $this->widget_options = array(
            'classname' => $this->widget_ID,
            'description' => $this->widget_name,
            'customize_selective_refresh' => true,
        );

        $this->control_options = array(
            'width' => 400,
            'height' => 350,
        );
    }

    public function lt_register()
    {
        parent::__construct($this->widget_ID, $this->widget_name, $this->widget_options, $this->control_options);
        //initialise widget values

        add_action('widgets_init', array($this, 'lt_register_location_widgets'));
    }
    
    //registers our widget for use
    public function lt_register_location_widgets()
    {
        register_widget($this);
    }



    //handles the back-end admin of the widget
    //$instance - saved values for the form
    public function form($instance)
    {
        //collect variables
        $location_id = (isset($instance['location_id']) ? $instance['location_id'] : 'default');
        $number_of_locations = (isset($instance['number_of_locations']) ? $instance['number_of_locations'] : 5);

        ?>
        <p><?php _e( 'Select your options below', 'last-tap-events');?></p>
        <p>
            <label for="<?php echo $this->get_field_name('location_id'); ?>"><?php _e('Location to display', 'last-tap-events'); ?></label>
            <select class="widefat" name="<?php echo $this->get_field_name('location_id'); ?>"
                    id="<?php echo $this->get_field_id('location_id'); ?>" value="<?php echo $location_id; ?>">
                <option value="default"><?php _e('All Locations', ' last-tap-events'); ?></option>
                <?php
                $args = array(
                    'posts_per_page' => -1,
                    'post_type' => 'locations'
                );
                $locations = get_posts($args);
                if ($locations) {
                    foreach ($locations as $location) {
                        if ($location->ID == $location_id) {
                            echo '<option selected value="' . $location->ID . '">' . get_the_title($location->ID) . '</option>';
                        } else {
                            echo '<option value="' . $location->ID . '">' . get_the_title($location->ID) . '</option>';
                        }
                    }
                }
                ?>
            </select>
        </p>
        <p>
            <small><?php _e('If you want to display multiple locations select how many below', 'last-tap-events'); ?></small>
            <br/>
            <label for="<?php echo $this->get_field_id('number_of_locations'); ?>"><?php _e('Number of Locations', 'last-tap-events'); ?></label>
            <select class="widefat" name="<?php echo $this->get_field_name('number_of_locations'); ?>"
                    id="<?php echo $this->get_field_id('number_of_locations'); ?>"
                    value="<?php echo $number_of_locations; ?>">
                <option value="default" <?php if ($number_of_locations == 'default') {
                    echo 'selected';
                } ?>><?php _e('All Locations', 'last-tap-events'); ?>
                </option>
                <option value="1" <?php if ($number_of_locations == '1') {
                    echo 'selected';
                } ?>>1
                </option>
                <option value="2" <?php if ($number_of_locations == '2') {
                    echo 'selected';
                } ?>>2
                </option>
                <option value="3" <?php if ($number_of_locations == '3') {
                    echo 'selected';
                } ?>>3
                </option>
                <option value="4" <?php if ($number_of_locations == '4') {
                    echo 'selected';
                } ?>>4
                </option>
                <option value="5" <?php if ($number_of_locations == '5') {
                    echo 'selected';
                } ?>>5
                </option>
                <option value="10" <?php if ($number_of_locations == '10') {
                    echo 'selected';
                } ?>>10
                </option>
            </select>
        </p>
        <?php
    }

    //handles updating the widget
//$new_instance - new values, $old_instance - old saved values
    public function update($new_instance, $old_instance)
    {

        $instance = array();

        $instance['location_id'] = $new_instance['location_id'];
        $instance['number_of_locations'] = $new_instance['number_of_locations'];

        return $instance;
    }

    //handles public display of the widget
//$args - arguments set by the widget area, $instance - saved values
    public function widget($args, $instance)
    {
        // $tot = absint( $instance[ 'location_id' ] );

        // var_dump($tot); die;
        //pass any arguments if we have any from the widget
        $arguments = array();
        $simple_locations = new LastTap_LocationController();
        //if we specify a location

        //if we specify a single location
        if ( $instance['location_id'] != 'default' ) {
            $arguments['location_id'] = $instance['location_id'];
        }
        //if we specify a number of locations
        if ( $instance['number_of_locations'] != 'default') {
            $arguments['number_of_locations'] = $instance['number_of_locations'];
        }

        //get the output
        $html = '';

        $html .= $args['before_widget'];
        $html .= $args['before_title'];
        $html .= $args['after_title'];

        //uses the main output function of the location class
        $html .= $simple_locations->lt_get_locations_output($arguments);
        $html .= $args['after_widget'];

        echo $html;
    }

}