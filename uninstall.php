<?php

/**
 * Trigger this file on Plugin uninstall
 *
 * @package  LastTapEvents
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// Clear Database stored data
$books = get_posts(array('post_type' => 'event','post_type' => 'locations','post_type' => 'participant', 'numberposts' => -1));

foreach ($books as $book) {
    wp_delete_post($book->ID, true);
}

 
function event_delete_all_option(){
	
	$all_options = wp_load_alloptions();
	$my_options  = '';

	foreach ( $all_options as $name => $value ) {
    	if ( stristr( $name, 'event' ) ) {
        	$my_options =  $name;
        	delete_option( $my_options );
    	}
	}
}
 
event_delete_all_option();
// Access the database via SQL
global $wpdb;
$wpdb->query("DELETE FROM wp_posts WHERE post_type = 'event'");
$wpdb->query("DELETE FROM wp_posts WHERE post_type = 'participant'");
$wpdb->query("DELETE FROM wp_posts WHERE post_type = 'locations'");
$wpdb->query("DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)");
$wpdb->query("DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)");

remove_role( 'event_role' );