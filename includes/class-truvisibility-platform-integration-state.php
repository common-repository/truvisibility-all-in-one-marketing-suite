<?php

/**
 * Fired during plugin activation
 *
 * @link       https://truvisibility.com
 * @since      1.0.0
 *
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/includes
 * @author     TruVisibility, LLC
 */
class TruVisibility_Platform_Integration_State
{

    /**
     *
     * @since    1.0.0
     * @access   private
     * @var      TruVisibility_Platform_Api_Client    $api_client    API client.
     */
    private $api_client;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      TruVisibility_Platform_Api_Client    $api_client        The API client of this plugin.
     */
    public function __construct($api_client)
    {

        $this->api_client = $api_client;

    }

    /**
     * @return string|null
     */
    public function get_account_id()
    {
        return get_option(TruVisibility_Platform_Config::ACCOUNT_ID_OPTION, null);
    }

    /**
     * @return string|null
     */
    public function get_server_access_token()
    {
        $expires_at = $this->get_server_access_token_expires_at();
        if (!empty($expires_at) && $expires_at - time() < 60/* 1 minute, can probably extend */) {
            $auth_data = $this->refresh_access_token();
            if (!empty($auth_data)) {
                $this->save_auth($auth_data['access_token'], $auth_data['refresh_token'], $auth_data['expires_in']);
                return $auth_data['access_token'];
            }
        }

        return get_option(TruVisibility_Platform_Config::SERVER_ACCESS_TOKEN_OPTION, null);
    }

    /**
     * @return string|null
     */
    public function get_server_refresh_token()
    {
        return get_option(TruVisibility_Platform_Config::SERVER_REFRESH_TOKEN_OPTION, null);
    }

    /**
     * @return string|null
     */
    public function get_server_access_token_expires_at()
    {
        return get_option(TruVisibility_Platform_Config::SERVER_ACCESS_TOKEN_EXPIRES_OPTION, null);
    }

    /**
     * @return string|null
     */
    public function get_client_access_token()
    {
        return get_option(TruVisibility_Platform_Config::CLIENT_ACCESS_TOKEN_OPTION, null);
    }

    /**
     * @return bool
     */
    public function is_gdpr_enabled()
    {
        return boolval(get_option(TruVisibility_Platform_Config::GDPR_ENABLED, false));
    }

    /**
     * @return string|null
     */
    public function get_gdpr_privacy_url()
    {
        return get_option(TruVisibility_Platform_Config::GDPR_PRIVACY_URL, null);
    }

    public function save_auth($access_token, $refresh_token, $expires_in)
    {
        update_option(TruVisibility_Platform_Config::SERVER_ACCESS_TOKEN_OPTION, $access_token);
        update_option(TruVisibility_Platform_Config::SERVER_REFRESH_TOKEN_OPTION, $refresh_token);
        update_option(TruVisibility_Platform_Config::SERVER_ACCESS_TOKEN_EXPIRES_OPTION, time() + $expires_in);

        $this->api_client->update_server_access_token($access_token);

        $account_id = $this->get_account_id();
        if (empty($account_id)) {
            $current_user = $this->get_current_user();
            if (!empty($current_user)) {
                $this->connect_account($current_user->account_id, $access_token);
            }
        }
    }

    public function connect_account($account_id, $access_token)
    {
        $this->disconnect_account();

        update_option(TruVisibility_Platform_Config::ACCOUNT_ID_OPTION, $account_id);
        update_option(TruVisibility_Platform_Config::SERVER_ACCESS_TOKEN_OPTION, $access_token);

        $this->api_client->update_server_access_token($access_token);
        $this->api_client->connect_chat($account_id, $this->get_client_access_token());
        $this->api_client->connect_crm($account_id, $this->get_client_access_token());
    }

    public function disconnect_account()
    {
        $account_id = $this->get_account_id();
        if (!empty($account_id)) {
            $this->api_client->disconnect_chat($account_id);
            $this->api_client->disconnect_crm($account_id);
            $this->api_client->update_server_access_token(null);
        }

        delete_option(TruVisibility_Platform_Config::ACCOUNT_ID_OPTION);
        delete_option(TruVisibility_Platform_Config::SERVER_ACCESS_TOKEN_OPTION);
        delete_option(TruVisibility_Platform_Config::SERVER_REFRESH_TOKEN_OPTION);
        delete_option(TruVisibility_Platform_Config::SERVER_ACCESS_TOKEN_EXPIRES_OPTION);
        delete_option(TruVisibility_Platform_Config::GDPR_ENABLED);
        delete_option(TruVisibility_Platform_Config::GDPR_PRIVACY_URL);
    }

    public function save_gdpr_settings($gdpr_enabled, $gdpr_privacy_url)
    {
        update_option(TruVisibility_Platform_Config::GDPR_ENABLED, boolval($gdpr_enabled));
        update_option(TruVisibility_Platform_Config::GDPR_PRIVACY_URL, $gdpr_privacy_url);
    }

    /**
     * @return bool
     */
    public function is_plugin_integrated()
    {
        return !empty($this->get_server_access_token()) && !empty($this->get_account_id()) && $this->api_client->is_connected();
    }

    /**
     * @return string
     */
    public function get_install_url()
    {
        return 'https://chat.' . TruVisibility_Platform_Config::$TvUmbrellaRoot . '/integration/woocommerce/install';
    }

    /**
     * @return string
     */
    public function get_admin_url()
    {
        return 'https://chat.' . TruVisibility_Platform_Config::$TvUmbrellaRoot . '/app/#/chat-widgets/integration/admin';
    }

    public function hook_add_order($order_id)
    {
        $account_id = $this->get_account_id();
        if (!empty($account_id)) {
            $this->api_client->update_server_access_token($this->get_server_access_token());
            $this->api_client->hook_add_order($account_id, $order_id);
        }
    }

    public function hook_update_order($order_id)
    {
        $account_id = $this->get_account_id();
        if (!empty($account_id)) {
            $this->api_client->update_server_access_token($this->get_server_access_token());
            $this->api_client->hook_update_order($account_id, $order_id);
        }
    }

    public function hook_add_customer($customer_id)
    {
        $account_id = $this->get_account_id();
        if (!empty($account_id)) {
            $this->api_client->update_server_access_token($this->get_server_access_token());
            $this->api_client->hook_add_customer($account_id, $customer_id);
        }
    }

    public function hook_update_customer($customer_id)
    {
        $account_id = $this->get_account_id();
        if (!empty($account_id)) {
            $this->api_client->update_server_access_token($this->get_server_access_token());
            $this->api_client->hook_update_customer($account_id, $customer_id);
        }
    }

    public function get_forms_list()
    {
        $account_id = $this->get_account_id();
        if (empty($account_id)) {
            return new WP_REST_Response('Account is not found', 403);
        }
        $this->api_client->update_server_access_token($this->get_server_access_token());
        return $this->api_client->get_forms_list($account_id);
    }

    public function get_chats_list()
    {
        $account_id = $this->get_account_id();
        if (empty($account_id)) {
            return new WP_REST_Response('Account is not found', 403);
        }
        $this->api_client->update_server_access_token($this->get_server_access_token());
        $chats = $this->api_client->get_chats_list();
        if ($chats) {
            return $chats->items;
        }
        return false;
    }

    public function get_current_user()
    {
        $this->api_client->update_server_access_token($this->get_server_access_token());
        $account_id = $this->get_account_id();
        $current_user = $this->api_client->get_current_user();
        if ($current_user && empty($account_id)) {
            $this->connect_account($current_user->account_id, $this->get_server_access_token());
        }
        return $current_user;
    }

    public function refresh_access_token()
    {
        $refresh_token = $this->get_server_refresh_token();
        if (empty($refresh_token)) {
            return $refresh_token;
        }
        return $this->api_client->refresh_access_token($refresh_token);
    }
}
