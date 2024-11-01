<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://truvisibility.com
 * @since      1.0.0
 *
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/includes
 * @author     TruVisibility, LLC
 */
class TruVisibility_Platform
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      TruVisibility_Platform_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     *
     * @since    1.0.0
     * @access   protected
     * @var      TruVisibility_Platform_Integration_State $integration_state
     */
    protected $integration_state;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('TRUVISIBILITY_PLATFORM_VERSION')) {
            $this->version = TRUVISIBILITY_PLATFORM_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'truvisibility-platform';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_integration_hooks();
        $this->define_rest_api();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - TruVisibility_Platform_Loader. Orchestrates the hooks of the plugin.
     * - TruVisibility_Platform_i18n. Defines internationalization functionality.
     * - TruVisibility_Platform_Admin. Defines all hooks for the admin area.
     * - TruVisibility_Platform_Public. Defines all hooks for the public side of the site.
     * - TruVisibility_Platform_Rest_Api. Defines all endpoints for the plugin.
     * - TruVisibility_Platform_Api_Client. the API client for the plugin.
     * - TruVisibility_Platform_Config. The config of the plugin.
     * - TruVisibility_Platform_Integration_State. The integration state of the plugin.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-truvisibility-platform-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-truvisibility-platform-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-truvisibility-platform-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-truvisibility-platform-public.php';

        /**
         * The class responsible for defining all endpoints for the REST API.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'services/class-truvisibility-platform-rest-api.php';

        /**
         * The class responsible for the API.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'services/class-truvisibility-platform-api-client.php';

        /**
         * The class responsible for the config.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'config/class-truvisibility-platform-config.php';

        /**
         * The class responsible for the integration state.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-truvisibility-platform-integration-state.php';

        $this->loader = new Truvisibility_Platform_Loader();

        $api_client = new TruVisibility_Platform_Api_Client();

        $this->integration_state = new TruVisibility_Platform_Integration_State($api_client);

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the TruVisibility_Platform_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new TruVisibility_Platform_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all hooks related to the admin area functionality of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new TruVisibility_Platform_Admin($this->integration_state);

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'register_admin_menu');
        $this->loader->add_action('admin_init', $plugin_admin, 'activation_redirect');
        $this->loader->add_action('init', $plugin_admin, 'register_form_block');
        $this->loader->add_action('enqueue_block_assets', $plugin_admin, 'register_block_assets');

        $this->loader->add_action('wp_ajax_render_admin_panel', $plugin_admin, 'render_admin_panel');
    }

    /**
     * Register all hooks related to the public-facing functionality of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new TruVisibility_Platform_Public($this->integration_state);

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_shortcode('truvisibility', $plugin_public, 'render_shortcode');
    }

    /**
     * Register all endpoints related to the plugin functionality of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_rest_api()
    {

        $plugin_rest_api = new TruVisibility_Platform_Rest_Api($this->integration_state);

        $this->loader->add_action('rest_api_init', $plugin_rest_api, 'register_endpoints');
    }

    /**
     * Register all hooks related to the plugin functionality of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_integration_hooks()
    {
        $this->loader->add_action('woocommerce_new_order', $this->integration_state, 'hook_add_order', 10, 1);

        $this->loader->add_action('woocommerce_update_order', $this->integration_state, 'hook_update_order', 10, 1);
        $this->loader->add_action('woocommerce_order_refunded', $this->integration_state, 'hook_update_order', 10, 1);

        $this->loader->add_action('user_register', $this->integration_state, 'hook_add_customer', 10, 1);
        $this->loader->add_action('woocommerce_created_customer', $this->integration_state, 'hook_add_customer', 10, 1);
        $this->loader->add_action('woocommerce_new_customer', $this->integration_state, 'hook_add_customer', 10, 1);

        $this->loader->add_action('profile_update', $this->integration_state, 'hook_update_customer', 10, 1);
        $this->loader->add_action('woocommerce_update_customer', $this->integration_state, 'hook_update_customer', 10, 1);
    }

    /**
     * Run the loader to execute all hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    TruVisibility_Platform_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version()
    {
        return $this->version;
    }

    /**
     *
     * @return TruVisibility_Platform_Integration_State
     */
    public function get_integration_state()
    {
        return $this->integration_state;
    }
}
