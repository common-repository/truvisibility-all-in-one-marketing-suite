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
class TruVisibility_Platform_Product_Info
{
    /**
     * Product ID.
     *
     * @var int
     */
    public $id = 0;

    /**
     * Product SKU.
     *
     * @var string
     */
    public $sku;

    /**
     * Product Brand.
     *
     * @var string
     */
    public $brand;

    /**
     * Product Categories.
     *
     * @var string[]
     */
    public $categories;

    /**
     * Product Name.
     *
     * @var string
     */
    public $name;

    /**
     * Product Proce.
     *
     * @var string
     */
    public $price;

    /**
     * Currency symbol.
     *
     * @var string
     */
    public $currency;

    /**
     * Product Stock.
     *
     * @var string
     */
    public $stock;

    /**
     * Product UOM.
     *
     * @var string
     */
    public $uom;

    /**
     * Product URL.
     *
     * @var string
     */
    public $url;

    /**
     * Product image URL.
     *
     * @var string
     */
    public $image_url;

    /**
     * Initialize the class and set its properties.
     *
     * @param      int        $product_id      The product id.
     */
    public function __construct($product_id)
    {
        $this->id = $product_id;

        $product   = wc_get_product($product_id);
        $this->sku = $product->get_sku();

        $this->categories = array();
        $product_cats_ids = wc_get_product_term_ids($product->get_id(), 'product_cat');
        foreach ($product_cats_ids as $cat_id) {
            $term               = get_term_by('id', $cat_id, 'product_cat');
            $this->categories[] = $term->name;
        }

        $this->name     = $product->get_name();
        $this->price    = $product->get_price();
        $this->currency = get_woocommerce_currency_symbol();

        $stock_status = $product->get_stock_status();
        if ($stock_status == 'instock') {
            $stock_quantity = $product->get_stock_quantity();
            if (isset($stock_quantity)) {
                $this->stock = strval($stock_quantity);
            } else {
                $this->stock = 'In stock';
            }
        } else {
            $this->stock = 'Out of stock';
        }

        if ($product->has_dimensions()) {
            $this->uom = str_replace('&times;', 'x', wc_format_dimensions($product->get_dimensions(false)));
        }

        $this->url = $product->get_permalink();

        $this->image_url = wc_placeholder_img_src();

        $post_thumbnail_id = $product->get_image_id();
        if ($post_thumbnail_id) {
            $thumbnail_src   = wp_get_attachment_image_src($post_thumbnail_id);
            $this->image_url = $thumbnail_src[0];
        } else {
            $this->image_url = wc_placeholder_img_src();
        }
    }
}
