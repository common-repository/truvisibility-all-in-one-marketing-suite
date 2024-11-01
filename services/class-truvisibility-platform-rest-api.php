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
class TruVisibility_Platform_Rest_Api
{

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

        require_once plugin_dir_path(dirname(__FILE__)) . 'services/class-truvisibility-platform-product-info.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'services/class-truvisibility-platform-order-info.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'services/class-truvisibility-platform-customer-info.php';
    }

    /**
     * Register the endpoints for the plugin.
     *
     * @since    1.0.0
     */

    public function register_endpoints()
    {
        register_rest_route('truvisibility/v1', '/user', array(
            'methods'             => WP_REST_Server::READABLE,
            'permission_callback' => array($this, 'privileged_permission_callback'),
            'callback'            => array($this, 'get_user_info'),
        )
        );

        register_rest_route('truvisibility/v1', '/platform/(?P<platform>.+)', array(
            'methods'             => WP_REST_Server::READABLE,
            'permission_callback' => array($this, 'privileged_permission_callback'),
            'callback'            => array($this, 'check_platform'),
            'args'                => array(
                'platform' => array(
                    'required' => true,
                    'type'     => 'string',
                ),
            ),
        )
        );

        register_rest_route('truvisibility/v1', '/customers', array(
            'methods'             => WP_REST_Server::READABLE,
            'permission_callback' => array($this, 'privileged_permission_callback'),
            'callback'            => array($this, 'get_customers'),
        )
        );

        register_rest_route('truvisibility/v1', '/orders', array(
            'methods'             => WP_REST_Server::READABLE,
            'permission_callback' => array($this, 'privileged_permission_callback'),
            'callback'            => array($this, 'get_orders'),
        )
        );

        register_rest_route('truvisibility/v1', '/products', array(
            'methods'             => WP_REST_Server::READABLE,
            'permission_callback' => array($this, 'privileged_permission_callback'),
            'callback'            => array($this, 'get_products'),
        )
        );

        register_rest_route('truvisibility/v1', '/products/search', array(
            'methods'             => WP_REST_Server::READABLE,
            'permission_callback' => array($this, 'privileged_permission_callback'),
            'callback'            => array($this, 'search_products'),
            'args'                => array(
                'searchterm' => array(
                    'required' => true,
                    'type'     => 'string',
                ),
                'page'       => array(
                    'required' => false,
                    'type'     => 'int',
                    'default'  => 1,
                ),
                'pagesize'   => array(
                    'required' => false,
                    'type'     => 'int',
                    'default'  => 5,
                ),
            ),
        )
        );

        register_rest_route('truvisibility/v1', '/products/(?P<id>\d+)', array(
            'methods'             => WP_REST_Server::READABLE,
            'permission_callback' => array($this, 'privileged_permission_callback'),
            'callback'            => array($this, 'get_product'),
            'args'                => array(
                'id' => array(
                    'required' => true,
                    'type'     => 'int',
                ),
            ),
        )
        );

        register_rest_route('truvisibility/v1', '/orders/by-customer/(?P<id>\d+)', array(
            'methods'             => WP_REST_Server::READABLE,
            'permission_callback' => array($this, 'privileged_permission_callback'),
            'callback'            => array($this, 'get_orders_by_customer'),
            'args'                => array(
                'id' => array(
                    'required' => true,
                    'type'     => 'int',
                ),
            ),
        )
        );

        register_rest_route('truvisibility/v1', '/forms', array(
            'methods'             => WP_REST_Server::READABLE,
            'permission_callback' => array($this, 'verify_edit_permissions'),
            'callback'            => array($this, 'get_forms_list'),
        )
        );

        register_rest_route('truvisibility/v1', '/chats', array(
            'methods'             => WP_REST_Server::READABLE,
            'permission_callback' => array($this, 'verify_edit_permissions'),
            'callback'            => array($this, 'get_chats_list'),
        )
        );

        register_rest_route('truvisibility/v1', '/auth', array(
            'methods'             => 'POST',
            'permission_callback' => '__return_true',
            'callback'            => array($this, 'save_auth'),
        )
        );

        register_rest_route('truvisibility/v1', '/reset-auth', array(
            'methods'             => 'POST',
            'permission_callback' => array($this, 'reset_auth_permissions'),
            'callback'            => array($this, 'reset_auth'),
        )
        );

        register_rest_route('truvisibility/v1', '/set-account', array(
            'methods'             => 'POST',
            'permission_callback' => array($this, 'reset_auth_permissions'),
            'callback'            => array($this, 'set_account'),
        )
        );

        register_rest_route('truvisibility/v1', '/get-gdpr-settings', array(
            'methods'             => 'GET',
            'permission_callback' => array($this, 'reset_auth_permissions'),
            'callback'            => array($this, 'get_gdpr_settings'),
        )
        );

        register_rest_route('truvisibility/v1', '/save-gdpr-settings', array(
            'methods'             => 'POST',
            'permission_callback' => array($this, 'reset_auth_permissions'),
            'callback'            => array($this, 'save_gdpr_settings'),
        )
        );
    }

    public function get_forms_list()
    {
        return $this->integration_state->get_forms_list();
    }

    public function get_chats_list()
    {
        return $this->integration_state->get_chats_list();
    }

    public function get_user_info($request)
    {
        $user = get_user_by('email', $request['email']);
        if (!$user) {
            return false;
        }
        $phone = get_user_meta($user->ID, 'phone_number', true);
        return array(
            "first_name" => $user->user_firstname,
            "last_name"  => $user->user_lastname,
            "nickname"   => $user->display_name,
            "phone"      => $phone,
        );
    }

    public function privileged_permission_callback($request)
    {
        return $request['access_token'] == $this->integration_state->get_client_access_token();
    }

    public function verify_edit_permissions()
    {
        return current_user_can('edit_posts');
    }

    public function get_customers($request)
    {
        list($page, $page_size) = $this->extract_paging_params($request);

        $query = new WP_User_Query(array(
            'number'  => $page_size,
            'offset'  => ($page - 1) * $page_size,
            'orderby' => 'ID',
            'order'   => 'ASC',
            'fields'  => 'ID',
        ));

        $total_users    = $query->get_total();
        $customers      = array();
        $is_wc_customer = truvisibility_is_woocommerce_active();
        foreach ($query->get_results() as $customer_id) {
            $customers[] = new TruVisibility_Platform_Customer_Info($customer_id, $is_wc_customer);
        }

        $result             = array();
        $result["items"]    = $customers;
        $result["page"]     = $page;
        $result["pagesize"] = $page_size;
        $result["total"]    = $total_users;

        return $result;
    }

    public function get_orders($request)
    {
        list($page, $page_size) = $this->extract_paging_params($request);

        $orders = array();
        $total  = 0;

        $result             = array();
        $result["items"]    = array();
        $result["page"]     = $page;
        $result["pagesize"] = $page_size;
        $result["total"]    = 0;

        if (!truvisibility_is_woocommerce_active()) {
            return $result;
        }

        $query = new WC_Order_Query(array(
            'paginate' => true,
            'limit'    => $page_size,
            'page'     => $page,
            'orderby'  => 'ID',
            'order'    => 'ASC',
            'return'   => 'ids',
        ));

        $query_result = $query->get_orders();
        $total        = $query_result->total;
        $orders       = array();
        foreach ($query_result->orders as $order_id) {
            $orders[] = new TruVisibility_Platform_Order_Info($order_id);
        }

        $result["items"] = $orders;
        $result["total"] = $total;

        return $result;
    }

    public function get_products()
    {
        if (!truvisibility_is_woocommerce_active()) {
            return array();
        }

        $query = new WC_Product_Query(array(
            'status' => 'publish',
            'return' => 'ids',
        ));

        $products = array();
        foreach ($query->get_products() as $product_id) {
            $products[] = new TruVisibility_Platform_Product_Info($product_id);
        }

        return $products;
    }

    public function search_products($request)
    {
        $search                 = wc_clean(wp_unslash($request->get_param('searchterm')));
        list($page, $page_size) = $this->extract_paging_params($request);

        $result             = array();
        $result["total"]    = 0;
        $result["items"]    = array();
        $result["page"]     = $page;
        $result["pagesize"] = $page_size;

        if (!truvisibility_is_woocommerce_active()) {
            return $result;
        }

        if (empty($search)) {
            return $result;
        }

        global $wpdb;

        $search_condition = "%" . $wpdb->esc_like($search) . "%";

        $ids = $wpdb->get_results($wpdb->prepare("
            SELECT posts.ID FROM $wpdb->posts as posts
            LEFT JOIN $wpdb->term_relationships as term_relationships ON posts.ID = term_relationships.object_id
            LEFT JOIN $wpdb->postmeta as postmeta ON posts.ID = postmeta.post_id
            WHERE 1=1 AND posts.post_type = 'product' AND term_relationships.term_taxonomy_id IN (2,3,4,5)
            AND posts.post_status = 'publish'
            AND ((posts.post_title LIKE %s) OR (postmeta.meta_key = '_sku' AND postmeta.meta_value LIKE %s))
            AND posts.post_password = ''
            GROUP BY posts.ID
            ORDER BY posts.post_date DESC
            ", $search_condition, $search_condition));
        $products = array();
        foreach (array_slice($ids, ($page - 1) * $page_size, $page_size) as $product_id) {
            $products[] = new TruVisibility_Platform_Product_Info($product_id->ID);
        }

        $result["total"] = count($ids);
        $result["items"] = $products;

        return $result;
    }

    public function get_product($request)
    {
        if (!truvisibility_is_woocommerce_active()) {
            return new WP_REST_Response(null, 404);
        }

        $product_id = $request->get_param('id');
        $product    = wc_get_product($product_id);

        if (!$product) {
            return new WP_REST_Response(null, 404);
        }

        return new TruVisibility_Platform_Product_Info($product_id);
    }

    public function get_orders_by_customer($request)
    {
        if (!truvisibility_is_woocommerce_active()) {
            return array();
        }

        $customer_id = $request->get_param('id');

        $query = new WC_Order_Query(array(
            'customer_id' => $customer_id,
            'return'      => 'ids',
        ));

        $orders = array();
        foreach ($query->get_orders() as $order_id) {
            $orders[] = new TruVisibility_Platform_Order_Info($order_id);
        }

        return $orders;
    }

    public function check_platform($request)
    {
        $platform = $request->get_param('platform');
        if ($platform == 'WooCommerce') {
            return truvisibility_is_woocommerce_active();
        }

        return true;
    }

    public function save_auth($request)
    {
        $access_token  = $request->get_param('access_token');
        $refresh_token = $request->get_param('refresh_token');
        $expires_in    = $request->get_param('expires_in');

        $this->integration_state->save_auth($access_token, $refresh_token, $expires_in);
    }

    public function reset_auth($request)
    {
        $this->integration_state->disconnect_account();
        return true;
    }

    public function set_account($request)
    {
        $this->integration_state->connect_account($request->get_param('account_id'), $this->integration_state->get_server_access_token());
    }

    public function save_gdpr_settings($request)
    {
        $this->integration_state->save_gdpr_settings($request->get_param('gdpr_enabled'), $request->get_param('gdpr_privacy_url'));
        wp_send_json_success(null, 200);
        die();
    }

    public function get_gdpr_settings($request)
    {
        $return = array(
            'gdpr_enabled'    => $this->integration_state->is_gdpr_enabled(),
            'gdpr_privacy_url' => $this->integration_state->get_gdpr_privacy_url(),
        );
        wp_send_json_success($return);
        die();
    }

    public function reset_auth_permissions()
    {
        return current_user_can('activate_plugins');
    }

    private function extract_paging_params($request)
    {
        $page = 1;
        if (isset($request['page'])) {
            $page = intval($request['page']);
            if ($page < 1) {
                $page = 1;
            }

        }

        $page_size = 5;
        if (isset($request['pagesize'])) {
            $page_size = intval($request['pagesize']);
            if ($page_size < 1) {
                $page_size = 1;
            }

        }

        return array($page, $page_size);
    }
}
