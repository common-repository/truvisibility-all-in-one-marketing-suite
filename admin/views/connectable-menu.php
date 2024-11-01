<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<connectable-menu>
    <div class="dropdown">
        <button type="button" id="dropdown" class="dropdown-toggle btn link-btn integration-menu-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <span>
                <?php echo esc_html($selectText); ?>
            </span>
            <i class="icon icon-arrow-down rotatable"></i>
        </button>
        <div class="connectable-dropdown dropdown-menu">
            <ul class="list-unstyled integration-menu">
                
                <?php if ( isset( $items ) && ( is_countable( $items) ? count( $items ) : 0 ) > 0 ) {
                	    foreach ( $items as $item ) { 
                            TruVisibility_Platform_Admin::view( 'connectable-item', array('type' => $type, 'item' => $item));
                        } 
                    } else { ?>
                        <li class="connectable-item empty">
                            <?php echo esc_html($noItemText); ?>
                        </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</connectable-menu>