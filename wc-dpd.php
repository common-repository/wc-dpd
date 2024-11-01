<?php

/*
 * Plugin Name: DPD SK for WooCommerce
 * Description: DPD SK plugin for WooCommerce which exports orders to the DPD through their API
 * Version: 7.0.1
 * Author: Webikon
 * Author URI: https://www.webikon.sk
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wc-dpd
 * Requires at least: 5.3
 * Tested up to: 6.6.1
 * Requires PHP: 7.4
 * WC requires at least: 7.0
 * WC tested up to: 9.2.3
 */

namespace WcDPD;

defined('ABSPATH') || exit;

/**
 * Global plugin constants
 */
//  Work out plugin folder name and store it as a constant
$plugin_dir = str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
$plugin_dir = substr($plugin_dir, 0, strlen($plugin_dir) - 1);
define('WCDPD_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WCDPD_PLUGIN_DIR', $plugin_dir);
define('WCDPD_PLUGIN_INDEX', __FILE__);
define('WCDPD_PLUGIN_WC_MIN_VERSION', '7.0');
define('WCDPD_PLUGIN_ASSETS_URL', plugins_url(WCDPD_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR));
define('WCDPD_PLUGIN_TEMPLATES_PATH', WCDPD_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR);

/**
 * Declare HPOS support
 */
add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

/**
 * Check if WC meets the required version
 */
add_action('admin_notices', function () {
    if (class_exists('WooCommerce') && version_compare(WC()->version, WCDPD_PLUGIN_WC_MIN_VERSION, '>=')) {
        return; // WooCommerce is active and meets the required version, so no notice needed
    }

    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php echo sprintf(__('DPD SK for WooCommerce plugin requires WooCommerce version %s or higher to work properly. Please update WooCommerce to use this plugin.', 'wc-dpd'), WCDPD_PLUGIN_WC_MIN_VERSION); ?></p>
    </div>
    <?php
});

/**
 * Autoload plugin files
 */
add_action('plugins_loaded', function () {
    // Check that the composer autoloader is present
    $composer_autoloader = WCDPD_PLUGIN_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
    if (!file_exists($composer_autoloader)) {
        return;
    }

    require_once $composer_autoloader;

    if (!\WcDPD\is_woocommerce_active()) {
        return; // WooCommerce is not active, so exit early
    }

    \WcDPD\Core::initTranslations();

    // Compare the installed WooCommerce version with the required version
    if (!class_exists('WooCommerce') || version_compare(WC()->version, WCDPD_PLUGIN_WC_MIN_VERSION, '<')) {
        return; // WooCommerce is not active or doesn't meet the required version, so exit early
    }

    // Initialize the plugin
    \WcDPD\Core::init();
});
