<?php
/**
 * @version 1.0
 *
 * @package LastTapEvents/inc/api/callbacks
 */

defined('ABSPATH') || exit;


class LastTap_TaxonomyCallbacks
{
    public function lt_taxSectionManager()
    {
        echo 'Create as many Custom Taxonomies as you want.';
    }

    public function lt_taxSanitize($input)
    {
        $output = get_option('event_plugin_tax');

        if (isset($_POST["remove"])) {
            unset($output[sanitize_text_field($_POST["remove"])]);

            return $output;
        }

        if (count($output) == 0) {
            $output[$input['taxonomy']] = $input;

            return $output;
        }

        foreach ($output as $key => $value) {
            if ($input['taxonomy'] === $key) {
                $output[$key] = $input;
            } else {
                $output[$input['taxonomy']] = $input;
            }
        }

        return $output;
    }

    public function lt_textField($args)
    {
        $name = $args['label_for'];
        $option_name = $args['option_name'];
        $value = '';

        if (isset($_POST["edit_taxonomy"])) {
            $input = get_option($option_name);
            $value = $input[sanitize_text_field($_POST["edit_taxonomy"])][$name];
        }

        echo '<input type="text" class="regular-text" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="' . $value . '" placeholder="' . $args['placeholder'] . '" required>';
    }

    public function lt_checkboxField($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $checked = false;

        if (isset($_POST["edit_taxonomy"])) {
            $checkbox = get_option($option_name);
            $checked = isset($checkbox[sanitize_text_field($_POST["edit_taxonomy"])][$name]) ?: false;
        }

        echo '<div class="' . $classes . '"><input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="1" class="" ' . ($checked ? 'checked' : '') . '><label for="' . $name . '"><div></div></label></div>';
    }

    public function lt_checkboxPostTypesField($args)
    {
        $output = '';
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $checked = false;

        if (isset($_POST["edit_taxonomy"])) {
            $checkbox = get_option($option_name);
        }

        $post_types = get_post_types(array('show_ui' => true));

        foreach ($post_types as $post) {

            if (isset($_POST["edit_taxonomy"])) {
                $checked = isset($checkbox[sanitize_text_field($_POST["edit_taxonomy"])][$name][$post]) ?: false;
            }

            $output .= '<div class="' . $classes . ' mb-10"><input type="checkbox" id="' . $post . '" name="' . $option_name . '[' . $name . '][' . $post . ']" value="1" class="" ' . ($checked ? 'checked' : '') . '><label for="' . $post . '"><div></div></label> <strong>' . $post . '</strong></div>';
        }

        echo $output;
    }
}