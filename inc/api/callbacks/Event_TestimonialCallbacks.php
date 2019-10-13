<?php
/**
 * @version 1.0.13
 *
 * @package K7Events/inc/api/callbacks
 */

defined('ABSPATH') || exit;


class Event_TestimonialCallbacks extends Event_BaseController
{
    public function ev_shortcodePage()
    {
        return require_once("$this->plugin_path/templates/testimonial.php");
    }
}
