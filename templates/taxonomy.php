<div class="wrap">
    <h1><?php _e('Taxonomy Manager', 'last-tap-events'); ?></h1>
    <?php settings_errors(); ?>

    <ul class="nav nav-tabs">
        <li class="<?php echo !isset($_POST["edit_taxonomy"]) ? 'active' : '' ?>"><a
                    href="#tab-1"><?php _e('Your Taxonomies', 'last-tap-events'); ?></a></li>
        <li class="<?php echo isset($_POST["edit_taxonomy"]) ? 'active' : '' ?>">
            <a href="#tab-2">
                <?php echo isset($_POST["edit_taxonomy"]) ? __('Edit', 'last-tap-events') : _e('Add Taxonomy', 'last-tap-events'); ?>
            </a>
        </li>
        <li><a href="#tab-3"><?php _e('Export', 'last-tap-events'); ?></a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane <?php echo !isset($_POST["edit_taxonomy"]) ? 'active' : '' ?>">

            <h3><?php _e('Manage Your Custom Taxonomies', 'last-tap-events'); ?></h3>

            <?php
            $options = get_option('event_plugin_tax') ?: array();

            echo '<table class="cpt-table"><tr><th>' . __('ID', 'last-tap-events') . '</th><th>' . __('Singular Name', 'last-tap-events') . '</th><th class="text-center">' . __('Hierarchical', 'last-tap-events') . '</th><th class="text-center">' . __( 'Actions', 'last-tap-events') . '</th></tr>';

            foreach ( $options as $option ) {
                $hierarchical = isset($option['hierarchical']) ? "TRUE" : "FALSE";

                echo "<tr><td>{$option['taxonomy']}</td><td>{$option['singular_name']}</td><td class=\"text-center\">{$hierarchical}</td><td class=\"text-center\">";

                echo '<form method="post" action="" class="inline-block">';
                echo '<input type="hidden" name="edit_taxonomy" value="' . $option['taxonomy'] . '">';
                submit_button('Edit', 'Primary small', 'submit', false);
                echo '</form> ';

                echo '<form method="post" action="options.php" class="inline-block">';
                settings_fields( 'event_plugin_tax_settings' );
                echo '<input type="hidden" name="remove" value="' . $option['taxonomy'] . '">';
                submit_button( 'Delete', 'delete small', 'submit', false, array(
                    'onclick' => 'return confirm("' . __( "Are you sure you want to delete this Custom Taxonomy? The data associated with it will not be deleted", "last-tap-event" ) . '" );'
                ));
                echo '</form></td></tr>';
            }

            echo '</table>';
            ?>

        </div>

        <div id="tab-2" class="tab-pane <?php echo isset( $_POST["edit_taxonomy"]) ? 'active' : '' ?>">
            <form method="post" action="options.php">
                <?php
                settings_fields( 'event_plugin_tax_settings' );
                do_settings_sections( 'event_taxonomy' );
                submit_button();
                ?>
            </form>
        </div>

        <div id="tab-3" class="tab-pane">
            <h3><?php _e( 'Export Your Taxonomies', 'last-tap-events' ); ?></h3>

        </div>
    </div>
</div>