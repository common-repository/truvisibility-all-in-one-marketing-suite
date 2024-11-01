<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://truvisibility.com
 * @since      1.0.0
 *
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/includes
 * @author     TruVisibility, LLC
 */
class TruVisibility_Platform_Deactivator
{

    public static function deactivate()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'config/class-truvisibility-platform-config.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'services/class-truvisibility-platform-api-client.php';

        $api_client        = new TruVisibility_Platform_Api_Client();
        $integration_state = new TruVisibility_Platform_Integration_State($api_client);
        $integration_state->disconnect_account();

        delete_option(TruVisibility_Platform_Config::CLIENT_ACCESS_TOKEN_OPTION);
        delete_option(TruVisibility_Platform_Config::ACTIVATION_REDIRECT_OPTION);
    }

}
