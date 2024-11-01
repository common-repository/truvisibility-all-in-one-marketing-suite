<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://truvisibility.com
 * @since      1.0.0
 *
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/services
 */

/**
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/services
 * @author     TruVisibility, LLC
 */
class TruVisibility_Platform_Api_Client
{
    /**
     * The crm endpoint of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $crm_endpoint The crm endpoint this plugin.
     */
    private $crm_endpoint;

    /**
     * The forms endpoint of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $forms_endpoint The forms endpoint this plugin.
     */
    private $forms_endpoint;

    /**
     * The chat endpoint of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $chat_endpoint The chat endpoint this plugin.
     */
    private $chat_endpoint;

    /**
     * The integrations endpoint of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $integrations_endpoint The integrations endpoint this plugin.
     */
    private $integrations_endpoint;

    /**
     * The server access token.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $server_access_token The server access token.
     */
    private $server_access_token;

    /**
     * Initialize the class and set its properties.
     *
     * https://crm.truvisibility.com/integration/woocommerce -  to process information about orders, products
     * https://chat.truvisibility.com/api - to save information about plugin connection
     * https://forms.truvisibility.com/api - to get a list of forms
     * https://integrations.truvisibility.com/api - to auth the plugin
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->crm_endpoint          = 'https://crm.' . TruVisibility_Platform_Config::$TvUmbrellaRoot . '/integration/woocommerce';
        $this->chat_endpoint         = 'https://chat.' . TruVisibility_Platform_Config::$TvUmbrellaRoot . '/api';
        $this->forms_endpoint        = 'https://forms.' . TruVisibility_Platform_Config::$TvUmbrellaRoot . '/api';
        $this->integrations_endpoint = 'https://integrations.' . TruVisibility_Platform_Config::$TvUmbrellaRoot;
    }

    public function update_server_access_token($access_token)
    {
        $this->server_access_token = $access_token;
    }

    /**
     * @return bool
     */
    public function is_connected()
    {
        // check connection
        return true;
    }

    /**
     * @return bool
     */
    public function connect_crm($account_id, $access_token)
    {
        try {
            $shop_url = $this->get_site_url();
            $url      = $this->crm_endpoint . '/accountshop/' . $account_id;
            self::make_request($url, 'POST', array(), wp_json_encode(
                array(
                    'accountId'   => $account_id,
                    'shopUrl'     => $shop_url,
                    'accessToken' => $access_token)
            ), false);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function disconnect_crm($account_id)
    {
        if (empty($account_id)) {
            return true;
        }

        try {
            $url = $this->crm_endpoint . '/accountshop/' . $account_id . '?shopUrl=' . $this->get_site_url();
            self::make_request($url, 'DELETE');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function connect_chat($account_id, $access_token)
    {
        try {
            $url = $this->chat_endpoint . '/integration/woocommerce';
            self::make_request($url, 'POST', array(), wp_json_encode(
                array(
                    'shop'        => $this->get_site_url(),
                    'accessToken' => $access_token)
            ), true);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function disconnect_chat($account_id)
    {
        try {
            $url = $this->chat_endpoint . '/integration/woocommerce?shop=' . $this->get_site_url();
            self::make_request($url, 'DELETE', array(), '', true);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function hook_add_order($account_id, $order_id)
    {
        try {
            $url        = $this->crm_endpoint . '/order/' . $account_id . '?shopUrl=' . $this->get_site_url();
            $order_info = new TruVisibility_Platform_Order_Info($order_id);
            self::make_request($url, 'POST', array(), wp_json_encode($order_info));
        } catch (\Exception $e) {
            return;
        }
    }

    public function hook_update_order($account_id, $order_id)
    {
        try {
            $url        = $this->crm_endpoint . '/order/' . $account_id . '?shopUrl=' . $this->get_site_url();
            $order_info = new TruVisibility_Platform_Order_Info($order_id);
            if ($order_info->status == 'draft') {
                return;
            }

            self::make_request($url, 'PUT', array(), wp_json_encode($order_info));
        } catch (\Exception $e) {
            return;
        }
    }

    public function hook_add_customer($account_id, $customer_id)
    {
        try {
            $url           = $this->crm_endpoint . '/customer/' . $account_id . '?shopUrl=' . $this->get_site_url();
            $customer_info = new TruVisibility_Platform_Customer_Info($customer_id, truvisibility_is_woocommerce_active());
            self::make_request($url, 'POST', array(), wp_json_encode($customer_info));
        } catch (\Exception $e) {
            return;
        }
    }

    public function hook_update_customer($account_id, $customer_id)
    {
        try {
            $url           = $this->crm_endpoint . '/customer/' . $account_id . '?shopUrl=' . $this->get_site_url();
            $customer_info = new TruVisibility_Platform_Customer_Info($customer_id, truvisibility_is_woocommerce_active());
            self::make_request($url, 'PUT', array(), wp_json_encode($customer_info));
        } catch (\Exception $e) {
            return;
        }
    }

    public function get_forms_list($account_id)
    {
        try {
            $url      = $this->forms_endpoint . '/public/forms/by-account/' . $account_id;
            $response = self::make_request($url);
            return json_decode($response['body']);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function get_chats_list()
    {
        try {
            $url      = $this->chat_endpoint . '/channels';
            $response = self::make_request($url);
            return json_decode($response['body']);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function get_current_user()
    {
        try {
            $url      = $this->integrations_endpoint . '/auth/me';
            $response = self::make_request($url);
            return json_decode($response['body']);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return object
     *
     * https://integrations.truVisibility.com/auth/refresh - to refresh access token
     *
     */
    public function refresh_access_token($refresh_token)
    {
        try {
            $args     = array('content-type' => 'application/x-www-form-urlencoded');
            $url      = $this->integrations_endpoint . '/auth/refresh?vendor=wp-v1&root=' . home_url();
            $response = self::make_request($url, 'POST', $args, 'refresh_token=' . $refresh_token, false);
            return json_decode($response['body'], true);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Make a HTTP request
     *
     * @param string $url the url.
     * @param string $method The type of HTTP request to make.
     * @param array $headers Any headers that should be added to the default array of headers sent with the request.
     * @param string $body string for the http request body.
     *
     * @return array The response.
     *
     * @throws \Exception For any errors in making the API request and for any errors.
     */
    private function make_request($url, $method = 'GET', $headers = array(), $body = '', $withAuth = true)
    {
        $headers = array_merge(self::get_default_headers($withAuth), $headers);

        $response = wp_remote_request($url, array('method' => $method, 'headers' => $headers, 'body' => $body, 'sslverify' => TruVisibility_Platform_Config::$SslVerify));

        if (is_wp_error($response)) {
            throw new \Exception(\wp_json_encode('WP HTTP Error'), 500);
        }

        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code >= '400') {
            throw new \Exception(esc_html(wp_remote_retrieve_body($response)), esc_html($response_code));
        }

        return $response;
    }

    /**
     * Get the default headers that all requests.
     *
     * @return array.
     */
    private function get_default_headers($withAuth)
    {
        $header = array(
            'timeout'      => 30,
            'content-type' => 'application/json',
        );
        if ($withAuth) {
            $header['Authorization'] = 'Bearer ' . $this->server_access_token;
        };
        return $header;
    }

    private function get_site_url()
    {
        return trailingslashit(get_site_url());
    }
}
