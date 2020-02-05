<div class="wrap">
    <h1><?php _e( 'Custom Post Types Manager', 'last-tap-events' ); ?></h1>
    <?php settings_errors(); ?>

    <ul class="nav nav-tabs">
        <li class="<?php echo !isset($_POST["edit_post"]) ? 'active' : '' ?>"><a
                    href="#tab-1"><?php _e( 'Your Custom Post Types', 'last-tap-events' ); ?></a></li>
        <li class="<?php echo isset($_POST["edit_post"]) ? 'active' : '' ?>">
            <a href="#tab-2">
                <?php echo isset($_POST["edit_post"]) ? 'Edit' : 'Add' ?> Custom Post Type
            </a>
        </li>
        <li><a href="#tab-3"><?php _e( 'Export', 'last-tap-events'); ?></a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane <?php echo !isset($_POST["edit_post"]) ? 'active' : '' ?>">

            <h3><?php _e( 'Manage Your Custom Post Types', 'last-tap-events' ); ?></h3>

            <?php
            $options = get_option('event_plugin_cpt') ?: array();

            echo '<table class="cpt-table"><tr><th>' . __( "ID", "last-tap-event" ) . '</th><th>' . __("Singular Name", "last-tap-event") . '</th><th>' . __( "Plural Name", "last-tap-event" ) . '</th><th class="text-center">' . __( "Public", "last-tap-event" ) . '</th><th class="text-center">' . __( "Archive", "last-tap-event" ) . '</th><th class="text-center">' . __( "Actions", "last-tap-event" ) . '</th></tr>';

            foreach ($options as $option) {
                $public = isset($option['public']) ? "TRUE" : "FALSE";
                $archive = isset($option['has_archive']) ? "TRUE" : "FALSE";

                echo "<tr><td>{$option['post_type']}</td><td>{$option['singular_name']}</td><td>{$option['plural_name']}</td><td class=\"text-center\">{$public}</td><td class=\"text-center\">{$archive}</td><td class=\"text-center\">";

                echo '<form method="post" action="" class="inline-block">';
                echo '<input type="hidden" name="edit_post" value="' . $option['post_type'] . '">';
                submit_button( 'Edit', 'Primary small', 'submit', false );
                echo '</form> ';

                echo '<form method="post" action="options.php" class="inline-block">';
                settings_fields('event_plugin_cpt_settings');
                echo '<input type="hidden" name="remove" value="' . $option['post_type'] . '">';
                submit_button( 'Delete', 'Delete small', 'submit', false, array(
                    'onclick' => 'return confirm("' . __( "Are you sure you want to delete this Custom Post Type? The data associated with it will not be deleted.", "last-tap-event" ) . '" );'
                ));
                echo '</form></td></tr>';
            }

            echo '</table>';
            ?>

        </div>

        <div id="tab-2" class="tab-pane <?php echo isset($_POST["edit_post"]) ? 'active' : '' ?>">
            <form method="post" action="options.php">
                <?php
                settings_fields('event_plugin_cpt_settings');
                do_settings_sections('event_cpt');
                submit_button();
                ?>
            </form>
        </div>

        <div id="tab-3" class="tab-pane">
            <h3><?php _e( 'Export Your Custom Post Types', 'last-tap-events' ); ?></h3>

            <?php foreach ($options as $option) { ?>

                <h3><?php echo $option['singular_name']; ?></h3>

                <pre class="prettyprint">

// Register Custom Post Type
function custom_post_type() {

	$labels = array(
		'name'                  => _x( 'Post Types', 'Post Type General Name', 'last-tap-event' ),
		'singular_name'         => _x( '<?php echo $option['singular_name']; ?>', 'Post Type Singular Name', 'last-tap-event' ),
		'menu_name'             => __( '<?php echo $option['plural_name']; ?>', 'last-tap-event' ),
		'plural_name'             => __( '<?php echo $option['plural_name']; ?>', 'last-tap-event' ),
		'name_admin_bar'        => __( 'Post Type', 'last-tap-event' ),
		'archives'              => __( 'Item Archives', 'last-tap-event' ),
		'attributes'            => __( 'Item Attributes', 'last-tap-event' ),
		'parent_item_colon'     => __( 'Parent Item:', 'last-tap-event' ),
		'all_items'             => __( 'All Items', 'last-tap-event' ),
		'add_new_item'          => __( 'Add New Item', 'last-tap-event' ),
		'add_new'               => __( 'Add New', 'last-tap-event' ),
		'new_item'              => __( 'New Item', 'last-tap-event' ),
		'edit_item'             => __( 'Edit Item', 'last-tap-event' ),
		'update_item'           => __( 'Update Item', 'last-tap-event' ),
		'view_item'             => __( 'View Item', 'last-tap-event' ),
		'view_items'            => __( 'View Items', 'last-tap-event' ),
		'searlt_items'          => __( 'Search Item', 'last-tap-event' ),
		'not_found'             => __( 'Not found', 'last-tap-event' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'last-tap-event' ),
		'featured_image'        => __( 'Featured Image', 'last-tap-event' ),
		'set_featured_image'    => __( 'Set featured image', 'last-tap-event' ),
		'remove_featured_image' => __( 'Remove featured image', 'last-tap-event' ),
		'use_featured_image'    => __( 'Use as featured image', 'last-tap-event' ),
		'insert_into_item'      => __( 'Insert into item', 'last-tap-event' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'last-tap-event' ),
		'items_list'            => __( 'Items list', 'last-tap-event' ),
		'items_list_navigation' => __( 'Items list navigation', 'last-tap-event' ),
		'filter_items_list'     => __( 'Filter items list', 'last-tap-event' ),
	);
	$args = array(
		'label'                 => __( 'Post Type', 'last-tap-event' ),
		'description'           => __( 'Post Type Description', 'last-tap-event' ),
		'labels'                => $labels,
		'supports'              => false,
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => <?php echo isset($option['public']) ? "true" : "false"; ?>,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => <?php echo isset($option['has_archive']) ? "true" : "false"; ?>,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( '<?php echo $option['post_type']; ?>', $args );

}
add_action( 'init', 'custom_post_type', 0 );

			</pre>

            <?php } ?>

        </div>
    </div>
</div>