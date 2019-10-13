<?php
/**
 * @version 1.0.13
 *
 * @package K7Events/inc/api/callbacks
 */

defined('ABSPATH') || exit;


class Event_AdminCallbacks extends Event_BaseController
{
    public function ev_adminDashboard()
    {
        return require_once("$this->plugin_path/templates/admin/admin.php");
    }

    public function ev_adminCpt()
    {
        return require_once("$this->plugin_path/templates/cpt.php");
    }

    public function ev_adminTaxonomy()
    {
        return require_once("$this->plugin_path/templates/taxonomy.php");
    }

    public function ev_adminWidget()
    {
        return require_once("$this->plugin_path/templates/widget.php");
    }

    public function ev_adminGallery()
    {
        echo "<h1>Gallery Manager</h1>";
    }

    public function ev_adminTestimonial()
    {
        echo "<h1>Testimonial Manager</h1>";
    }

    public function ev_adminTemplates()
    {
        echo "<h1>Templates Manager</h1>";
    }

    public function ev_adminAuth()
    {
        echo "<h1>Templates Manager</h1>";
    }

    public function ev_adminMembership()
    {
        echo "<h1>Membership Manager</h1>";
    }

    public function painel()
    {
        return require_once("$this->plugin_path/templates/panel.php");
    }
}