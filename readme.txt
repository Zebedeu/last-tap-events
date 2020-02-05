=== Last Tap Events ===
Contributors: Márcio Zebedeu, passoniate
Tags: Form, Register, Locations, Location Widget, Media Widget, Custom Post Type Manager, Custom Taxonomy Manager, Testimonial, post, Advanced Notification System,  
Requires at least: 5.2
Tested up to: 5.3.2
Requires PHP: 5.6.20
Stable tag: 1.0.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Last Tap Events is a WordPress plugin that claims to be simple and objective so you can place event announcements on your site.

==Also the plugin you can: ==
add events
Add places where there is a Events

There is a Post Type manager where you can create several post type according to your will well with your taxonomies.

== FIlters ==

* location_event_hours_days : With this filter you can create a function that returns an array with new fields for your location meta box with days of the week and hours

* location_event_title: serves to change the title that appears above the hours of events

* {pot_type}_before_main_content & {post_type}_after_main_content:  This will add extra content before the title of  and  before the button.. 

== Actions ==

* {pot_type}_admin_form_start & {post_type}_admin_form_end : With these filters you can use to add html and css codes to stylize your own meta box

* {pot_type}_meta_data_output_end & {post_type}_meta_data_output_end: allows you to get the data that comes from the post meta. The function receives an ID.

Options like CPT, Taxonomies, Template manager,  Location Manager, Location Widget,  Media Widget,  Testimonial Manager,  Notification, Custom Templates, can be activated and deactivated whenever you want.

== Installation ==

1. Upload the `event_admin` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings » Permalinks, and simply click on Save Changes button.

== Shortcode ==

Testimonial Form Shortcode
[testimonial-form]

Testimonial SlideShow Shortcode
[testimonial-slideshow]

add location on a page: location for default 
[locations location_id=1]

Location for member the post

[locations location_id=1 number_of_locations=1]
[locations location_id="1" number_of_locations=1 post_status="publish"]

Add event on a page
[event]


== Frequently Asked Questions ==

= Can I contribute to this plugin? =
Sure! You are welcome to report any issues or add feature suggestions on the [GitHub repository](https://github.com/Zebedeu/last-tap-events).


== Screenshots ==

2. Event Layout
2. Event Information
3. Limit of participants
4. Form for registration to the event


== Changelog ==
= 1.0.3 =
activate form for participants by default
added function to delete all as plugin options
= 1.0.2 =
fixed several errors
= 1.0.1 =
add notification bubble
fixes bug currency types
