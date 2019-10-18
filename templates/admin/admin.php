<div class="wrap">
    <?php settings_errors(); ?>

    <?php
    if (isset($_GET['tab'])) {
        $active_tab = $_GET['tab'];
    }
        elseif (isset($_GET['events'])) {
        $active_tab = $_GET['events'];
    }
        elseif (isset($_GET['event_color'])) {
        $active_tab = $_GET['event_color'];
    }
     else {
        $active_tab = 'tab_one';
    }
    ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=event_plugin&tab=tab_one"
           class="nav-tab <?php echo $active_tab == 'tab_one' ? 'nav-tab-active' : ''; ?>">GENERAL SETTINGS</a>
        <a href="?page=event_plugin&tab=events"
           class="nav-tab <?php echo $active_tab == 'events' ? 'nav-tab-active' : ''; ?>">Events</a>
        <a href="?page=event_plugin&tab=event_color"
           class="nav-tab <?php echo $active_tab == 'event_color' ? 'nav-tab-active' : ''; ?>">Color Control</a>
    </h2>
    <form method="post" action="options.php">
        <?php
        if ($active_tab == 'tab_one') { ?>

            <?php /** settings manager */ ?>

            <div class="wrap">


                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-1"><?php _e('Manage Settings', 'last-tap-event'); ?></a></li>
                    <li><a href="#tab-2"><?php _e('Updates', 'last-tap-event'); ?></a></li>
                    <li><a href="#tab-3"><?php _e('About', 'last-tap-event'); ?></a></li>
                </ul>

                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <form method="post" action="options.php">

                            <?php
                            settings_fields('event_plugin_settings');
                            do_settings_sections('event_plugin');
                            submit_button();
                            ?>
                        </form>

                    </div>

                    <div id="tab-2" class="tab-pane">
                        <h3><?php _e('Updates', 'last-tap-event'); ?></h3>
                        <p>
                            <code>
                                == <?php __('Changelog','last-tap-event');?> ==


                                == <?php __('Upgrade Notice','last-tap-event');?> ==
                                no
                            </code>
                        </p>
                    </div>

                    <div id="tab-3" class="tab-pane">

                        <P>
                            <?php __('K7 Church is a Wordpress plugin for churches that claims to be simple and objective for your
                            church website ', 'last-tap-event');?>.
                        </P>
                        <div class='wrap'>
                            

                            <p><?php __('Testimonial Form Shortcode', 'last-tap-event');?></p>
                            <code>[testimonial-form]</code>
                            <p><?php __('Testimonial SlideShow Shortcode', 'last-tap-event');?></p><br>
                            <code>[testimonial-slideshow]</code>
                            <p><?php __('location for defaul ', 'last-tap-event');?></p><br>
                            <code>[locations location_id=1]</code>
                            <p><?php __('Location for namber the post', 'last-tap-event');?></p>
                            <code>[locations location_id=1 number_of_locations=1]</code>
                            <code>[locations location_id="1" number_of_locations=1 post_status="publish"]</code>
                            <br>
                            <p>
                            <h2><?php __('3. Go to Settings » Permalinks, and simply click on Save Changes button.', 'last-tap-event');?></h2></p>
                            <em><?php __('If you like this plugin, please', 'last-tap-event');?> <a href="http://wordpress.org/extend/plugins/last-tap-event"><?php __('vote', 'last-tap-event');?></a>
                                .
                                <?php __('Author : ', 'last-tap-event');?><a href="https://github.com/zebedeu">Máecio Zebedeu</a>
                                <?php __('You can ', 'last-tap-event');?><a href="https://github.com/knut7/last-tap-event"><?php __('for bugs, </a> thanks.</em>','last-tap-event');?>

                        </div>
                    </div>
                </div>
            </div>

            <?php

        } elseif ($active_tab == 'events') {
                  /** settings manager */ 

                    settings_fields( 'event_options_group' );
                    do_settings_sections( 'event_settings' );
                    submit_button( $text = null, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null );
        }
         elseif ($active_tab == 'event_color') {
                  /** settings manager */ 

                    settings_fields( 'event_options_group' );
                    do_settings_sections( 'colors' );
                    submit_button( $text = null, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null );
        }
        ?>
    </form>

</div>
