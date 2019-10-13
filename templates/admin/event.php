<div class="wrap">
    <?php settings_errors(); ?>

    <?php
    if (isset($_GET['tab'])) {
        $active_tab = $_GET['tab'];
    } else {
        $active_tab = 'tab_one';
    }
    ?>

    <h2 class="nav-tab-wrapper">
        <a href="?post_type=event&page=event_settings&tab=tab_one"
           class="nav-tab <?php echo $active_tab == 'tab_one' ? 'nav-tab-active' : ''; ?>"><?php _e( 'GENERAL SETTINGS', 'k7-event');?></a>
        <a href="?post_type=event&page=event_settings&tab=tab_two"
           class="nav-tab <?php echo $active_tab == 'tab_two' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Shortcode', 'k7-event');?></a>
    </h2>
            <form method="post" action="options.php">
                <?php
                if ($active_tab == 'tab_one') { ?>

                    <?php /** settings manager */ 

                    settings_fields( 'event_options_group' );
                    do_settings_sections( 'event_settings' );
                    submit_button( $text = null, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null );
       
                }

            if( $active_tab == 'tab_two') {?>


                <h1><?php _e( 'Event Shortcode', 'k7-event');?></h1>

            <div class="row">
                <label><h3><?php _e( 'event for default', 'k7-event');?></h3>
                    <input style="width: 20%; height: 20px;"  type="text" disabled value="[events event_id=1]">
                </label>

                <label><h3><?php _e( 'Event for namber the post', 'k7-event');?></h3>
                    <input style="width: 35%; height: 20px;"  type="text" disabled  value="[events event_id=1 number_of_events=1]">
                </label>

                <p><input style="width: 45%; height: 20px;"  type="text" disabled  value='[events event_id="1" number_of_events=1 post_status="publish"]'>
                </p>

            </div>
                <?php 
            }            
            ?>
            </form>
</div>