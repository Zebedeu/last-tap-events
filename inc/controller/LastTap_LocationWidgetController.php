<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/controller
 * @see LastTap_BaseController
 */

defined('ABSPATH') || exit;


class LastTap_LocationWidgetController extends LastTap_BaseController
{
    public function lt_register()
    {
        if (!$this->lt_activated('location_widget')) return;

        $media_widget = new LastTap_LocationWidget();
        $media_widget->lt_register();
    }
}