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
        <a href="?post_type=locations&page=event_location_settings&tab=tab_one"
           class="nav-tab <?php echo $active_tab == 'tab_one' ? 'nav-tab-active' : ''; ?>">GENERAL SETTINGS</a>
        <a href="?post_type=locations&page=event_location_settings&tab=tab_two"
           class="nav-tab <?php echo $active_tab == 'tab_two' ? 'nav-tab-active' : ''; ?>">Shortcode</a>
    </h2>
            <form method="post" action="options.php">
                <?php
                if ($active_tab == 'tab_one') { ?>

                    <?php /** settings manager */ 
       
                }

            if( $active_tab == 'tab_two') {?>


                <h1>Location Shortcode</h1>

            <div class="row">
                <label><h3>location for default</h3>
                    <input style="width: 20%; height: 20px;"  type="text" disabled value="[locations location_id=1]">
                </label>

                <label><h3>Location for namber the post</h3>
                    <input style="width: 35%; height: 20px;"  type="text" disabled  value="[locations location_id=1 number_of_locations=1]">
                </label>

                <p><input style="width: 45%; height: 20px;"  type="text" disabled  value='[locations location_id="1" number_of_locations=1 post_status="publish"]'>
                </p>

            </div>
                <?php 
            }            
            ?>
            </form>
</div>

    <!--    <p> Filter </p>
    <code>location_trading_hours_days</code>
    <code>the_content</code>
    <code>location_before_main_content</code>
    <code>location_after_main_content</code>
    </div> -->