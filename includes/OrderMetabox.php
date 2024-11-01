<?php

namespace WcDPD;

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

defined('ABSPATH') || exit;

/**
 * OrderMetabox class
 */
class OrderMetabox
{
    public const EXPORT_ACTION_KEY = 'dpd_export';
    public const RESET_ACTION_KEY = 'dpd_reset';

    public static function init()
    {
        add_action('add_meta_boxes', [__CLASS__, 'addMetabox']);
        add_action('save_post', [__CLASS__, 'processExport']);
        add_filter('save_post', [__CLASS__, 'processReset']);
    }

    public static function addMetabox()
    {
        $screen = class_exists(CustomOrdersTableController::class) && wc_get_container()->get(CustomOrdersTableController::class)->custom_orders_table_usage_is_enabled()
                ? \wc_get_page_screen_id('shop-order')
                : 'shop_order';

        add_meta_box('dpd-export', __('DPD Export', 'wc-dpd'), [__CLASS__, 'renderMetabox'], $screen, 'side', 'core');
    }

    /**
     * Render export metabox
     *
     * @return void
     */
    public static function renderMetabox($post_or_order_object)
    {
        $order = ($post_or_order_object instanceof \WP_Post) ? wc_get_order($post_or_order_object->ID) : $post_or_order_object;
        $order_id = $order->get_id();

        if (!$order_id) {
            return;
        }

        $default_settings = DpdExportSettings::getDefaultSettings();
        $dpd_export_result = $order->get_meta(Order::EXPORT_STATUS_META_KEY, true);

        if ($dpd_export_result == Order::EXPORT_SUCCESS_STATUS) {
            $dpd_label_url = $order->get_meta(Order::EXPORT_LABEL_URL_META_KEY, true);
            $dpd_package_number = wp_kses_post($order->get_meta(Order::EXPORT_PACKAGE_NUMBER_META_KEY, true));

            echo '<p>' . __('Export Status', 'wc-dpd') . ': ' . __('Success', 'wc-dpd') . '</p>';

            if ($dpd_label_url) {
                echo '<p><a href="' . esc_url($dpd_label_url) . '">' . __('Download DPD label', 'wc-dpd') . '</a></p>';
            }

            if ($dpd_package_number) {
                echo '<p>' . __('Package number', 'wc-dpd') . ': <strong>' . $dpd_package_number . '</strong></p>';
            }

            echo '<input type="submit" class="button" value="' . __('Reset', 'wc-dpd') . '" name="' . esc_attr(self::RESET_ACTION_KEY) . '">';

            return;
        }

        $default_bank_id = isset($default_settings[DpdExportSettings::BANK_ID_OPTION_KEY]) && !empty($default_settings[DpdExportSettings::BANK_ID_OPTION_KEY]) ? $default_settings[DpdExportSettings::BANK_ID_OPTION_KEY] : null;
        $default_address_id = isset($default_settings[DpdExportSettings::ADDRESS_ID_OPTION_KEY]) && !empty($default_settings[DpdExportSettings::ADDRESS_ID_OPTION_KEY]) ? $default_settings[DpdExportSettings::ADDRESS_ID_OPTION_KEY] : null;
        $default_shipping = isset($default_settings[DpdExportSettings::SHIPPING_OPTION_KEY]) && !empty($default_settings[DpdExportSettings::SHIPPING_OPTION_KEY]) ? $default_settings[DpdExportSettings::SHIPPING_OPTION_KEY] : null;
        $default_notification = isset($default_settings[DpdExportSettings::NOTIFICATION_OPTION_KEY]) && !empty($default_settings[DpdExportSettings::NOTIFICATION_OPTION_KEY]) ? $default_settings[DpdExportSettings::NOTIFICATION_OPTION_KEY] : 'no';

        $shipping = $order->get_meta(Order::SHIPPING_META_KEY, true);
        $shipping = $shipping ? $shipping : $default_shipping;

        $bank_id_options = !empty(DpdExportSettings::getRepeaterOptions(Order::BANK_ID_META_KEY)) ? DpdExportSettings::getRepeaterOptions(Order::BANK_ID_META_KEY) : [];
        $selected_bank_id_option = $order->get_meta(Order::BANK_ID_META_KEY, true);

        $address_id_options = !empty(DpdExportSettings::getRepeaterOptions(Order::ADDRESS_ID_META_KEY)) ? DpdExportSettings::getRepeaterOptions(Order::ADDRESS_ID_META_KEY) : [];
        $selected_address_id_option = $order->get_meta(Order::ADDRESS_ID_META_KEY, true);

        $shipping_options = !empty(DpdExportSettings::getShippingOptions()) ? DpdExportSettings::getShippingOptions() : [];

        $notification = $order->get_meta(Order::NOTIFICATION_META_KEY, true);
        $notification = $notification ? $notification : $default_notification;
        $notification = $notification !== 'no' ? 'yes' : 'no';

        $reference_1 = $order->get_meta(Order::REFERENCE_1_META_KEY, true);
        $reference_2 = $order->get_meta(Order::REFERENCE_2_META_KEY, true);

        ?>

        <form method="post" name="form">
			<?php if (!empty($bank_id_options)) : ?>
				<p>
					<label for="<?php echo esc_attr(Order::BANK_ID_META_KEY); ?>"><?php _e('ID Bank account', 'wc-dpd')?>:</label><br>
					<select id="<?php echo esc_attr(Order::BANK_ID_META_KEY); ?>" name="<?php echo esc_attr(Order::BANK_ID_META_KEY); ?>" style="width: 100%;">
						<?php foreach ($bank_id_options as $key => $values):
						    $selected_option = $selected_bank_id_option ? $selected_bank_id_option : $default_bank_id;
						    if ($selected_option) {
						        $selected = $values['value'] == $selected_option ? true : false;
						    } else {
						        $selected = $values['default'] ? true : false;
						    }
						    ?>
							<option value="<?php echo esc_attr($values['value']); ?>" <?php echo $selected ? ' selected="selected"' : ''; ?>>
								<?php echo esc_html($values['nice_value']); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</p>
			<?php endif; ?>

			<?php if (!empty($address_id_options)) : ?>
				<p>
					<label for="<?php echo esc_attr(Order::ADDRESS_ID_META_KEY); ?>"><?php _e('ID of the collection point', 'wc-dpd')?>:</label><br>
					<select id="<?php echo esc_attr(Order::ADDRESS_ID_META_KEY); ?>" name="<?php echo esc_attr(Order::ADDRESS_ID_META_KEY); ?>" style="width: 100%;">
						<?php foreach ($address_id_options as $key => $values):
						    $selected_option = $selected_address_id_option ? $selected_address_id_option : $default_address_id;
						    if ($selected_option) {
						        $selected = $values['value'] == $selected_option ? true : false;
						    } else {
						        $selected = $values['default'] ? true : false;
						    }
						    ?>
							<option value="<?php echo esc_attr($values['value']); ?>" <?php echo $selected ? ' selected="selected"' : ''; ?>>
								<?php echo esc_html($values['nice_value']); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</p>
			<?php endif; ?>

			<?php if (!empty($shipping_options)) : ?>
				<p>
					<label for="<?php echo esc_attr(Order::SHIPPING_META_KEY); ?>"><?php _e('Shipping product', 'wc-dpd')?>:</label><br>
					<select id="<?php echo esc_attr(Order::SHIPPING_META_KEY); ?>" name="<?php echo esc_attr(Order::SHIPPING_META_KEY); ?>" class="js-wc-dpd-shipping-type-field" style="width: 100%;">
						<?php foreach ($shipping_options as $key => $value): ?>
							<option value="<?php echo esc_attr($key); ?>" data-notification-required="<?php echo esc_attr(DpdExportSettings::isNotificationRequired($key)); ?>" <?php echo $shipping == $key ? ' selected="selected"' : ''; ?>>
								<?php echo esc_html($value); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</p>
			<?php endif; ?>

			<p class="js-wc-dpd-notification-field-row">
				<label for="<?php echo esc_attr(Order::NOTIFICATION_META_KEY); ?>"><?php _e('Notification', 'wc-dpd')?>:</label><br>
				<input type="checkbox" id="<?php echo esc_attr(Order::NOTIFICATION_META_KEY); ?>" name="<?php echo esc_attr(Order::NOTIFICATION_META_KEY); ?>" class="js-wc-dpd-notification-field" <?php checked($notification, 'yes'); ?>>
			</p>

			<p>
				<label for="<?php echo esc_attr(Order::REFERENCE_1_META_KEY); ?>"><?php echo sprintf(__('Reference %d', 'wc-dpd'), 1); ?>:</label><br>
				<input type="text" id="<?php echo esc_attr(Order::REFERENCE_1_META_KEY); ?>" name="<?php echo esc_attr(Order::REFERENCE_1_META_KEY); ?>" value="<?php echo esc_attr($reference_1); ?>">
			</p>

			<p>
				<label for="<?php echo esc_attr(Order::REFERENCE_2_META_KEY); ?>"><?php echo sprintf(__('Reference %d', 'wc-dpd'), 2); ?>:</label><br>
				<input type="text" id="<?php echo esc_attr(Order::REFERENCE_2_META_KEY); ?>" name="<?php echo esc_attr(Order::REFERENCE_2_META_KEY); ?>" value="<?php echo esc_attr($reference_2); ?>">
			</p>

			<p>
				<input type="hidden" value="<?php echo $order_id; ?>" name="<?php echo esc_attr(OrderList::EXPORT_ORDER_KEY); ?>">
				<input type="submit" class="button" value="<?php _e('Export to DPD', 'wc-dpd'); ?>" name="<?php echo esc_attr(self::EXPORT_ACTION_KEY); ?>">
			</p>
        </form>
		<?php
    }

    /**
     * Save data from metabox fiels
     *
     * @return void
     */
    public static function processExport($order_id)
    {
        if (!is_admin()) {
            return;
        }

        if (!$order_id) {
            return;
        }

        $order = wc_get_order($order_id);

        if (!$order instanceof \WC_Order) {
            return;
        }

        // Save metabox fields
        if (isset($_POST[Order::SHIPPING_META_KEY])) {
            $order->update_meta_data(Order::SHIPPING_META_KEY, sanitize_text_field($_POST[Order::SHIPPING_META_KEY]));
        }

        if (isset($_POST[Order::ADDRESS_ID_META_KEY])) {
            $order->update_meta_data(Order::ADDRESS_ID_META_KEY, sanitize_text_field($_POST[Order::ADDRESS_ID_META_KEY]));
        }

        if (isset($_POST[Order::BANK_ID_META_KEY])) {
            $order->update_meta_data(Order::BANK_ID_META_KEY, sanitize_text_field($_POST[Order::BANK_ID_META_KEY]));
        }

        if (isset($_POST[Order::NOTIFICATION_META_KEY])) {
            $order->update_meta_data(Order::NOTIFICATION_META_KEY, $_POST[Order::NOTIFICATION_META_KEY] == 'on' ? 'yes' : 'no');
        } else {
            $order->update_meta_data(Order::NOTIFICATION_META_KEY, 'no');
        }

        if (isset($_POST[Order::REFERENCE_1_META_KEY])) {
            $order->update_meta_data(Order::REFERENCE_1_META_KEY, sanitize_text_field($_POST[Order::REFERENCE_1_META_KEY]));
        }

        if (isset($_POST[Order::REFERENCE_2_META_KEY])) {
            $order->update_meta_data(Order::REFERENCE_2_META_KEY, sanitize_text_field($_POST[Order::REFERENCE_2_META_KEY]));
        }

        $order->save_meta_data();

        // Process export
        if (isset($_POST[self::EXPORT_ACTION_KEY])) {
            Order::export($order_id);
        }
    }

    /**
     * Process order reset
     *
     * @param int $order_id
     *
     * @return mixed
     */
    public static function processReset($order_id)
    {
        if (!is_admin()) {
            return;
        }

        if (!$order_id) {
            return;
        }

        $order = wc_get_order($order_id);

        if (!$order instanceof \WC_Order) {
            return;
        }

        if ($order_id && isset($_POST[self::RESET_ACTION_KEY])) {
            Order::reset($order_id);

            $order_edit_url = admin_url('post.php?post=' . $order_id . '&action=edit');

            wp_safe_redirect($order_edit_url);
            exit;
        }

        return $order_id;
    }
}
