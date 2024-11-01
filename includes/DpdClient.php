<?php

namespace WcDPD;

use League\ISO3166\ISO3166;

defined('ABSPATH') || exit;

class DpdClient
{
    public const CUSTOMER_FULL_NAME_KEY = 'dpd_customer_full_name';
    public const CUSTOMER_STREET_KEY = 'dpd_customer_street';
    public const CUSTOMER_HOUSE_NUMBER_KEY = 'dpd_customer_house_number';
    public const CUSTOMER_ZIP_KEY = 'dpd_customer_zip';
    public const CUSTOMER_CITY_KEY = 'dpd_customer_city';
    public const CUSTOMER_COUNTRY_KEY = 'dpd_customer_country';
    public const CUSTOMER_PHONE_KEY = 'dpd_customer_phone';
    public const CUSTOMER_EMAIL_KEY = 'dpd_customer_email';
    public const ORDER_ID_KEY = 'dpd_order_id';
    public const ORDER_PRICE_KEY = 'dpd_order_price';
    public const ORDER_CURRENCY_KEY = 'dpd_order_currency';
    public const ORDER_PAYMENT_METHOD_KEY = 'dpd_order_payment_method';
    public const ORDER_NOTE_KEY = 'dpd_order_note';
    public const DATE_NOW_KEY = 'dpd_date_now';
    public const RESPONSE_SUCCESS_STATUS = 'success';
    public const RESPONSE_ERROR_STATUS = 'error';

    public function __construct()
    {
        $default_settings = DpdExportSettings::getDefaultSettings();

        $this->{self::CUSTOMER_FULL_NAME_KEY} = '';
        $this->{self::CUSTOMER_STREET_KEY} = '';
        $this->{self::CUSTOMER_HOUSE_NUMBER_KEY} = '';
        $this->{self::CUSTOMER_ZIP_KEY} = '';
        $this->{self::CUSTOMER_CITY_KEY} = '';
        $this->{self::CUSTOMER_COUNTRY_KEY} = '';
        $this->{self::CUSTOMER_PHONE_KEY} = '';
        $this->{self::CUSTOMER_EMAIL_KEY} = '';
        $this->{self::ORDER_ID_KEY} = '';
        $this->{self::ORDER_PAYMENT_METHOD_KEY} = '';
        $this->{self::ORDER_PRICE_KEY} = '';
        $this->{self::ORDER_CURRENCY_KEY} = '';
        $this->{self::ORDER_NOTE_KEY} = '';
        $this->{self::DATE_NOW_KEY} = '';

        $this->{DpdExportSettings::API_KEY_OPTION_KEY} = isset($default_settings[DpdExportSettings::API_KEY_OPTION_KEY]) ? $default_settings[DpdExportSettings::API_KEY_OPTION_KEY] : null;
        $this->{DpdExportSettings::EMAIL_OPTION_KEY} = isset($default_settings[DpdExportSettings::EMAIL_OPTION_KEY]) ? $default_settings[DpdExportSettings::EMAIL_OPTION_KEY] : null;
        $this->{DpdExportSettings::DELIS_ID_OPTION_KEY} = isset($default_settings[DpdExportSettings::DELIS_ID_OPTION_KEY]) ? $default_settings[DpdExportSettings::DELIS_ID_OPTION_KEY] : null;
        $this->{DpdExportSettings::ADDRESS_ID_OPTION_KEY} = isset($default_settings[DpdExportSettings::ADDRESS_ID_OPTION_KEY]) ? $default_settings[DpdExportSettings::ADDRESS_ID_OPTION_KEY] : null;
        $this->{DpdExportSettings::BANK_ID_OPTION_KEY} = isset($default_settings[DpdExportSettings::BANK_ID_OPTION_KEY]) ? $default_settings[DpdExportSettings::BANK_ID_OPTION_KEY] : null;
        $this->{DpdExportSettings::SHIPPING_OPTION_KEY} = isset($default_settings[DpdExportSettings::SHIPPING_OPTION_KEY]) ? $default_settings[DpdExportSettings::SHIPPING_OPTION_KEY] : 0;
        $this->{DpdExportSettings::NOTIFICATION_OPTION_KEY} = isset($default_settings[DpdExportSettings::NOTIFICATION_OPTION_KEY]) ? $default_settings[DpdExportSettings::NOTIFICATION_OPTION_KEY] : 'no';
    }

    /**
     * Get request data
     *
     * @return array
     */
    public function getRequestData()
    {
        $notification_data = [
            'notification' => [
                0 => [
                    'destination' => !$this->{self::CUSTOMER_EMAIL_KEY} ? $this->{self::CUSTOMER_PHONE_KEY} : $this->{self::CUSTOMER_EMAIL_KEY},
                    'type'        => '1',
                    'rule'        => '1',
                ]
            ]
        ];

        $add_notification_data = $this->{DpdExportSettings::NOTIFICATION_OPTION_KEY} == 'yes' ? true : false;

        // Check if notification is required for the shipment type
        if (DpdExportSettings::isNotificationRequired($this->{DpdExportSettings::SHIPPING_OPTION_KEY})) {
            $add_notification_data = true;
        }

        $services = [
            'notifications' => $add_notification_data ? $notification_data : '',
        ];

        $cod_data = [
            'bankAccount' => [
                'id' =>  $this->{DpdExportSettings::BANK_ID_OPTION_KEY},
            ],
            'paymentMethod'  => 0, // Cash only
            'variableSymbol' => $this->{self::ORDER_ID_KEY},
            'amount'         => $this->{self::ORDER_PRICE_KEY},
            'currency'       => $this->{self::ORDER_CURRENCY_KEY},
        ];

        $add_cod_data = $this->{self::ORDER_PAYMENT_METHOD_KEY} == 'cod' ? true : false;

        if ($add_cod_data) {
            $services['cod'] = $cod_data;
        }

        $data = [
            'jsonrpc' => '2.0',
            'method' => 'create',
            'params' => [
                'DPDSecurity' => [
                    'SecurityToken' => [
                        'ClientKey' => $this->{DpdExportSettings::API_KEY_OPTION_KEY},
                        'Email' =>  $this->{DpdExportSettings::EMAIL_OPTION_KEY},
                    ],
                ],
                'shipment' => [
                    0 => [
                        'delisId' => $this->{DpdExportSettings::DELIS_ID_OPTION_KEY},
                        'reference' => $this->{self::ORDER_ID_KEY},
                        'note' => $this->{self::ORDER_NOTE_KEY},
                        'product' => $this->{DpdExportSettings::SHIPPING_OPTION_KEY},
                        'pickup' => [
                            'date' => $this->{self::DATE_NOW_KEY},
                        ],
                        'addressSender' => [
                            'id' => $this->{DpdExportSettings::ADDRESS_ID_OPTION_KEY},
                        ],
                        'addressRecipient' => [
                            'type' => 'b2b',
                            'name' => $this->{self::CUSTOMER_FULL_NAME_KEY},
                            'street' => $this->{self::CUSTOMER_STREET_KEY},
                            'houseNumber' => $this->{self::CUSTOMER_HOUSE_NUMBER_KEY},
                            'zip' => $this->{self::CUSTOMER_ZIP_KEY},
                            'country' => $this->{self::CUSTOMER_COUNTRY_KEY},
                            'city' => $this->{self::CUSTOMER_CITY_KEY},
                            'phone' => $this->{self::CUSTOMER_PHONE_KEY} ,
                            'email' => $this->{self::CUSTOMER_EMAIL_KEY},
                            'note' => $this->{self::ORDER_NOTE_KEY},
                        ],
                        'parcels' => [
                            'parcel' => [
                                0 => [
                                    'weight' => 3,
                                ],
                            ],
                        ],
                        'services' => $services,
                    ],
                ],
                'id' => $this->{self::ORDER_ID_KEY},
            ]
        ];

        return $data;
    }

    /**
     * Set request recipient data
     *
     * @param array $data
     *
     * @return void
     */
    public function setAddressRecipient($data = [])
    {
        $this->{self::CUSTOMER_FULL_NAME_KEY} = isset($data[self::CUSTOMER_FULL_NAME_KEY]) && !empty($data[self::CUSTOMER_FULL_NAME_KEY]) ? $data[self::CUSTOMER_FULL_NAME_KEY] : '';
        $this->{self::CUSTOMER_STREET_KEY} = isset($data[self::CUSTOMER_STREET_KEY]) && !empty($data[self::CUSTOMER_STREET_KEY]) ? $data[self::CUSTOMER_STREET_KEY] : '';
        $this->{self::CUSTOMER_HOUSE_NUMBER_KEY} = isset($data[self::CUSTOMER_HOUSE_NUMBER_KEY]) && !empty($data[self::CUSTOMER_HOUSE_NUMBER_KEY]) ? $data[self::CUSTOMER_HOUSE_NUMBER_KEY] : '';
        $this->{self::CUSTOMER_ZIP_KEY} = isset($data[self::CUSTOMER_ZIP_KEY]) && !empty($data[self::CUSTOMER_ZIP_KEY]) ? $data[self::CUSTOMER_ZIP_KEY] : '';
        $this->{self::CUSTOMER_CITY_KEY} = isset($data[self::CUSTOMER_CITY_KEY]) && !empty($data[self::CUSTOMER_CITY_KEY]) ? $data[self::CUSTOMER_CITY_KEY] : '';
        $this->{self::CUSTOMER_PHONE_KEY} = isset($data[self::CUSTOMER_PHONE_KEY]) && !empty($data[self::CUSTOMER_PHONE_KEY]) ? $data[self::CUSTOMER_PHONE_KEY] : '';
        $this->{self::CUSTOMER_EMAIL_KEY} = isset($data[self::CUSTOMER_EMAIL_KEY]) && !empty($data[self::CUSTOMER_EMAIL_KEY]) ? $data[self::CUSTOMER_EMAIL_KEY] : '';
        $this->{self::ORDER_NOTE_KEY} = isset($data[self::ORDER_NOTE_KEY]) && !empty($data[self::ORDER_NOTE_KEY]) ? $data[self::ORDER_NOTE_KEY] : '';
        $this->{self::ORDER_ID_KEY} = isset($data[self::ORDER_ID_KEY]) && !empty($data[self::ORDER_ID_KEY]) ? $data[self::ORDER_ID_KEY] : '';
        $this->{self::ORDER_PRICE_KEY} = isset($data[self::ORDER_PRICE_KEY]) && !empty($data[self::ORDER_PRICE_KEY]) ? $data[self::ORDER_PRICE_KEY] : '';
        $this->{self::ORDER_CURRENCY_KEY} = isset($data[self::ORDER_CURRENCY_KEY]) && !empty($data[self::ORDER_CURRENCY_KEY]) ? $data[self::ORDER_CURRENCY_KEY] : 'EUR';
        $this->{self::ORDER_PAYMENT_METHOD_KEY} = isset($data[self::ORDER_PAYMENT_METHOD_KEY]) && !empty($data[self::ORDER_PAYMENT_METHOD_KEY]) ? $data[self::ORDER_PAYMENT_METHOD_KEY] : '';
        $this->{self::DATE_NOW_KEY} = isset($data[self::DATE_NOW_KEY]) && !empty($data[self::DATE_NOW_KEY]) ? $data[self::DATE_NOW_KEY] : '';

        $this->{DpdExportSettings::SHIPPING_OPTION_KEY} = !empty($data[DpdExportSettings::SHIPPING_OPTION_KEY]) ? $data[DpdExportSettings::SHIPPING_OPTION_KEY] : $this->{DpdExportSettings::SHIPPING_OPTION_KEY};

        // Add country ISO code
        $country = isset($data[self::CUSTOMER_COUNTRY_KEY]) && !empty($data[self::CUSTOMER_COUNTRY_KEY]) ? $data[self::CUSTOMER_COUNTRY_KEY] : '';
        $country_data = (new ISO3166())->alpha2($country);
        $this->{self::CUSTOMER_COUNTRY_KEY} = !empty($country_data['numeric']) ? (int) $country_data['numeric'] : '';
    }

    /**
     * Submit request to DPD
     *
     * @param array $data
     *
     * @return array
     *
     * @throws \Exception
     */
    public function export($data = [])
    {
        $data = $this->setAddressRecipient($data);
        $data = $this->getRequestData();

        if (empty($data)) {
            throw new \Exception('No data', 400);
        }

        $url = "https://api.dpd.sk/shipment/json";

        $response = \wp_remote_post($url, [
            'body' => json_encode($data),
        ]);

        $response_body = json_decode(\wp_remote_retrieve_body($response), true);

        wc_dpd_log('Request log', [
            'data' => $data,
            'response' => $response,
        ]);

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            throw new \Exception(sprintf(__('Something went wrong: %s', 'wc-dpd'), $response->get_error_message()));
        }

        if (empty($response)) {
            throw new \Exception(__('Something went wrong! Response is empty!', 'wc-dpd'), 400);
        }

        if (isset($response_body['error'])) {
            $error_message = isset($response_body['error']['message']) ? $response_body['error']['message'] : '';
            $error_code = isset($response_body['error']['code']) ? (int) $response_body['error']['code'] : '';

            if (!$error_message) {
                $error_message = isset($response_body['message']) ? $response_body['message'] : '';
            }

            if (!$error_code) {
                $error_code = isset($response_body['code']) ? (int) $response_body['code'] : '';
            }

            $error_message = apply_filters('wc_dpd_client_error_message', $error_message);

            throw new \Exception(esc_html($error_message), $error_code ? $error_code : 400);
        }

        $success = isset($response_body['result']['result'][0]['success']) ? (bool) $response_body['result']['result'][0]['success'] : false;

        if (!$success) {
            $error_message = isset($response_body['result']['result'][0]['messages'][0]['value']) ? $response_body['result']['result'][0]['messages'][0]['value'] : null;

            if (!$error_message) {
                $error_message = isset($response_body['result']['result'][0]['messages'][0]) ? $response_body['result']['result'][0]['messages'][0] : __('Something went wrong!', 'wc-dpd');
            }

            $error_message = apply_filters('wc_dpd_client_error_message', $error_message);

            throw new \Exception(esc_html($error_message), 400);
        }

        $label = isset($response_body['result']['result'][0]['label']) ? esc_html($response_body['result']['result'][0]['label']) : '';

        return [
            Order::EXPORT_STATUS_META_KEY => self::RESPONSE_SUCCESS_STATUS,
            Order::EXPORT_LABEL_URL_META_KEY => $label
        ];
    }
}
