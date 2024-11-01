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
class TruVisibility_Platform_Activator
{

    public static function activate()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'config/class-truvisibility-platform-config.php';

        update_option(TruVisibility_Platform_Config::CLIENT_ACCESS_TOKEN_OPTION, md5(uniqid(wp_rand(), true)));
        add_option(TruVisibility_Platform_Config::ACTIVATION_REDIRECT_OPTION, true);
    }

}
