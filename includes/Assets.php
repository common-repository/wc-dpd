<?php

namespace WcDPD;

defined('ABSPATH') || exit;

/**
 * Assets class
 */
class Assets
{
    public static function init()
    {
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueueScripts']);
    }

    /**
     * Enqueue plugin styles and scripts
     *
     * @return void
     */
    public static function enqueueScripts()
    {
        if (!is_cart() && !is_checkout()) {
            return;
        }

        $is_map_widget_enabled = is_map_widget_enabled();

        // Enqueue styles
        wp_enqueue_style('wc_dpd_parcelshop_shipping_method_content_styles', WCDPD_PLUGIN_ASSETS_URL . 'styles/dpd-parcelshop-shipping-method-content.css', [], wc_dpd_get_plugin_version(), 'all');

        if ($is_map_widget_enabled) {
            wp_enqueue_style('wc_dpd_parcelshop_map_widget_styles', WCDPD_PLUGIN_ASSETS_URL . 'styles/dpd-parcelshop-map-widget.css', [], wc_dpd_get_plugin_version(), 'all');
        } else {
            wp_enqueue_style('wc_dpd_parcelshop_popup_styles', WCDPD_PLUGIN_ASSETS_URL . 'styles/dpd-parcelshop-popup.css', [], wc_dpd_get_plugin_version(), 'all');
        }

        // Enqueue scripts
        if ($is_map_widget_enabled) {
            wp_enqueue_script('wc_dpd_parcelshop_map_scripts', 'https://pus-maps.dpd.sk/lib/library.js', [], wc_dpd_get_plugin_version(), true);
            wp_enqueue_script('wc_dpd_parcelshop_map_widget_scripts', WCDPD_PLUGIN_ASSETS_URL . 'scripts/dpd-parcelshop-map-widget.js', [], wc_dpd_get_plugin_version(), true);
            wp_localize_script('wc_dpd_parcelshop_map_widget_scripts', 'wc_dpd_parcelshop_map_widget_settings', [
                'ajax_url' => admin_url('admin-ajax.php'),
            ]);
        } else {
            wp_enqueue_script('wc_dpd_parcelshop_popup_scripts', WCDPD_PLUGIN_ASSETS_URL . 'scripts/dpd-parcelshop-popup.js', [], wc_dpd_get_plugin_version(), true);
            wp_localize_script('wc_dpd_parcelshop_popup_scripts', 'wc_dpd_parcelshop_popup_settings', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'required_fields_error_message' => __('Please fill all the fields above!', 'wc-dpd'),
                'select_parcelshop_error_message' => __('Please select one of the available parcelshops!', 'wc-dpd')
            ]);
        }
    }

}
