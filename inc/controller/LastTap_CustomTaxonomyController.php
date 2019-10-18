<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/controller
 */

defined('ABSPATH') || exit;


class LastTap_CustomTaxonomyController extends LastTap_BaseController
{
    public $settings;

    public $callbacks;

    public $tax_callbacks;

    public $subpages = array();

    public $taxonomies = array();

    public function lt_register()
    {
        if (!$this->lt_activated('taxonomy_manager')) return;

        $this->settings = new LastTap_SettingsApi();

        $this->callbacks = new LastTap_AdminCallbacks();

        $this->tax_callbacks = new LastTap_TaxonomyCallbacks();

        $this->lt_setSubpages();

        $this->lt_setSettings();

        $this->lt_setSections();

        $this->lt_setFields();

        $this->settings->lt_addSubPages($this->subpages)->lt_register();

        $this->lt_storeCustomTaxonomies();

        if (!empty($this->taxonomies)) {
            add_action('init', array($this, 'lt_registerCustomTaxonomy'));
        }
    }

    public function lt_setSubpages()
    {
        $this->subpages = array(
            array(
                'parent_slug' => 'event_plugin',
                'page_title' => __('Custom Taxonomies', 'last-tap-event'),
                'menu_title' => __('Taxonomy Manager', 'last-tap-event'),
                'capability' => 'manage_options',
                'menu_slug' => 'event_taxonomy',
                'callback' => array($this->callbacks, 'lt_adminTaxonomy')
            )
        );
    }

    public function lt_setSettings()
    {
        $args = array(
            array(
                'option_group' => 'event_plugin_tax_settings',
                'option_name' => 'event_plugin_tax',
                'callback' => array($this->tax_callbacks, 'lt_taxSanitize')
            )
        );

        $this->settings->lt_setSettings($args);
    }

    public function lt_setSections()
    {
        $args = array(
            array(
                'id' => 'event_tax_index',
                'title' => __('Custom Taxonomy Manager', 'last-tap-event'),
                'callback' => array($this->tax_callbacks, 'lt_taxSectionManager'),
                'page' => 'event_taxonomy'
            )
        );

        $this->settings->lt_setSections($args);
    }

    public function lt_setFields()
    {
        $args = array(
            array(
                'id' => 'taxonomy',
                'title' => __('Custom Taxonomy ID', 'last-tap-event'),
                'callback' => array($this->tax_callbacks, 'lt_textField'),
                'page' => 'event_taxonomy',
                'section' => 'event_tax_index',
                'args' => array(
                    'option_name' => 'event_plugin_tax',
                    'label_for' => 'taxonomy',
                    'placeholder' => __('eg. genre', 'last-tap-event'),
                    'array' => 'taxonomy'
                )
            ),
            array(
                'id' => 'singular_name',
                'title' => __('Singular Name', 'last-tap-event'),
                'callback' => array($this->tax_callbacks, 'lt_textField'),
                'page' => 'event_taxonomy',
                'section' => 'event_tax_index',
                'args' => array(
                    'option_name' => 'event_plugin_tax',
                    'label_for' => 'singular_name',
                    'placeholder' => __('eg. Genre', 'last-tap-event'),
                    'array' => 'taxonomy'
                )
            ),
            array(
                'id' => 'hierarchical',
                'title' => __('Hierarchical', 'last-tap-event'),
                'callback' => array($this->tax_callbacks, 'lt_checkboxField'),
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
                'title' => __('Post Types', 'last-tap-event'),
                'callback' => array($this->tax_callbacks, 'lt_checkboxPostTypesField'),
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

        $this->settings->lt_setFields($args);
    }

    public function lt_storeCustomTaxonomies()
    {
        $options = get_option('event_plugin_tax') ?: array();

        foreach ($options as $option) {
            $labels = array(
                'name' => $option['singular_name'],
                'singular_name' => $option['singular_name'],
                'searlt_items' => 'Search ' . $option['singular_name'],
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


    public function lt_registerCustomTaxonomy()
    {
        foreach ($this->taxonomies as $taxonomy) {
            $objects = isset($taxonomy['objects']) ? array_keys($taxonomy['objects']) : null;
            register_taxonomy($taxonomy['rewrite']['slug'], $objects, $taxonomy);
        }
    }
}