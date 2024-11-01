<?php
/**
 *
 * @link       https://truvisibility.com
 * @since      1.0.0
 *
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/admin/models
 */

/**
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/admin/models
 * @author     TruVisibility, LLC
 */
class TruVisibility_Platform_Connectable_Item_Action_Definition
{
    /**
     * Icon Code.
     *
     * @var string
     */
    public $iconCode;

    /**
     * Tooltip.
     *
     * @var string
     */
    public $tooltip;

    /**
     * Initialize the class and set its properties.
     *
     * @param      string     $iconCode      The icon code.
     * @param      string     $tooltip       The tooltip.
     */
    public function __construct($iconCode, $tooltip)
    {
        $this->iconCode = $iconCode;
        $this->tooltip  = $tooltip;
    }
}
