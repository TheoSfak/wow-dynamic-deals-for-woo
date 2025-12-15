<?php
/**
 * Import/Export Functionality
 *
 * @package WooDynamicDeals
 * @since 1.0.0
 */

namespace WDD;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ImportExport Class
 * 
 * Handles JSON import/export for all rule types with validation.
 */
class ImportExport {
    /**
     * Export rules to JSON
     *
     * @param string $rule_type Rule type to export (price|tiered|cart|gift|all)
     * @return string JSON string
     */
    public static function export_rules($rule_type = 'all') {
        global $wpdb;
        
        $export_data = array(
            'version' => '1.0.0',
            'exported_at' => current_time('mysql'),
            'rules' => array()
        );

        $tables = array(
            'price' => $wpdb->prefix . 'wdd_pricing_rules',
            'tiered' => $wpdb->prefix . 'wdd_tiered_pricing',
            'cart' => $wpdb->prefix . 'wdd_cart_discount_rules',
            'gift' => $wpdb->prefix . 'wdd_gift_rules'
        );

        if ($rule_type === 'all') {
            foreach ($tables as $type => $table) {
                $export_data['rules'][$type] = $wpdb->get_results("SELECT * FROM {$table}", ARRAY_A);
            }
        } else {
            $export_data['rules'][$rule_type] = $wpdb->get_results("SELECT * FROM {$tables[$rule_type]}", ARRAY_A);
        }

        return wp_json_encode($export_data, JSON_PRETTY_PRINT);
    }

    /**
     * Import rules from JSON
     *
     * @param string $json_data JSON string to import
     * @param bool $overwrite Whether to overwrite existing rules
     * @return array Result with success/error counts
     */
    public static function import_rules($json_data, $overwrite = false) {
        global $wpdb;
        
        $result = array(
            'success' => 0,
            'errors' => 0,
            'skipped' => 0,
            'messages' => array()
        );

        $data = json_decode($json_data, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $result['messages'][] = __('Invalid JSON format', 'wow-dynamic-deals-for-woo');
            $result['errors']++;
            return $result;
        }

        if (!isset($data['rules']) || !is_array($data['rules'])) {
            $result['messages'][] = __('Invalid data structure', 'wow-dynamic-deals-for-woo');
            $result['errors']++;
            return $result;
        }

        $tables = array(
            'price' => $wpdb->prefix . 'wdd_pricing_rules',
            'tiered' => $wpdb->prefix . 'wdd_tiered_pricing',
            'cart' => $wpdb->prefix . 'wdd_cart_discount_rules',
            'gift' => $wpdb->prefix . 'wdd_gift_rules'
        );

        foreach ($data['rules'] as $type => $rules) {
            if (!isset($tables[$type])) {
                continue;
            }

            foreach ($rules as $rule) {
                $rule_id = isset($rule['id']) ? $rule['id'] : null;
                unset($rule['id']);

                if ($rule_id && !$overwrite) {
                    $exists = $wpdb->get_var($wpdb->prepare(
                        "SELECT COUNT(*) FROM {$tables[$type]} WHERE id = %d",
                        $rule_id
                    ));
                    
                    if ($exists) {
                        $result['skipped']++;
                        continue;
                    }
                }

                if (empty($rule['title'])) {
                    $result['errors']++;
                    /* translators: %s: Rule type */
                    $result['messages'][] = sprintf(__('Skipped rule without title in %s', 'wow-dynamic-deals-for-woo'), $type);
                    continue;
                }

                if ($rule_id && $overwrite) {
                    $wpdb->update($tables[$type], $rule, array('id' => $rule_id));
                } else {
                    $wpdb->insert($tables[$type], $rule);
                }

                if ($wpdb->last_error) {
                    $result['errors']++;
                    $result['messages'][] = $wpdb->last_error;
                } else {
                    $result['success']++;
                }
            }
        }


        return $result;
    }

    /**
     * Download export file
     *
     * @param string $rule_type Rule type to export
     */
    public static function download_export($rule_type = 'all') {
        $json = self::export_rules($rule_type);
        $filename = 'wdd-rules-' . $rule_type . '-' . gmdate('Y-m-d-His') . '.json';

        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($json));
        
        echo $json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        exit;
    }
}
