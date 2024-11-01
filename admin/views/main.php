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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="trv-admin-panel" style="display: none;">
  <?php TruVisibility_Platform_Admin::view( 'loading' ); ?>

  <header class="trv-header">
    <?php TruVisibility_Platform_Admin::view( 'user-info-header', array( 'user_name' => $user->account_name ) ); ?>
  </header>

  <main class="trv-main">
      <?php if ( $user->is_default || $user->is_owner ) { ?>
        <section class="content-products">
          <?php TruVisibility_Platform_Admin::view( 'product-tile',
              array(
                  'type' => 'chat',
                  'imageName' => 'live-chat.svg',
                  'productName' => __('Chat', 'truvisibility-platform'),
                  'productDescription' => __('Add a chatbot or Live Chat.', 'truvisibility-platform'),
                  'manageUrl' => 'https://chat.' . TruVisibility_Platform_Config::$TvUmbrellaRoot . '/app/#/chat-widgets',
                  'selectText' => __('Select chat widget to copy shortcode', 'truvisibility-platform'),
                  'noItemText' => __('Create A Chat Widget', 'truvisibility-platform'),
                  'items' => $chats ) );
          ?>
          <?php TruVisibility_Platform_Admin::view( 'product-tile',
              array(
                  'type' => 'forms',
                  'imageName' => 'forms.svg',
                  'productName' => __('Forms', 'truvisibility-platform'),
                  'productDescription' => __('Utilize Forms on your website.', 'truvisibility-platform'),
                  'manageUrl' => 'https://forms.' . TruVisibility_Platform_Config::$TvUmbrellaRoot,
                  'selectText' => __('Select form widget to copy shortcode', 'truvisibility-platform'),
                  'noItemText' => __('Create a New Form', 'truvisibility-platform'),
                  'items' => $forms ) );
          ?>
          <?php TruVisibility_Platform_Admin::view( 'product-tile',
              array(
                  'type' => 'crm',
                  'imageName' => 'crm.svg',
                  'productName' => __('CRM', 'truvisibility-platform'),
                  'productDescription' => __('Manage your customer lists.', 'truvisibility-platform'),
                  'manageUrl' => 'https://crm.' . TruVisibility_Platform_Config::$TvUmbrellaRoot) );
          ?>
          <?php TruVisibility_Platform_Admin::view( 'product-tile',
              array(
                  'type' => 'email',
                  'imageName' => 'email.svg',
                  'productName' => __('Messaging', 'truvisibility-platform'),
                  'productDescription' => __('Send email messages to your customers.', 'truvisibility-platform'),
                  'manageUrl' => 'https://em.' . TruVisibility_Platform_Config::$TvUmbrellaRoot) );
          ?>
        </section>
      <?php } else { ?>
        <section class="access-denied">
          <h1 class="img-animation-target">
            <?php esc_html_e('Connection denied','truvisibility-platform'); ?>
          </h1>
          <section class="img-animation-target">
            <?php esc_html_e('Please contact your account administrator','truvisibility-platform'); ?>
          </section>
          <img class="text-animation-target" src="<?php echo esc_url(TRUVISIBILITY_PLATFORM_PLUGIN_URL . 'assets/images/access-denied.png'); ?>" />
        </section>
      <?php } ?>

      <section id="trv-just-connected-message" class="just-connected-message">
        <img src="<?php echo esc_url(TRUVISIBILITY_PLATFORM_PLUGIN_URL . 'assets/images/ok.svg'); ?>" class="img-animation-target" />
        <p class="just-connected-message__text text-animation-target">
          <?php
            echo esc_html($title);
            esc_html_e(' is now connected to the TruVISIBILITY All-In-One Digital Marketing Suite', 'truvisibility-platform');
          ?>
        </p>
      </section>
  </main>

  <?php if ( $user->is_default || $user->is_owner ) { ?>
    <footer class="trv-footer main-footer">
      <a target="_blank" href="<?php echo esc_url('https://auth.' . TruVisibility_Platform_Config::$TvUmbrellaRoot . '/account'); ?>">
        <?php esc_html_e('Go To Your TruVISIBILITY Account','truvisibility-platform'); ?>
      </a>
      <a target="_blank" href="https://support.truvisibility.com/">
        <?php esc_html_e('Go To TruVISIBILITY Support','truvisibility-platform'); ?>
      </a>
    </footer>
  <?php } ?>

  <!-- Modal -->
  <div id="disconnect-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-no-min-height modal-lg" role="dialog" aria-hidden="true">
      <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title" id="modal-basic-title">
          <?php esc_html_e('Disconnect the TruVISIBILITY Plugin', 'truvisibility-platform'); ?>
        </h4>
        </div>
        <div class="modal-body no-footer">
          <?php esc_html_e('By disconnecting the TruVISIBILITY plugin it will remove the connection between TruVISIBILITY and this WordPress website. The existing data held in your TruVISIBILITY account will remain available. New data will no longer be added to your TruVISIBILITY Account.', 'truvisibility-platform'); ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-rounded" data-bs-dismiss="modal"><?php esc_html_e('Cancel', 'truvisibility-platform'); ?></button>
          <button type="button" class="btn btn-danger btn-rounded" onclick="disconnect()"><?php esc_html_e('Disconnect', 'truvisibility-platform'); ?></button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <?php TruVisibility_Platform_Admin::view( 'settings-modal',
            array(
                'gdprEnabled' => $gdprEnabled,
                'gdprPrivacyUrl' => $gdprPrivacyUrl
                ));
        ?>
</div>
