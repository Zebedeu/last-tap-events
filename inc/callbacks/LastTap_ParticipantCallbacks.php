<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/api/callbacks
 */

defined('ABSPATH') || exit;


class LastTap_ParticipantCallbacks extends LastTap_BaseController
{
    public function lt_shortcodePage()
    {
        return require_once("$this->plugin_path/templates/testimonial.php");
    }
}
