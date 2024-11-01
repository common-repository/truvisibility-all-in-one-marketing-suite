<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://truvisibility.com
 * @since      1.0.0
 *
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/includes
 * @author     TruVisibility, LLC
 */
class TruVisibility_Platform_i18n
{

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain(
            TruVisibility_Platform_Config::PLUGIN_NAME,
            false,
            dirname(plugin_basename(__FILE__), 2) . '/languages/'
        );
    }
}
