<?php
/**
 * @version 1.0.13
 *
 * @package K7Events/inc/controller
 */

defined('ABSPATH') || exit;


class Event_WidgetController extends Event_BaseController
{
    public function ev_register()
    {
        if (!$this->ev_activated('media_widget')) return;

        $media_widget = new Event_MediaWidget();
        $media_widget->ev_register();
    }
}