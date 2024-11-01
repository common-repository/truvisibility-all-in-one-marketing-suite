<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<product-tile>
    <header>
        <img src="<?php echo esc_url(TRUVISIBILITY_PLATFORM_PLUGIN_URL . 'assets/images/products/' . $imageName); ?>">
        <h1><?php echo esc_html($productName); ?></h1>
        <a target="_blank" class="btn-plugin-primary btn btn-outline-primary" href="<?php echo esc_url($manageUrl); ?>">
            <?php esc_html_e('Manage', 'truvisibility-platform'); ?>
        </a>
    </header>
    <main>
        <?php echo esc_html($productDescription); ?>
    </main>
    <footer>
        <?php if ( $type === 'chat' || $type === 'forms' ) {
            TruVisibility_Platform_Admin::view( 'connectable-menu',  
                array(
                    'type' => $type,                
                    'selectText' => $selectText,
                    'noItemText' => $noItemText,
                    'items' => $items ) ); 
        }; ?>
    </footer>
</product-tile>