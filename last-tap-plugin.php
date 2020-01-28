<?php
/**
 * @version 1.0
 * @package LastTapEvents
 * @link         https://github.com/zebedeu/last-tap-events
 * @author       Marcio Zebedeu
 * @copyright    2018 Marcio Zebedeu
 * @license      GPL2
 */

/**
 *  Plugin Name: Last Tap Events
 *  Description:  Last Tap Events is a Wordpress plugin that claims to be simple and objective so you can place event announcements on your site.
 * GitHub Plugin URI: https://github.com/zebedeu/last-tap-events
 *  Version:      1.0.3
 *  Author:       Márcio Zebedeu
 *  Author URI:    http://marciozebedeu.com
 *  License:      GPL2
 *  License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 *  Text Domain:  last-tap-event
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

require_once(dirname(__FILE__) . '/inc/LastTap_Init.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_Activate.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_Deactivate.php');
include_once(dirname(__FILE__) . '/inc/controller/LastTap_BaseController.php');
include_once(dirname(__FILE__) . '/inc/controller/LastTap_MembershipController.php');
require_once(dirname(__FILE__) . '/inc/api/LastTap_SettingsApi.php');
require_once(dirname(__FILE__) . '/inc/pages/LastTap_Dashboard.php');
require_once(dirname(__FILE__) . '/inc/api/LatTap_API_Client.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/LastTap_AdminCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/fpdf/fpdf.php');
require_once(dirname(__FILE__) . '/inc/api/LastTap_Pdf.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_MakePedf.php');
require_once(dirname(__FILE__) . '/inc/api/LastTap_Currency.php');
require_once(dirname(__FILE__) . '/inc/api/LastTap_Country.php');
// require_once(dirname(__FILE__) . '/inc/api/LastTap_QRcodeGenerator.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/LastTap_EventCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/LastTap_ManagerCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/LastTap_LocationCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/LastTap_NotificationCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/LastTap_TestimonialCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/LastTap_CptCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/LastTap_TaxonomyCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/callbacks/LastTap_ParticipantCallbacks.php');
require_once(dirname(__FILE__) . '/inc/api/widgets/LastTap_MediaWidget.php');
require_once(dirname(__FILE__) . '/inc/api/widgets/LastTap_LocationWidget.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_Enqueue.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_EmailController.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_SettingsLinks.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_CustomPostTypeController.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_CustomTaxonomyController.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_CustomTaxonomyController.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_TestimonialController.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_TemplateController.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_LocationController.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_NotificationController.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_MediaWidgetController.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_LocationWidgetController.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_EventController.php');
require_once(dirname(__FILE__) . '/inc/controller/LastTap_ParticipantController.php');  



/**
 * The code that runs during plugin activation
 */
function activate_event_plugin()
{
    LastTap_Activate::lt_activate();
}

register_activation_hook(__FILE__, 'activate_event_plugin');

/**
 * The code that runs during plugin deactivation
 */
function deactivate_event_plugin()
{
    LastTap_Deactivate::lt_deactivate();
}

register_deactivation_hook(__FILE__, 'deactivate_event_plugin');

/**
 * Initialize all the core classes of the plugin
 */
if (class_exists('LastTap_Init')) {


    LastTap_Init::lt_registerServices();

}