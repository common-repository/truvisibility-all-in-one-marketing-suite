<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://truvisibility.com
 * @since      1.0.0
 *
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/admin/views
 */

if (!defined('ABSPATH')) {
    exit;
}
// Exit if accessed directly
?>

<div class="trv-admin-panel" style="display: none;">
    <?php TruVisibility_Platform_Admin::view( 'loading' ); ?>

    <header class="trv-header">
        <?php TruVisibility_Platform_Admin::view('signin-link-header');?>
    </header>

    <main class="trv-main">
        <section class="content">
            <h1>
                <span class="primary-title">
                    <img src="<?php echo esc_attr( TRUVISIBILITY_PLATFORM_PLUGIN_URL); ?>assets/images/logo.svg" />
                    <span>
                        <?php esc_html_e('TruVISIBILITY', 'truvisibility-platform');?>
                    </span>
                </span>

                <span>
                    <?php esc_html_e('Digital Marketing Suite', 'truvisibility-platform');?>
                </span>
            </h1>

            <ul class="subheader">
                <li>
                    <?php esc_html_e('No Monthly Fees', 'truvisibility-platform');?>
                </li>
                <li>
                    <?php esc_html_e('No Credit Card Required', 'truvisibility-platform');?>
                </li>
            </ul>

            <main class="products-list">
                <a href="https://www.truvisibility.com/sites/" target="_blank" class="products-list__item">
                    <img src="<?php echo esc_attr( TRUVISIBILITY_PLATFORM_PLUGIN_URL); ?>assets/images/products/sites.svg" />
                    <span>
                    <?php esc_html_e('Sites/Blogs', 'truvisibility-platform');?>
                    </span>
                </a>
                <a href="https://www.truvisibility.com/sites/" target="_blank" class="products-list__item">
                    <img src="<?php echo esc_attr( TRUVISIBILITY_PLATFORM_PLUGIN_URL); ?>assets/images/products/lp.svg" />
                    <span>
                    <?php esc_html_e('Landing Pages', 'truvisibility-platform');?>
                    </span>
                </a>
                <a href="https://www.truvisibility.com/messaging/" target="_blank" class="products-list__item">
                    <img src="<?php echo esc_attr( TRUVISIBILITY_PLATFORM_PLUGIN_URL); ?>assets/images/products/email.svg" />
                    <span>
                    <?php esc_html_e('Email Automation', 'truvisibility-platform');?>
                    </span>
                </a>
                <a href="https://www.truvisibility.com/crm/" target="_blank" class="products-list__item">
                    <img src="<?php echo esc_attr( TRUVISIBILITY_PLATFORM_PLUGIN_URL); ?>assets/images/products/crm.svg" />
                    <span>
                    <?php esc_html_e('CRM', 'truvisibility-platform');?>
                    </span>
                </a>
                <a href="https://www.truvisibility.com/chatbots/" target="_blank" class="products-list__item">
                    <img src="<?php echo esc_attr( TRUVISIBILITY_PLATFORM_PLUGIN_URL); ?>assets/images/products/live-chat.svg" />
                    <span>
                    <?php esc_html_e('Live Chat', 'truvisibility-platform');?>
                    </span>
                </a>
                <a href="https://www.truvisibility.com/chatbots/" target="_blank" class="products-list__item">
                    <img src="<?php echo esc_attr( TRUVISIBILITY_PLATFORM_PLUGIN_URL); ?>assets/images/products/chatbots.svg" style="margin-right: -12px;" />
                    <span>
                    <?php esc_html_e('Chatbots', 'truvisibility-platform');?>
                    </span>
                </a>
                <a href="https://drive.truvisibility.com/" target="_blank" class="products-list__item">
                    <img src="<?php echo esc_attr( TRUVISIBILITY_PLATFORM_PLUGIN_URL); ?>assets/images/products/drive.svg" />
                    <span>
                    <?php esc_html_e('Drive', 'truvisibility-platform');?>
                    </span>
                </a>

                <a href="https://forms.truvisibility.com/" target="_blank" class="products-list__item">
                    <img src="<?php echo esc_attr( TRUVISIBILITY_PLATFORM_PLUGIN_URL); ?>assets/images/products/forms.svg" />
                    <span>
                    <?php esc_html_e('Forms', 'truvisibility-platform');?>
                    </span>
                </a>
            </main>

            <button class="get-started" onclick="startConnectionFlow()"><?php esc_html_e('Connect Plugin', 'truvisibility-platform');?></button>
        </section>
    </main>
</div>