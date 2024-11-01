<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://truvisibility.com
 * @since      1.0.0
 *
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/public
 * @author     TruVisibility, LLC
 */
class TruVisibility_Platform_Public {

	/**
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      TruVisibility_Platform_Integration_State    $integration_state    Contains integration state.
	 */
	private $integration_state;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      TruVisibility_Platform_Integration_State		$integration_state      The integration state of this plugin.
	 */
	public function __construct( $integration_state ) {

		$this->integration_state = $integration_state;

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/utils/class-truvisibility-platform-shortcode-utils.php';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in TruVisibility_Platform_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The TruVisibility_Platform_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( TruVisibility_Platform_Config::PLUGIN_NAME, plugin_dir_url( __FILE__ ) . 'css/truvisibility-platform-public.css', array(), TruVisibility_Platform_Config::PLUGIN_VERSION, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in TruVisibility_Platform_Loader as all hooks are defined
		 * in that particular class.
		 *
		 * The TruVisibility_Platform_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( TruVisibility_Platform_Config::PLUGIN_NAME, plugin_dir_url( __FILE__ ) . 'js/truvisibility-platform-public.js', array( 'jquery' ), TruVisibility_Platform_Config::PLUGIN_VERSION, false );
		wp_localize_script( TruVisibility_Platform_Config::PLUGIN_NAME, 'truchat_script_vars', array(
			'woo_cart_url' => $this->get_woo_cart_url( ),
			'is_user_logged_in' => is_user_logged_in() ? 'true' : 'false',
			'customer_id' => get_current_user_id()
			)
		);
	}

	/**
	 * Parse shortcodes
	 *
	 * @param array $attributes Shortcode attributes.
	 */
	public function render_shortcode( $attributes ) {
		return TruVisibility_Platform_Shortcode_Utils::render_shortcode( $attributes );
	}

	/**
	 * Get cart url
	 *
	 * @return string
	 */
	public function get_woo_cart_url(): string {
		if ( ! function_exists( 'wc_get_page_id' ) ) {
			return "";
		}

		$cart_url = get_permalink( wc_get_page_id( 'cart' ) );
		if ( ! $cart_url ) {
			return "";
		}
		return $cart_url;
	}
}
