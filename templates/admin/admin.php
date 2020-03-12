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
           class="nav-tab <?php echo $active_tab == 'tab_one' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General Settings','last-tap-events' );?></a>
        <a href="?page=event_plugin&tab=events"
           class="nav-tab <?php echo $active_tab == 'events' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Events','last-tap-events' );?></a>
        <a href="?page=event_plugin&tab=event_color"
           class="nav-tab <?php echo $active_tab == 'event_color' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Color Control','last-tap-events' );?></a>
    </h2>
    <form method="post" action="options.php">
        <?php
        if ($active_tab == 'tab_one') { ?>

            <?php /** settings manager */ ?>

            <div class="wrap">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-1"><?php _e('Manage Settings', 'last-tap-events'); ?></a></li>
                    <li><a href="#tab-2"><?php _e( 'Updates', 'last-tap-events' ); ?></a></li>
                    <li><a href="#tab-3"><?php _e( 'All Available Shortcodes', 'last-tap-events' ); ?></a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                            <?php
                            settings_fields('event_plugin_settings');
                            do_settings_sections('event_plugin');
                            submit_button();
                            ?>

                    </div>

                    <div id="tab-2" class="tab-pane">
                        <h3><?php _e( 'Updates', 'last-tap-events' ); ?></h3>
                        <p>
                            == <?php _e( 'Changelog','last-tap-events' );?> ==<br>


                            == <?php _e( 'Upgrade Notice','last-tap-events' );?> ==
                        </p>
                    </div>

                    <div id="tab-3" class="tab-pane">

                        <P>
                            <?php __( 'Last Tap Events is a WordPress plugin for churches that claims to be simple and objective for your Events website.', 'last-tap-events');?>.
                        </P>
                        <div class='wrap'>
                            <?php
                                global $shortcode_tags;
                            ?>
                            <div id="icon-options-general" class="icon32"><br>
                            </div>
                                <div class="section panel">
                                    <p>T<?php _e( 'This page will display all of the available shortcodes that you can use on your WordPress blog.', 'last-tap-event"' );?></p>
                                        <table class="widefat importers">
                                            <tr><td><strong><?php _e( 'Shortcodes', 'last-tap-event' );?></strong></td></tr>
                                            <?php

                                                foreach($shortcode_tags as $code => $function){
                                                ?>
                                                        <tr><td>[<?php echo $code; ?>]</td></tr>
                                                <?php
                                                    }
                                            ?>

                                        </table>
                                </div><!-- section panel -->
                        </div><!-- wrap -->
                            
                            <h2><?php _e( '3. Go to Settings » Permalinks, and simply click on Save Changes button.', 'last-tap-events' );?></h2></p>
                            <em><?php _e( 'If you like this plugin, please', 'last-tap-events' );?> <a href="http://wordpress.org/extend/plugins/last-tap-event"><?php _e( 'Vote', 'last-tap-events' );?></a>
 .
                                <?php _e( 'Author : ', 'last-tap-events' );?><a href="https://github.com/zebedeu">Márcio Zebedeu</a>
                                <?php _e( 'You can ', 'last-tap-events' );?><a href="https://github.com/knut7/last-tap-event"><?php _e('for bugs', 'last-tap-events')?>, </a> <?php _e('Thank you', 'last-tap-events');?>.</em>

                    </div><!-- tab-3 -->
                </div><!-- tab-content -->
            </div><!-- wrap  -- >


            <?php

        } elseif ($active_tab == 'events') {
            ?>
             <div class="wrap">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-4"><?php _e('Event Settings', 'last-tap-events'); ?></a></li>
                    <li><a href="#tab-5"><?php _e( 'Calendar event', 'last-tap-events' ); ?></a></li>
                    <li><a href="#tab-6"><?php _e( 'All Shortcode', 'last-tap-events' ); ?></a></li>
                </ul>

                <div class="tab-content">
                    <div id="tab-4" class="tab-pane active">
                        <?php
                                    /** settings manager */ 
                            settings_fields( 'event_options_group' );
                            do_settings_sections( 'event_settings' );
                            submit_button( $text = null, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null );
                                    
                        ?>
                    </div>

                    <div id="tab-5" class="tab-pane">
                        <h3><?php _e( 'Calendar', 'last-tap-events' ); ?></h3>
                        <?php $the_posts = get_posts(array('post_type' => 'event'));
                            $data = array();
                        foreach ($the_posts as $key => $value) {
                            $post_id = $value->ID;
                        
                        $title['title'] = $value->post_title;
                        $_event_detall_info = get_post_meta( $post_id, '_event_detall_info',  true );
                        $start['start'] = $_event_detall_info['_lt_start_eventtimestamp']; 
                        $end['end'] = $_event_detall_info['_lt_end_eventtimestamp'];
                        $data[] = array_merge($title, $start, $end);
                        }
                        $my = json_encode($data);
                        $locale = substr( get_locale(), 0, -3);

                        ?>

                            <script>

                                var today = new Date();
                                var dd = String(today.getDate()).padStart(2, '0');
                                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                                var yyyy = today.getFullYear();

                                today = yyyy + '-' + mm + '-' + dd;

                                document.addEventListener('DOMContentLoaded', function() {
                                    
                                    var initialLocaleCode ="<?php  echo $locale;?>";
                                    

                                var calendarEl = document.getElementById('calendar');

                                var calendar = new FullCalendar.Calendar(calendarEl, {
                                  plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
                                  header: {
                                    left: 'prev,next today',
                                    center: 'title',
                                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                                  },
                                  locale: initialLocaleCode,
                                  defaultDate: '2019-08-12',
                                  navLinks: true, // can click day/week names to navigate views
                                  businessHours: true, // display business hours
                                  editable: true,
                                  selectable: true,
                                  selectMirror: true,
                                  select: function(arg) {
                                    var title = prompt('Event Title:');
                                    if(title) {
                                        calendar.addEvent({
                                            title: title,
                                            start: arg.start,
                                            end: arg.end,
                                            allDay: arg.allDay
                                        })
                                    }
                                    calendar.unselect()
                                  },
                                  events: <?php echo $my; ?>
                                });

                                calendar.render();
                              });

                            </script>
                            <style>

                              #calendar {
                                max-width: 900px;
                                margin: 0 auto;
                              }

                            </style>
                              <div id='calendar'></div>

                    </div><!-- tab-5 -->

                    <div id="tab-6" class="tab-pane">

                        <P>
                            <?php __( 'Last Tap Events is a WordPress plugin for churches that claims to be simple and objective for your Events website.', 'last-tap-events' );?>.
                        </P>
                        <div class='wrap'>
                            

                            <p><?php __( 'Testimonial Form Shortcode', 'last-tap-events' );?></p>
                            <code>[testimonial-form]</code>
                            <p><?php __( 'Testimonial SlideShow Shortcode', 'last-tap-events' );?></p><br>
                            <code>[testimonial-slideshow]</code>
                            <p><?php __( 'Location for default', 'last-tap-events' );?></p><br>
                            <code>[locations location_id=1]</code>
                            <p><?php __( 'Location for number the post', 'last-tap-events' );?></p>
                            <code>[locations location_id=1 number_of_locations=1]</code>
                            <code>[locations location_id="1" number_of_locations=1 post_status="publish"]</code>
                            <br>
                            <p>
                            <h4><?php __( '3. Go to Settings » Permalinks, and simply click on Save Changes button.', 'last-tap-events');?></h4></p>
                            <em><?php __('If you like this plugin, please', 'last-tap-events');?> <a href="http://wordpress.org/extend/plugins/last-tap-event"><?php __( 'Vote', 'last-tap-events' );?></a>
                                <?php _e( 'Author : ', 'last-tap-events' );?><a href="https://github.com/zebedeu">Márcio Zebedeu</a>
                                <?php _e( 'You can ', 'last-tap-events' );?><a href="https://github.com/knut7/last-tap-event"><?php _e('for bugs', 'last-tap-events')?>, </a> <?php _e('Thank you', 'last-tap-events');?>.</em>

                        </div><!-- wrap -->
                    </div><!-- tab-6 -->
                </div><!-- tab-content -->
            </div>
           
                <?php 
                  
        }
         elseif ($active_tab == 'event_color') {
                  /** settings manager */ 
                  echo "<br>";

                    settings_fields( 'event_options_group' );
                    do_settings_sections( 'colors' );
                    submit_button( $text = null, $type = 'primary', $name = 'submit', $wrap = true, $other_attributes = null );
        }
        ?>
    </form>

</div>
