<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/pages
 */

defined('ABSPATH') || exit;


final class LastTap_Init
{


    /**
     * Loop through the classes, initialize them,
     * and call the register() method if it exists
     * @return
     */
    public static function lt_registerServices()
    {

        foreach (self::lt_getServices() as $class) {
            $service = self::lt_instantiate($class);
            if (method_exists($service, 'lt_register')) {
                $service->lt_register();
            }
        }
    }

    /**
     * Store all the classes inside an array
     * @return array Full list of classes
     */
    public static function lt_getServices()
    {
        return [
            // LastTap_SettingsApi::class,
            LastTap_Dashboard::class,
            LastTap_Enqueue::class,
            LastTap_SettingsLinks::class,
            LastTap_CustomPostTypeController::class,
            LastTap_CustomTaxonomyController::class,
            LastTap_WidgetController::class,
            LastTap_TestimonialController::class,
            LastTap_TemplateController::class,
            LastTap_LocationController::class,
            LastTap_LocationWidget::class,
            LastTap_NotificationController::class,
            LastTap_EventController::class,
            LastTap_ParticipantController::class,
        ];
    }

    /**
     * Initialize the class
     * @param  class $class class from the services array
     * @return class instance  new instance of the class
     */
    private static function lt_instantiate($class)
    {
        $service = new $class();

        return $service;
    }
}
