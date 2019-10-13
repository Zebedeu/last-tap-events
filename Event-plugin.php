<?php
/**
 * @version 1.0.16
 *
 * @package K7 Events
 */

/**
 *  Plugin Name: K7 Events
 *  Description:  K7 Events is a Wordpress plugin for churches that claims to be simple and objective for your church's website.
 *  Version:      1.0.0
 *  Author:       Márcio Zebedeu
 *  Author URI:    https://profiles.wordpress.org/marcio-zebedeu
 *  License:      GPL2
 *  License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 *  Text Domain:  k7-event
 *  Domain Path: /languages
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

// If this file is called firectly, abort!!!
defined('ABSPATH') or die('Hey, what are you doing here? You silly human!');

require_once(dirname(__FILE__) . '/inc/Event_Init.php');
require_once(dirname(__FILE__) . '/inc/controller/Event_Activate.php');
require_once(dirname(__FILE__) . '/inc/controller/Event_Deactivate.php');
include_once(dirname(__FILE__) . '/inc/controller/Event_BaseController.php');
require_once(dirname(__FILE__) . '/inc/api/Event_SettingsApi.php');
require_once(dirname(__FILE__) . '/inc/pages/Event_Dashboard.php');
require_once(dirname(__FILE__) . '/inc/api/WP_API_Client.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Event_AdminCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/Event_Currency.php');
require_once(dirname(__FILE__) . '/inc/api/Event_Country.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Event_EventCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Event_ManagerCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Event_LocationCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Event_NotificationCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Event_TestimonialCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Event_CptCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Event_TaxonomyCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/Event_ParticipantCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/widgets/Event_MediaWidget.php');
require_once(dirname(__FILE__) . '/inc/api/widgets/Event_LocationWidget.php');
require_once(dirname(__FILE__) . '/inc/controller/Event_Enqueue.php');
require_once(dirname(__FILE__) . '/inc/controller/Event_EmailController.php');
require_once(dirname(__FILE__) . '/inc/controller/Event_SettingsLinks.php');
require_once(dirname(__FILE__) . '/inc/controller/Event_CustomPostTypeController.php');
require_once(dirname(__FILE__) . '/inc/controller/Event_CustomTaxonomyController.php');
require_once(dirname(__FILE__) . '/inc/controller/Event_CustomTaxonomyController.php');
require_once(dirname(__FILE__) . '/inc/controller/Event_TestimonialController.php');
require_once(dirname(__FILE__) . '/inc/controller/Event_TemplateController.php');
require_once(dirname(__FILE__) . '/inc/controller/Event_LocationController.php');
require_once(dirname(__FILE__) . '/inc/controller/Event_NotificationController.php');
require_once(dirname(__FILE__) . '/inc/controller/Event_WidgetController.php');
require_once(dirname(__FILE__) . '/inc/controller/Event_EventController.php');
require_once(dirname(__FILE__) . '/inc/controller/Event_ParticipantController.php');  


/**
 * The code that runs during plugin activation
 */
function activate_event_plugin()
{
    Event_Activate::ev_activate();
}

register_activation_hook(__FILE__, 'activate_event_plugin');

/**
 * The code that runs during plugin deactivation
 */
function deactivate_event_plugin()
{
    Event_Deactivate::ev_deactivate();
}

register_deactivation_hook(__FILE__, 'deactivate_event_plugin');

/**
 * Initialize all the core classes of the plugin
 */
if (class_exists('Event_Init')) {


    Event_Init::ev_registerServices();

}