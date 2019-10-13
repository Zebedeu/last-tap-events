<?php

/**
 * Trigger this file on Plugin uninstall
 *
 * @package  K7Church
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// Clear Database stored data
$books = get_posts(array('post_type' => 'event','post_type' => 'locations','post_type' => 'sermon', 'numberposts' => -1));

foreach ($books as $book) {
    wp_delete_post($book->ID, true);
}

// Access the database via SQL
global $wpdb;
$wpdb->query("DELETE FROM wp_posts WHERE post_type = 'event'");
$wpdb->query("DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)");
$wpdb->query("DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)");

remove_role( 'event_role' );