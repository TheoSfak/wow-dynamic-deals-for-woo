<?php
/**
 * Template Loader
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

namespace WDD;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Template Loader Class
 * 
 * Handles template loading with theme override support.
 * Template hierarchy:
 * 1. theme/woo-dynamic-deals/template-name.php
 * 2. plugin/templates/template-name.php
 */
class TemplateLoader {
    /**
     * Get the template path
     *
     * @param string $template_name Template file name
     * @return string Full path to template
     */
    public static function get_template_path($template_name) {
        $theme_path = get_stylesheet_directory() . '/woo-dynamic-deals/' . $template_name;
        if (file_exists($theme_path)) {
            return $theme_path;
        }

        return WDD_PLUGIN_DIR . 'templates/' . $template_name;
    }

    /**
     * Load a template file with variables
     *
     * @param string $template_name Template file name
     * @param array $args Variables to pass to template
     * @param bool $echo Whether to echo or return
     * @return string|void
     */
    public static function load_template($template_name, $args = array(), $echo = true) {
        $template_path = self::get_template_path($template_name);

        if (!file_exists($template_path)) {
            return '';
        }

        extract($args);

        if ($echo) {
            include $template_path;
        } else {
            ob_start();
            include $template_path;
            return ob_get_clean();
        }
    }

    /**
     * Get template part (for use in templates that extend others)
     *
     * @param string $slug Template slug
     * @param string $name Template variation name
     * @param array $args Variables to pass
     */
    public static function get_template_part($slug, $name = null, $args = array()) {
        $templates = array();
        
        if ($name) {
            $templates[] = "{$slug}-{$name}.php";
        }
        $templates[] = "{$slug}.php";

        foreach ($templates as $template_name) {
            $template_path = self::get_template_path($template_name);
            if (file_exists($template_path)) {
                extract($args);
                include $template_path;
                return;
            }
        }
    }
}
