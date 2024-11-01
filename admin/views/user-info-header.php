<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<span class="welcome-message text-nowrap text-truncate">
  <?php esc_html_e('TruVISIBILITY Plugin connected to ','truvisibility-platform'); echo esc_html($user_name); ?>
</span>


<span data-bs-toggle="modal" data-bs-target="#settings-modal">
  <span class="action-icon-wrapper" title="<?php esc_html_e('Settings','truvisibility-platform'); ?>"
    data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-offset="0,5" data-bs-trigger="hover">
    <span class="icon icon-settings"></span>
  </span>
</span>

<a class="disconnect-link btn-plugin-primary btn btn-outline-primary text-nowrap text-truncate" data-bs-toggle="modal" data-bs-target="#disconnect-modal">
  <span class="disconnect-link__text"><?php esc_html_e('Disconnect Plugin','truvisibility-platform'); ?></span>
  <span class="icon icon-unlink"></span>
</a>