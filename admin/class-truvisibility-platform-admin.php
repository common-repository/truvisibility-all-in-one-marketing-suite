<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://truvisibility.com
 * @since      1.0.0
 *
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/admin
 * @author     TruVisibility, LLC
 */
class TruVisibility_Platform_Admin
{
    const ADMIN_CSS            = 'truvisibility_admin_css';
    const ADMIN_JS             = 'truvisibility_admin_js';
    const CONFIG_JS            = 'truvisibility_config_js';
    const FORMS_JS             = 'trv_forms_script';
    const TRUVISIBILITY_CONFIG = 'trv_config';
    const FORMS_BLOCK_JS       = 'truvisibility-platform-form-block-js';
    const POPPER_JS            = 'truvisibility_admin_popper_js';
    const BOOTSTRAP_JS         = 'truvisibility_admin_boostrap_js';
    const BOOTSTRAP_CSS        = 'truvisibility_admin_boostrap_css';

    /**
     *
     * @since    1.0.0
     * @access   private
     * @var      TruVisibility_Platform_Integration_State $integration_state Contains integration state.
     */
    private $integration_state;

    /**
     * Initialize the class and set its properties.
     *
     * @param TruVisibility_Platform_Integration_State $integration_state The integration state of this plugin.
     * @since    1.0.0
     */
    public function __construct($integration_state)
    {
        $this->integration_state = $integration_state;

        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/models/class-truvisibility-platform-connectable-item-action-definition.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/models/class-truvisibility-platform-connectable-item-action.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/models/class-truvisibility-platform-connectable-item.php';
    }

    /**
     * Register the block for editor.
     *
     * @since    1.0.0
     */
    public function register_form_block()
    {
        if (is_admin()) {
            wp_enqueue_script(self::FORMS_JS, plugin_dir_url(__FILE__) . 'js/truvisibility-platform-admin-editor.js', array('jquery'), TruVisibility_Platform_Config::PLUGIN_VERSION, false);
            wp_localize_script(self::FORMS_JS, self::TRUVISIBILITY_CONFIG, $this->get_config());

            register_block_type(TRUVISIBILITY_PLATFORM_PLUGIN_PATH . 'build/');

            if (function_exists('wp_set_script_translations')) {
                wp_set_script_translations(
                    'truvisibility-platform-form-block-editor-script',
                    TruVisibility_Platform_Config::PLUGIN_NAME,
                    TRUVISIBILITY_PLATFORM_PLUGIN_PATH . 'languages/');
            }
        }
    }

    /**
     * Function for `enqueue_block_assets` action-hook.
     *
     * https://forms.truVisibility.com/static/scripts/embed.js - javascript for form operation
     *
     * @return void
     */
    public function register_block_assets()
    {
        if (is_admin()) {
            wp_enqueue_script(self::FORMS_BLOCK_JS, 'https://forms.' . TruVisibility_Platform_Config::$TvUmbrellaRoot . '/static/scripts/embed.js', array(), TruVisibility_Platform_Config::PLUGIN_VERSION, false);
        }
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_register_style(self::ADMIN_CSS, plugin_dir_url(__FILE__) . 'css/truvisibility-platform-admin.min.css', array(), TruVisibility_Platform_Config::PLUGIN_VERSION);
        wp_register_style(self::BOOTSTRAP_CSS, 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css', array(), TruVisibility_Platform_Config::PLUGIN_VERSION);
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_register_script(self::POPPER_JS, 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js', array('jquery'), TruVisibility_Platform_Config::PLUGIN_VERSION, true);
        wp_register_script(self::BOOTSTRAP_JS, 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(self::POPPER_JS), TruVisibility_Platform_Config::PLUGIN_VERSION, true);
        wp_register_script(self::ADMIN_JS, plugin_dir_url(__FILE__) . 'js/truvisibility-platform-admin.js', array(self::BOOTSTRAP_JS), TruVisibility_Platform_Config::PLUGIN_VERSION, true);
        if (is_admin()) {
            wp_localize_script(self::ADMIN_JS, self::TRUVISIBILITY_CONFIG, $this->get_config());
        }
    }

    public function register_admin_menu()
    {
        $logo_url = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0yMSAxMkMyMSAxNi45NzA2IDE2Ljk3MDYgMjEgMTIgMjFDMTAuMzIxNyAyMSA4Ljc1MDc4IDIwLjU0MDYgNy40MDU5OCAxOS43NDA4TDMgMjAuOTk5N0w0LjI1ODkgMTYuNTkzNUMzLjQ1OTI0IDE1LjI0ODggMyAxMy42NzggMyAxMkMzIDcuMDI5NDQgNy4wMjk0NCAzIDEyIDNDMTYuOTcwNiAzIDIxIDcuMDI5NDQgMjEgMTJaTTYuOTYgMTJDNi45NiAxNC43ODM1IDkuMjE2NDkgMTcuMDQgMTIgMTcuMDRDMTQuNzgzNSAxNy4wNCAxNy4wNCAxNC43ODM1IDE3LjA0IDEyQzE3LjA0IDkuMjE2NDkgMTQuNzgzNSA2Ljk2IDEyIDYuOTZDOS4yMTY0OSA2Ljk2IDYuOTYgOS4yMTY0OSA2Ljk2IDEyWk0xNC4wMDAyIDEyLjAwMDNDMTQuMDAwMiAxMy4xMDQ5IDEzLjEwNDcgMTQuMDAwMyAxMi4wMDAyIDE0LjAwMDNDMTAuODk1NiAxNC4wMDAzIDEwLjAwMDIgMTMuMTA0OSAxMC4wMDAyIDEyLjAwMDNDMTAuMDAwMiAxMC44OTU4IDEwLjg5NTYgMTAuMDAwMyAxMi4wMDAyIDEwLjAwMDNDMTMuMTA0NyAxMC4wMDAzIDE0LjAwMDIgMTAuODk1OCAxNC4wMDAyIDEyLjAwMDNaIiBmaWxsPSIjNkE3MTc5Ii8+Cjwvc3ZnPgo=';
        add_menu_page(__('TruVISIBILITY', 'truvisibility-platform'),
            __('TruVISIBILITY', 'truvisibility-platform'), 'manage_options',
            TruVisibility_Platform_Config::PLUGIN_NAME,
            array($this, 'render_admin_page'),
            $logo_url,
            25.1// After 25 - Comments
        );
    }

    /**
     * Renders admin page.
     *
     * https://integrations.truVisibility.com/app - the admin panel
     *
     */
    public function render_admin_page()
    {
        $this->enqueue_admin_assets();
        $this->include_admin_view();
    }

    /**
     *
     * @return TruVisibility_Platform_Integration_State
     */
    public function get_integration_state()
    {
        return $this->integration_state;
    }

    /**
     * Enqueue the assets needed in the admin section.
     */
    private function enqueue_admin_assets()
    {
        wp_enqueue_script(self::POPPER_JS);
        wp_enqueue_script(self::BOOTSTRAP_JS);
        wp_enqueue_style(self::BOOTSTRAP_CSS);

        wp_enqueue_style(self::ADMIN_CSS);
        wp_enqueue_script(self::ADMIN_JS);
    }

    /**
     * Returns plugin config for the javascript.
     *
     * https://forms.truVisibility.com/static/scripts/embed.js - javascript for form operation
     *
     */
    private function get_config()
    {
        $access_token = $this->integration_state->get_server_access_token();

        return array(
            'pluginPath'     => TRUVISIBILITY_PLATFORM_PLUGIN_URL,
            'accessToken'    => $access_token,
            'adminUrl'       => admin_url(),
            'rootUrl'        => home_url(),
            'title'          => $this->get_site_title(),
            'apiUrl'         => get_rest_url(),
            'isConnected'    => $this->integration_state->is_plugin_integrated(),
            'apiNonce'       => wp_create_nonce('wp_rest'),
            'formsScript'    => 'https://forms.' . TruVisibility_Platform_Config::$TvUmbrellaRoot . '/static/scripts/embed.js',
            'authLink'       => 'https://integrations.' . TruVisibility_Platform_Config::$TvUmbrellaRoot . '/auth/redirect?root=' . home_url() . '&vendor=wp-v2',
            'language'       => get_bloginfo("language"),
            'gdprEnabled'    => $this->integration_state->is_gdpr_enabled(),
            'gdprPrivacyUrl' => $this->integration_state->get_gdpr_privacy_url()
        );
    }

    private function get_site_title()
    {
        $site_title = get_bloginfo('name');
        if ($site_title === '') {
            $site_title = get_bloginfo('wpurl');
        }

        if ($site_title === '') {
            $site_title = get_bloginfo('url');
        }

        return $site_title;
    }

    public static function view($name, array $args = array())
    {
        $args = apply_filters('truvisibiliry_platform_view_arguments', $args, $name);

        foreach ($args as $key => $val) {
            $$key = $val;
        }

        load_plugin_textdomain('truvisibiliry_platform');

        $file = dirname(__FILE__) . '/views/' . $name . '.php';

        include $file;
    }

    public function render_admin_panel()
    {
        $this->include_admin_view();
        wp_die();
    }

    private function include_admin_view()
    {
        $current_user = $this->integration_state->get_current_user();
        if ($current_user) {
            TruVisibility_Platform_Admin::view('main', array(
                'title'          => $this->get_site_title(),
                'user'           => $current_user,
                'chats'          => $this->get_chats_list(),
                'forms'          => $this->get_forms_list(),
                'gdprEnabled'    => $this->integration_state->is_gdpr_enabled(),
                'gdprPrivacyUrl' => $this->integration_state->get_gdpr_privacy_url(),
            ));
        } else {
            TruVisibility_Platform_Admin::view('unauthorized', array());
        }
    }

    private function get_chats_list()
    {
        $chats = [];
        foreach ($this->integration_state->get_chats_list() as $chat) {
            $chats[] = new TruVisibility_Platform_Connectable_Item(
                $chat->id,
                $chat->name,
                $chat->enabled ? 'active' : 'disabled',
                array(
                    new TruVisibility_Platform_Connectable_Item_Action(
                        'chatEmbeddedGenerator("' . $chat->id . '")',
                        new TruVisibility_Platform_Connectable_Item_Action_Definition('code', __('Embedded Widget', 'truvisibility-platform'))),
                    new TruVisibility_Platform_Connectable_Item_Action(
                        'chatPopupGenerator("' . $chat->id . '")',
                        new TruVisibility_Platform_Connectable_Item_Action_Definition('copy', __('Copy', 'truvisibility-platform'))),
                )
            );
        };

        return $chats;
    }

    private function get_forms_list()
    {
        $forms = [];
        foreach ($this->integration_state->get_forms_list() as $form) {
            if ($form->status == 'Draft') {
                continue;
            }

            $forms[] = new TruVisibility_Platform_Connectable_Item(
                $form->id,
                $form->title,
                $form->status != 'Published' ? 'pending' : 'active',
                array(
                    new TruVisibility_Platform_Connectable_Item_Action(
                        'formsGenerator("' . $form->id . '")',
                        new TruVisibility_Platform_Connectable_Item_Action_Definition('copy', __('Copy', 'truvisibility-platform'))),
                )
            );
        }

        return $forms;
    }

    public function activation_redirect($plugin)
    {
        if (get_option(TruVisibility_Platform_Config::ACTIVATION_REDIRECT_OPTION, false)) {
            delete_option(TruVisibility_Platform_Config::ACTIVATION_REDIRECT_OPTION);
            wp_redirect(admin_url('admin.php?page=' . TruVisibility_Platform_Config::PLUGIN_NAME));
        }
    }
}
