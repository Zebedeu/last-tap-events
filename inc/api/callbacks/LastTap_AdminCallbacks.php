<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/api/callbacks
 */

defined('ABSPATH') || exit;


class LastTap_AdminCallbacks extends LastTap_BaseController
{
    public function lt_adminDashboard()
    {
        return require_once("$this->plugin_path/templates/admin/admin.php");
    }

    public function lt_adminCpt()
    {
        return require_once("$this->plugin_path/templates/cpt.php");
    }

    public function lt_adminTaxonomy()
    {
        return require_once("$this->plugin_path/templates/taxonomy.php");
    }

    public function lt_adminWidget()
    {
        return require_once("$this->plugin_path/templates/widget.php");
    }

    public function lt_adminGallery()
    {
        echo "<h1>Gallery Manager</h1>";
    }

    public function lt_adminTestimonial()
    {
        echo "<h1>Testimonial Manager</h1>";
    }

    public function lt_adminTemplates()
    {
        echo "<h1>Templates Manager</h1>";
    }

    public function lt_adminAuth()
    {
        echo "<h1>Templates Manager</h1>";
    }

    public function lt_adminMembership()
    {
        echo "<h1>Membership Manager</h1>";
    }

    public function painel()
    {
        return require_once("$this->plugin_path/templates/panel.php");
    }
}