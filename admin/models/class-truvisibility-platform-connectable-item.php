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
class TruVisibility_Platform_Connectable_Item
{
    /**
     * ID.
     *
     * @var string
     */
    public $id;

    /**
     * Name.
     *
     * @var string
     */
    public $name;

    /**
     * Status.
     *
     * @var string
     */
    public $status;


    /**
     * Actions.
     *
     * @var TruVisibility_Platform_Connectable_Item_Action[]
     */
    public $actions;

    /**
     * Initialize the class and set its properties.
     *
     * @param      int        $id      The id.
     * @param      string     $name    The name.
     * @param      string     $status  The status.
     */
    public function __construct($id, $name, $status, $actions)
    {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
        $this->actions = $actions;
    }
}
