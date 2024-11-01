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
class TruVisibility_Platform_Connectable_Item_Action
{
    /**
     * Defenition.
     *
     * @var TruVisibility_Platform_Connectable_Item_Action_Definition
     */
    public $definition;

    /**
     * Generator.
     *
     * @var string
     */
    public $generator;

    /**
     * Initialize the class and set its properties.
     *
     * @param      string                                                           $generator       The generator.
     * @param      TruVisibility_Platform_Connectable_Item_Action_Definition        $definition      The defenition.     
     */
    public function __construct($generator, $definition)
    {        
        $this->generator  = $generator;
        $this->definition = $definition;
    }
}
