<?php

namespace WcDPD;

defined('ABSPATH') || exit;

class DpdRepeaterField
{
    /**
     * Adjust form post data before save
     *
     * @return void
     */
    public function adjustPostData()
    {
        if (empty($_POST)) {
            return;
        }

        $repeater_values = [];

        $repeater_fields_keys = self::getRepeaterFieldsIds();
        foreach($repeater_fields_keys as $field_key) {
            $default_value = !empty($_POST['woocommerce_dpd_export_'.$field_key.'_default'][0]) ? $_POST['woocommerce_dpd_export_'.$field_key.'_default'][0] : '';

            if (!empty($_POST['woocommerce_dpd_export_'.$field_key.'_value'])) {
                foreach ($_POST['woocommerce_dpd_export_'.$field_key.'_value'] as $key => $value) {
                    $repeater_values[$key]['value'] = sanitize_text_field($value);
                    $repeater_values[$key]['default'] = $value == $default_value ? true : false;
                }

                unset($_POST['woocommerce_dpd_export_'.$field_key.'_value']);
            }

            if (!empty($_POST['woocommerce_dpd_export_'.$field_key.'_nice_value'])) {
                foreach ($_POST['woocommerce_dpd_export_'.$field_key.'_nice_value'] as $key => $value) {
                    $repeater_values[$key]['nice_value'] = sanitize_text_field($value);
                }

                unset($_POST['woocommerce_dpd_export_'.$field_key.'_nice_value']);
            }

            unset($_POST['woocommerce_dpd_export_'.$field_key.'_default']);

            if (!empty($repeater_values)) {
                $_POST['woocommerce_dpd_export_'.$field_key] = serialize($repeater_values);
            }
        }
    }

    /**
     * Initialize method options fields
     *
     * @return void
     */
    public function init_form_fields()
    {
        $this->form_fields = [
            self::DELIS_ID_OPTION_KEY => [
                'title' => __('ID delis', 'wc-dpd'),
                'label' => __('This is the username you got from DPD to login to their service', 'wc-dpd'),
                'description' => __('Unique customer identifier assigned by DPD.', 'wc-dpd'),
                'desc_tip' => true,
            ],
            self::EMAIL_OPTION_KEY => [
                'title' => __('Client email', 'wc-dpd'),
            ],
            self::API_KEY_OPTION_KEY => [
                'title' => __('API key', 'wc-dpd'),
                'label' => __('Your api key', 'wc-dpd'),
                'description' => __('Unique authentication key required to access API.', 'wc-dpd'),
                'desc_tip' => true,
            ],
            self::BANK_ID_OPTION_KEY => [
                'title' => __('Bank account ID', 'wc-dpd'),
                'type' => 'repeater',
                'max_rows' => 10,
                'label_text' => __('Bank account', 'wc-dpd'),
                'add_btn_text' => __('Add bank account', 'wc-dpd'),
                'repeater_description' => __('Select your default bank account.', 'wc-dpd'),
            ],
            self::ADDRESS_ID_OPTION_KEY => [
                'title' => __('ID of the collection address', 'wc-dpd'),
                'type' => 'repeater',
                'max_rows' => 10,
                'label_text' => __('Address', 'wc-dpd'),
                'add_btn_text' => __('Add address', 'wc-dpd'),
                'repeater_description' => __('Select your default address.', 'wc-dpd'),
            ],
            self::SHIPPING_OPTION_KEY => [
                'title' => __('Transport', 'wc-dpd'),
                'type' => 'select',
                'options' => self::getShippingOptions(),
                'class' => 'js-wc-dpd-shipping-type-field'
            ],
            self::NOTIFICATION_OPTION_KEY => [
                'title' => __('Notification', 'wc-dpd'),
                'type' => 'checkbox',
                'default' => 'no',
                'class' => 'js-wc-dpd-notification-field'
            ],
            self::LABELS_FORMAT_OPTION_KEY => [
                'title' => __('Labels format', 'wc-dpd'),
                'type' => 'select',
                'default' => 'a4',
                'options' => [
                    'A4' => 'A4',
                    'A6' => 'A6',
                ]
            ],
        ];
    }

    /**
     * Add repeater field html
     *
     * @param string $html
     * @param string $key
     * @param array $data
     * @param object $wc_settings
     *
     * @return string
     */
    public function addRepeaterFieldHtml($html = '', $key = '', $data = [], $wc_settings = null)
    {
        $field_key = $this->get_field_key($key);
        $defaults  = array(
            'title' => '',
            'disabled' => false,
            'class' => '',
            'label_text' => '',
            'desc_tip' => false,
            'max_rows' => 10,
            'type' => 'repeater',
            'add_btn_text' => '',
            'description' => '',
            'repeater_description' => '',
        );

        $data = wp_parse_args($data, $defaults);

        $values = self::getRepeaterOptions($key);
        $values = htmlspecialchars(json_encode($values), ENT_QUOTES, 'UTF-8');

        $props = [
            'maxRows' => $data['max_rows'],
            'inputName' => $field_key,
            'labelText' => $data['label_text'],
            'removeLabel' => __('Remove', 'wc-dpd'),
            'titlePlaceholder' => __('Title', 'wc-dpd'),
            'valuePlaceholder' => __('Value', 'wc-dpd'),
        ];
        $props = htmlspecialchars(json_encode($props), ENT_QUOTES, 'UTF-8');

        ob_start();
        ?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr($field_key); ?>"><?php echo wp_kses_post($data['title']); ?> <?php echo $this->get_tooltip_html($data); // WPCS: XSS ok.?></label>
				</th>

				<td class="forminp">
					 <fieldset class="repeatable-field <?php echo esc_attr($data['class']); ?>" data-component="field-repeater" data-props="<?php echo $props; ?>" data-inputs-data="<?php echo $values; ?>" tabindex="0">
						<legend class="screen-reader-text"><span><?php echo wp_kses_post($data['title']); ?></span></legend>

						<ol class="repeatable-field__rows" data-ref="rowList"></ol>

						<?php if(!empty($data['repeater_description'])) : ?>
							<p><small><?php echo wp_kses_post($data['repeater_description']); ?></small></p>
						<?php endif; ?>

						<div class="repeatable-field__bottom">
							<span class="repeatable-field__limit-counter" data-ref="limitCounter"></span>

							<button class="repeatable-field__add-button button" data-ref="addButton" type="button">+ <?php echo esc_attr($data['add_btn_text']); ?></button>
						</div>

						<?php echo $this->get_description_html($data); // WPCS: XSS ok.?>
					</fieldset>
				</td>
			</tr>
		<?php

        return ob_get_clean();
    }

    /**
     * Get repeater option list of options
     *
     * @param string $option_key
     *
     * @return array
     */
    public static function getRepeaterOptions($option_key = '')
    {
        $values = maybe_unserialize((new DpdExportSettings())->get_option($option_key));

        // Backwards value compatibility with previous plugin version
        if(is_string($values)) {
            $values = [['default' => true, 'nice_value' => (string) $values, 'value' => (string) $values]];
        }

        return $values;
    }

    /**
     * Get repeater field value
     *
     * @param string $option_key
     *
     * @return string
     */
    public static function getRepeaterValue($option_key = '')
    {
        $options = self::getRepeaterOptions($option_key);

        // Try to get default value
        foreach ($options as $key => $value) {
            if(!empty($value['default']) && $value['default']) {
                return $value['value'];
            }
        }

        // Return first option value
        return !empty($options[0]['value']) ? $options[0]['value'] : '';
    }

    /**
     * Get default settings
     *
     * @return array
     */
    public static function getDefaultSettings()
    {
        $settings = get_option('woocommerce_'.self::SETTINGS_ID_KEY.'_settings');
        $repeater_fields_keys = self::getRepeaterFieldsIds();

        foreach ($settings as $key => $value) {
            if (in_array($key, $repeater_fields_keys)) {
                $settings[$key] = self::getRepeaterValue($key);
            }
        }

        $settings = is_array($settings) ? $settings : [];

        return [
            self::DELIS_ID_OPTION_KEY => isset($settings[self::DELIS_ID_OPTION_KEY]) && !empty($settings[self::DELIS_ID_OPTION_KEY]) ? sanitize_text_field($settings[self::DELIS_ID_OPTION_KEY]) : '',
            self::EMAIL_OPTION_KEY => isset($settings[self::EMAIL_OPTION_KEY]) && !empty($settings[self::EMAIL_OPTION_KEY]) ? sanitize_text_field($settings[self::EMAIL_OPTION_KEY]) : '',
            self::API_KEY_OPTION_KEY => isset($settings[self::API_KEY_OPTION_KEY]) && !empty($settings[self::API_KEY_OPTION_KEY]) ? sanitize_text_field($settings[self::API_KEY_OPTION_KEY]) : '',
            self::BANK_ID_OPTION_KEY => isset($settings[self::BANK_ID_OPTION_KEY]) && !empty($settings[self::BANK_ID_OPTION_KEY]) ? sanitize_text_field($settings[self::BANK_ID_OPTION_KEY]) : '',
            self::ADDRESS_ID_OPTION_KEY => isset($settings[self::ADDRESS_ID_OPTION_KEY]) && !empty($settings[self::ADDRESS_ID_OPTION_KEY]) ? sanitize_text_field($settings[self::ADDRESS_ID_OPTION_KEY]) : '',
            self::SHIPPING_OPTION_KEY => isset($settings[self::SHIPPING_OPTION_KEY]) && !empty($settings[self::SHIPPING_OPTION_KEY]) ? sanitize_text_field($settings[self::SHIPPING_OPTION_KEY]) : '',
            self::NOTIFICATION_OPTION_KEY => isset($settings[self::NOTIFICATION_OPTION_KEY]) && !empty($settings[self::NOTIFICATION_OPTION_KEY]) ? sanitize_text_field($settings[self::NOTIFICATION_OPTION_KEY]) : '',
            self::LABELS_FORMAT_OPTION_KEY => isset($settings[self::LABELS_FORMAT_OPTION_KEY]) && !empty($settings[self::LABELS_FORMAT_OPTION_KEY]) ? sanitize_text_field($settings[self::LABELS_FORMAT_OPTION_KEY]) : 'A4'
        ];
    }

    /**
     * Add admin scripts
     *
     * @return void
     */
    public static function addScripts()
    {
        // Only on the settings page
        if (!self::isCurrentPageSettingsPage() && !self::isCurrentPageOrderDetail()) {
            return;
        }

        wp_enqueue_script(self::SETTINGS_ID_KEY . '_scripts', WCDPD_PLUGIN_ASSETS_URL . 'scripts/dpd-export-settings-admin.js', [], wc_dpd_get_plugin_version(), true);
        wp_localize_script(self::SETTINGS_ID_KEY . '_scripts', 'wc_dpd_settings', ['required_notifications_shipping_keys' => self::getRequiredNotificationsShippingKeys()]);

        // Repeater field assets
        wp_enqueue_script(self::SETTINGS_ID_KEY . '_repeater_field', WCDPD_PLUGIN_ASSETS_URL . 'scripts/dpd-export-settings-admin-repeater.js', [], wc_dpd_get_plugin_version(), true);
        wp_enqueue_style(self::SETTINGS_ID_KEY . '_repeater_field', WCDPD_PLUGIN_ASSETS_URL . 'styles/dpd-export-settings-admin-repeater.css', [], wc_dpd_get_plugin_version(), 'all');
    }

    /**
     * Check if the current page is plugin settings page
     *
     * @return boolean
     */
    public static function isCurrentPageSettingsPage()
    {
        if (
            is_admin() &&
            !empty($_GET['page']) && $_GET['page'] == 'wc-settings' &&
            !empty($_GET['tab']) && $_GET['tab'] == 'shipping' &&
            !empty($_GET['section']) && $_GET['section'] == self::SETTINGS_ID_KEY
        ) {
            return true;
        }

        return false;
    }

    /**
     * Check if the current page is order detail
     *
     * @return boolean
     */
    public static function isCurrentPageOrderDetail()
    {
        if (
            is_admin() &&
            !empty($_GET['post']) && (int) $_GET['post'] &&
            get_post_type($_GET['post']) == 'shop_order'
        ) {
            return true;
        }

        return false;
    }
}
