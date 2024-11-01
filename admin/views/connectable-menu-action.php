<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<button class="btn btn-icon" onclick="<?php echo esc_attr( $action->generator ); ?>"
    data-toggle="tooltip" title="<?php echo esc_html( $action->definition->tooltip ); ?>">
    <section class="done">
        <div class="done__body">
            <i class="icon icon-check"></i>
        </div>
    </section>
    <i class="icon icon-<?php echo esc_attr( $action->definition->iconCode ); ?>"></i>
</button>