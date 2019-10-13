<?php
/**
 * @version 1.0.13
 *
 * @package K7Events/inc/controller
 */

defined('ABSPATH') || exit;


class Event_CustomTaxonomyController extends Event_BaseController
{
    public $settings;

    public $callbacks;

    public $tax_callbacks;

    public $subpages = array();

    public $taxonomies = array();

    public function ev_register()
    {
        if (!$this->ev_activated('taxonomy_manager')) return;

        $this->settings = new Event_SettingsApi();

        $this->callbacks = new Event_AdminCallbacks();

        $this->tax_callbacks = new Event_TaxonomyCallbacks();

        $this->ev_setSubpages();

        $this->ev_setSettings();

        $this->ev_setSections();

        $this->ev_setFields();

        $this->settings->ev_addSubPages($this->subpages)->ev_register();

        $this->ev_storeCustomTaxonomies();

        if (!empty($this->taxonomies)) {
            add_action('init', array($this, 'ev_registerCustomTaxonomy'));
        }
    }

    public function ev_setSubpages()
    {
        $this->subpages = array(
            array(
                'parent_slug' => 'event_plugin',
                'page_title' => __('Custom Taxonomies', 'k7-event'),
                'menu_title' => __('Taxonomy Manager', 'k7-event'),
                'capability' => 'manage_options',
                'menu_slug' => 'event_taxonomy',
                'callback' => array($this->callbacks, 'ev_adminTaxonomy')
            )
        );
    }

    public function ev_setSettings()
    {
        $args = array(
            array(
                'option_group' => 'event_plugin_tax_settings',
                'option_name' => 'event_plugin_tax',
                'callback' => array($this->tax_callbacks, 'ev_taxSanitize')
            )
        );

        $this->settings->ev_setSettings($args);
    }

    public function ev_setSections()
    {
        $args = array(
            array(
                'id' => 'event_tax_index',
                'title' => __('Custom Taxonomy Manager', 'k7-event'),
                'callback' => array($this->tax_callbacks, 'ev_taxSectionManager'),
                'page' => 'event_taxonomy'
            )
        );

        $this->settings->ev_setSections($args);
    }

    public function ev_setFields()
    {
        $args = array(
            array(
                'id' => 'taxonomy',
                'title' => __('Custom Taxonomy ID', 'k7-event'),
                'callback' => array($this->tax_callbacks, 'ev_textField'),
                'page' => 'event_taxonomy',
                'section' => 'event_tax_index',
                'args' => array(
                    'option_name' => 'event_plugin_tax',
                    'label_for' => 'taxonomy',
                    'placeholder' => __('eg. genre', 'k7-event'),
                    'array' => 'taxonomy'
                )
            ),
            array(
                'id' => 'singular_name',
                'title' => __('Singular Name', 'k7-event'),
                'callback' => array($this->tax_callbacks, 'ev_textField'),
                'page' => 'event_taxonomy',
                'section' => 'event_tax_index',
                'args' => array(
                    'option_name' => 'event_plugin_tax',
                    'label_for' => 'singular_name',
                    'placeholder' => __('eg. Genre', 'k7-event'),
                    'array' => 'taxonomy'
                )
            ),
            array(
                'id' => 'hierarchical',
                'title' => __('Hierarchical', 'k7-event'),
                'callback' => array($this->tax_callbacks, 'ev_checkboxField'),
                'page' => 'event_taxonomy',
                'section' => 'event_tax_index',
                'args' => array(
                    'option_name' => 'event_plugin_tax',
                    'label_for' => 'hierarchical',
                    'class' => 'ui-toggle',
                    'array' => 'taxonomy'
                )
            ),
            array(
                'id' => 'objects',
                'title' => __('Post Types', 'k7-event'),
                'callback' => array($this->tax_callbacks, 'ev_checkboxPostTypesField'),
                'page' => 'event_taxonomy',
                'section' => 'event_tax_index',
                'args' => array(
                    'option_name' => 'event_plugin_tax',
                    'label_for' => 'objects',
                    'class' => 'ui-toggle',
                    'array' => 'taxonomy'
                )
            )
        );

        $this->settings->ev_setFields($args);
    }

    public function ev_storeCustomTaxonomies()
    {
        $options = get_option('event_plugin_tax') ?: array();

        foreach ($options as $option) {
            $labels = array(
                'name' => $option['singular_name'],
                'singular_name' => $option['singular_name'],
                'searev_items' => 'Search ' . $option['singular_name'],
                'all_items' => 'All ' . $option['singular_name'],
                'parent_item' => 'Parent ' . $option['singular_name'],
                'parent_item_colon' => 'Parent ' . $option['singular_name'] . ':',
                'edit_item' => 'Edit ' . $option['singular_name'],
                'update_item' => 'Update ' . $option['singular_name'],
                'add_new_item' => 'Add New ' . $option['singular_name'],
                'new_item_name' => 'New ' . $option['singular_name'] . ' Name',
                'menu_name' => $option['singular_name'],
            );

            $this->taxonomies[] = array(
                'hierarchical' => isset($option['hierarchical']) ? true : false,
                'labels' => $labels,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'show_in_rest' => true,
                'rewrite' => array('slug' => $option['taxonomy']),
                'objects' => isset($option['objects']) ? $option['objects'] : null
            );

        }
    }


    public function ev_registerCustomTaxonomy()
    {
        foreach ($this->taxonomies as $taxonomy) {
            $objects = isset($taxonomy['objects']) ? array_keys($taxonomy['objects']) : null;
            register_taxonomy($taxonomy['rewrite']['slug'], $objects, $taxonomy);
        }
    }
}