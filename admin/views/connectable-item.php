<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<li class="connectable-item">
    <span class="connectable-item__status connectable-item__status--<?php echo esc_attr( $item->status ); ?>"></span>
    <span class="connectable-item__name text-truncate">
        <?php echo esc_html( $item->name ); ?>
    </span>
    <?php 
        foreach ( $item->actions as $action ) { 
            TruVisibility_Platform_Admin::view( 'connectable-menu-action', array('action' => $action));
        }
    ?>
</li>