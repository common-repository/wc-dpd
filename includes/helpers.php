<?php

namespace WcDPD;

use Automattic\WooCommerce\Utilities\OrderUtil;

defined('ABSPATH') || exit;

/**
 * Log to the file
 *
 * @param string $message
 * @param mixed $variable
 *
 * @return void
 */

function wc_dpd_log($message, $variable = null)
{
    $message = trim($message);
    $message = "DEBUG: $message";

    $log_handler = new \WC_Log_Handler_File();
    $file = $log_handler->get_log_file_path('wc_dpd');

    $date = get_date_from_gmt(gmdate('Y-m-d H:i:s'), 'Y-m-d H:i:s O');

    if (null !== $variable) {
        $message .= ': ' . wc_dpd_dump($variable);
    }

    // implementation note:
    // we do not use WooCommerce log handler intentionally, because it does not
    // use file locking, thus there is possibility that log gets damaged
    file_put_contents($file, "[$date] $message\n", LOCK_EX | FILE_APPEND);
}

/**
 * Dump variable
 *
 * @param mixed $variable
 *
 * @return mixed
 */
function wc_dpd_dump($variable)
{
    $type = gettype($variable);
    switch ($type) {
        case 'boolean':
            return $variable ? 'true' : 'false';
        case 'integer':
            return strval($variable);
        case 'double':
            return sprintf('%.1f', $variable);
        case 'string':
            $variable = str_replace([ '\\', "'" ], [ '\\\\', "\\'" ], $variable);
            return "'" . preg_replace_callback('/[\x00-\x1f\x7f-\xff]/', function ($m) {
                return "\\x" . bin2hex($m[0]);
            }, $variable) . "'";
        case 'array':
            $items = [];
            foreach ($variable as $key => $value) {
                $items[] = wc_dpd_dump($key) . ': ' . wc_dpd_dump($value);
            }
            return $items ? '[ ' . implode(', ', $items) . ' ]' : '[]';
        case 'object':
            $items = [];
            foreach (get_object_vars($variable) as $key => $value) {
                $items[] = wc_dpd_dump($key) . ': ' . wc_dpd_dump($value);
            }
            return get_class($variable) . ' ' . ($items ? '{ ' . implode(', ', $items) . ' }' : '{}');
        case 'resource':
        case 'resource (closed)':
            return $type;
        case 'NULL':
            return 'null';
    }
    return "??? $type ???";
}

/**
 * Get current plugin version
 *
 * @return string
 */
function wc_dpd_get_plugin_version()
{
    $plugin_data = get_plugin_data(WCDPD_PLUGIN_INDEX);
    $plugin_version = !empty($plugin_data['Version']) ? (string) $plugin_data['Version'] : '1.0.0';

    return $plugin_version;
}

/**
 * Include php template and pass variables there
 *
 * @param string $file_path
 * @param array $variables
 * @param boolean $print
 *
 * @return string
 */
function include_template($file_path, $variables = [])
{
    $templates_root = WCDPD_PLUGIN_TEMPLATES_PATH . DIRECTORY_SEPARATOR;
    $output = null;

    if (file_exists($templates_root . $file_path)) {
        // Extract the variables to a local namespace
        extract($variables);

        // Start output buffering
        ob_start();

        // Include the template file
        include $templates_root . $file_path;

        // End buffering and return its contents
        $output = ob_get_clean();
    }

    return $output;
}

/**
 * Check if HPOS mode is enabled in WooCommerce settings.
 *
 * @return bool true if HPOS mode is enabled, false otherwise.
 */
function wc_dpd_is_hpos_enabled()
{
    return (bool) class_exists(OrderUtil::class) && OrderUtil::custom_orders_table_usage_is_enabled();
}

/**
 * Check if woocommerce is active
 *
 * @return bool
 */
function is_woocommerce_active()
{
    // Check if WooCommerce is active on the network
    if (is_multisite()) {
        $network_active_plugins = get_site_option('active_sitewide_plugins');
        if (isset($network_active_plugins['woocommerce/woocommerce.php'])) {
            return true;
        }
    }

    // Check if WooCommerce is active on the current site
    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        return true;
    }

    return false;
}

/**
 * Check if map widget is enabled
 *
 * @return boolean
 */
function is_map_widget_enabled()
{
    $settings = DpdExportSettings::getDefaultSettings();

    if (empty($settings[DpdExportSettings::MAP_WIDGET_ENABLED_OPTION_KEY]) || empty($settings[DpdExportSettings::MAP_API_KEY_OPTION_KEY])) {
        return false;
    }

    return isset($settings[DpdExportSettings::MAP_WIDGET_ENABLED_OPTION_KEY]) && $settings[DpdExportSettings::MAP_WIDGET_ENABLED_OPTION_KEY] === 'yes';
}
