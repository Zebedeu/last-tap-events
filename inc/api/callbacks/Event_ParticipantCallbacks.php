<?php
/**
 * @version 1.0
 *
 * @package K7Events/inc/api/callbacks
 */

defined('ABSPATH') || exit;


class Event_ParticipantCallbacks extends Event_BaseController
{
    public function ev_shortcodePage()
    {
        return require_once("$this->plugin_path/templates/testimonial.php");
    }
}
