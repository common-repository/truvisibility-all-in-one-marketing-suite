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
class TruVisibility_Platform_Customer_Info
{
    /**
     * Customer ID.
     *
     * @var string
     */
    public $id = 0;

    /**
     * Date Created.
     *
     * @var string
     */
    public $date_created;

    /**
     * Date Created GMT.
     *
     * @var string
     */
    public $date_created_gmt;

    /**
     * Date Modified.
     *
     * @var string
     */
    public $date_modified;

    /**
     * Date Modified GMT.
     *
     * @var string
     */
    public $date_modified_gmt;

    /**
     * Email.
     *
     * @var string
     */
    public $email;

    /**
     * First Name.
     *
     * @var string
     */
    public $first_name;

    /**
     * Last Name.
     *
     * @var float
     */
    public $last_name;

    /**
     * Role.
     *
     * @var string
     */
    public $role;

    /**
     * Username.
     *
     * @var string
     */
    public $username;

    /**
     * Avatar Url.
     *
     * @var string
     */
    public $avatar_url;

    /**
     * Phone.
     *
     * @var string
     */
    public $phone;

    /**
     * Address.
     *
     * @var string
     */
    public $address;

    /**
     * Initialize the class and set its properties.
     *
     * @param      int        $customer_id      The order id.
     * @param      bool        $is_wc_customer   The customer from WooCommerce.
     */
    public function __construct($customer_id, $is_wc_customer = true)
    {
        $this->id = strval($customer_id);

        if ($is_wc_customer) {
            $customer    = new WC_Customer($customer_id);
            $data        = $customer->get_data();
            $format_date = array('date_created', 'date_modified');

            // Format date values.
            foreach ($format_date as $key) {
                // Date created is stored UTC, date modified is stored WP local time.
                $datetime            = 'date_created' === $key && is_subclass_of($data[$key], 'DateTime') ? get_date_from_gmt(gmdate('Y-m-d H:i:s', $data[$key]->getTimestamp())) : $data[$key];
                $data[$key]          = wc_rest_prepare_date_response($datetime, false);
                $data[$key . '_gmt'] = wc_rest_prepare_date_response($datetime);
            }

            $this->date_created      = $data['date_created'];
            $this->date_created_gmt  = $data['date_created_gmt'];
            $this->date_modified     = $data['date_modified'];
            $this->date_modified_gmt = $data['date_modified_gmt'];
            $this->email             = $data['email'];
            $this->first_name        = $data['first_name'];
            $this->last_name         = $data['last_name'];
            $this->role              = $data['role'];
            $this->username          = $data['username'];
            $this->avatar_url        = $customer->get_avatar_url();
            $this->address           = $customer->get_billing_address_1();
            $this->phone             = $customer->get_billing_phone();
            if (empty($this->phone)) {
                $this->phone = $customer->get_shipping_phone();
            }
        } else {
            $user                    = get_userdata($customer_id);
            $this->date_created      = $user->user_registered;
            $this->date_created_gmt  = $user->user_registered;
            $this->date_modified     = $user->user_registered;
            $this->date_modified_gmt = $user->user_registered;
            $this->email             = $user->user_email;
            $this->first_name        = $user->first_name;
            $this->last_name         = $user->last_name;
            $this->role              = $user->roles[0];
            $this->username          = $user->user_login;
            $this->avatar_url        = get_avatar_url($user->ID);
        }
    }
}
