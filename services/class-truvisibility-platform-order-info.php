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
class TruVisibility_Platform_Order_Info
{
    /**
     * Order ID.
     *
     * @var int
     */
    public $id = 0;

    /**
     * Customer ID.
     *
     * @var int
     */
    public $customer_id;

    /**
     * Order Url.
     *
     * @var string
     */
    public $url;

    /**
     * Order Date.
     *
     * @var string
     */
    public $created_date;

    /**
     * Order Total.
     *
     * @var float
     */
    public $amount;

    /**
     * Order Status.
     *
     * @var string
     */
    public $status;

    /**
     * Order Ship Date.
     *
     * @var WC_DateTime
     */
    public $ship_date;

    /**
     * Order Ship By.
     *
     * @var string
     */
    public $ship_by;

    /**
     * Order Tracking Number.
     *
     * @var int
     */
    public $tracking_number;

    /**
     * Order Tracking URL.
     *
     * @var string
     */
    public $tracking_url;

    /**
     * Order Ship To.
     *
     * @var string
     */
    public $ship_to;

    /**
     * Order Ship Address 1.
     *
     * @var string
     */
    public $ship_address_1;

    /**
     * Order Ship Address 2.
     *
     * @var string
     */
    public $ship_address_2;

    /**
     * Order Ship City.
     *
     * @var string
     */
    public $ship_city;

    /**
     * Order Ship State.
     *
     * @var string
     */
    public $ship_state;

    /**
     * Order Ship Country.
     *
     * @var string
     */
    public $ship_country;

    /**
     * Order Ship ZIP.
     *
     * @var string
     */
    public $ship_zip;

    /**
     * Order Ship ZIP.
     *
     * @var TruVisibility_Platform_Product_Info[]
     */
    public $products;

    /**
     * Initialize the class and set its properties.
     *
     * @param      int        $order_id      The order id.
     */
    public function __construct($order_id)
    {
        $this->id = $order_id;

        $order                = wc_get_order($order_id);
        $data                 = $order->get_data();
        $this->customer_id    = $order->get_customer_id();
        $this->url            = $order->get_view_order_url();
        $this->created_date   = is_subclass_of($data['date_created'], 'DateTime') ? get_date_from_gmt(gmdate('Y-m-d H:i:s', $data['date_created']->getTimestamp())) : $data['date_created'];
        $this->amount         = $order->get_total();
        $this->status         = $order->get_status();
        $this->ship_to        = trim($order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name());
        $this->ship_address_1 = $order->get_shipping_address_1();
        $this->ship_address_2 = $order->get_shipping_address_2();
        $this->ship_city      = $order->get_shipping_city();
        $this->ship_state     = $order->get_shipping_state();
        $this->ship_country   = $order->get_shipping_country();
        $this->ship_zip       = $order->get_shipping_postcode();

        $this->products = array();
        foreach ($order->get_items() as $item) {
            if (!$item instanceof WC_Order_Item_Product) {
                continue;
            }

            $this->products[] = new TruVisibility_Platform_Product_Info($item->get_product_id());
        }
    }
}
