<?php

/**
 *
 * @link              https://truvisibility.com
 * @since             1.0.0
 * @package           TruVisibility_Platform
 *
 * @wordpress-plugin
 * Plugin Name:       TruVISIBILITY All-In-One Marketing Suite
 * Description:       The TruVISIBILITY Plugin integrates your website with their all-in-one Marketing Suite featuring a CRM, Email Automation, Forms, & Live Chat/Chatbots.
 * Version:           1.1.3
 * Author:            TruVisibility, LLC
 * Author URI:        https://truvisibility.com
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl.html
 * Text Domain:       truvisibility-platform
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!defined('TRUVISIBILITY_PLATFORM_PLUGIN_PATH')) {
    define('TRUVISIBILITY_PLATFORM_PLUGIN_PATH', plugin_dir_path(__FILE__));
}

if (!defined('TRUVISIBILITY_PLATFORM_PLUGIN_URL_PATH')) {
    define('TRUVISIBILITY_PLATFORM_PLUGIN_URL', plugin_dir_url(__FILE__));
}
/**
 * Currently plugin version.
 */
require_once plugin_dir_path(__FILE__) . 'config/class-truvisibility-platform-config.php';
define('TRUVISIBILITY_PLATFORM_VERSION', TruVisibility_Platform_Config::PLUGIN_VERSION);

if (!function_exists('truvisibility_is_woocommerce_active')) {
    /**
     * Checks if WooCommerce plugin is active.
     *
     * @return bool
     */
    function truvisibility_is_woocommerce_active()
    {
        return in_array('woocommerce/woocommerce.php', get_option('active_plugins', array()), true);
    }
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-truvisibility-platform-activator.php
 */
function truvisibility_activate_platform()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-truvisibility-platform-activator.php';
    TruVisibility_Platform_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-truvisibility-platform-deactivator.php
 */
function truvisibility_deactivate_platform()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-truvisibility-platform-deactivator.php';
    TruVisibility_Platform_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'truvisibility_activate_platform');
register_deactivation_hook(__FILE__, 'truvisibility_deactivate_platform');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-truvisibility-platform.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function truvisibility_run_platform()
{

    $plugin = new TruVisibility_Platform();
    $plugin->run();

}
truvisibility_run_platform();
