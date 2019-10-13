=== K7 Events ===
Contributors: Márcio Zebedeu
Tags: Form, Register, Locations, Location Widget, Media Widget, Custom Post Type Manager, Custom Taxonomy Manager, Testimonial, Sermon post, Advanced Notification System,  
Requires at least: 4.9
Tested up to: 5.2.3
Requires PHP: 5.6.20
Stable tag: 1.0.20
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

K7 Events is a Wordpress plugin that claims to be simple and objective so you can place event announcements on your site.== Description ==
K7 Events is a Wordpress plugin that claims to be simple and objective so you can place event announcements on your site.

==Also the plugin you can: ==
add events
Add places where there is a Events

There is a Post Type manager where you can create several post type according to your will well with your texamonias.

== FIlters ==

* location_cult_hours_days : With this filter you can create a function that returns an array with new fields for your location meta box with days of the week and hours

* location_cult_title: serves to change the title that appears above the hours of cults

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

add event on a page
[event]


== Frequently Asked Questions ==

== Screenshots ==
1. Post Testimonial
2. slide view of the testimony
3. location post
4. quick view of the location
5. full location view
6. Notify subscribers
7. Location  Layout and front-page
8. Event Post
9. Event List
10. Event Information
11. Limit of participants
12. Form for registration to the event


== Changelog ==

