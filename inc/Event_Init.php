<?php
/**
 * @version 1.0.13
 *
 * @package K7Events/inc/pages
 */

defined('ABSPATH') || exit;


final class Event_Init
{


    /**
     * Loop through the classes, initialize them,
     * and call the register() method if it exists
     * @return
     */
    public static function ev_registerServices()
    {

        foreach (self::ev_getServices() as $class) {
            $service = self::ev_instantiate($class);
            if (method_exists($service, 'ev_register')) {
                $service->ev_register();
            }
        }
    }

    /**
     * Store all the classes inside an array
     * @return array Full list of classes
     */
    public static function ev_getServices()
    {
        return [
            // Event_SettingsApi::class,
            Event_Dashboard::class,
            Event_Enqueue::class,
            Event_SettingsLinks::class,
            Event_CustomPostTypeController::class,
            Event_CustomTaxonomyController::class,
            Event_WidgetController::class,
            Event_TestimonialController::class,
            Event_TemplateController::class,
            Event_LocationController::class,
            Event_LocationWidget::class,
            Event_NotificationController::class,
            Event_EventController::class,
            Event_ParticipantController::class,
        ];
    }

    /**
     * Initialize the class
     * @param  class $class class from the services array
     * @return class instance  new instance of the class
     */
    private static function ev_instantiate($class)
    {
        $service = new $class();

        return $service;
    }
}
