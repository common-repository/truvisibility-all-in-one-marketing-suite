<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div id="settings-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-no-min-height modal-lg" role="dialog" aria-hidden="true">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="modal-basic-title">
            <?php esc_html_e('Settings', 'truvisibility-platform'); ?>
          </h4>
          <button type="button" class="close-btn" data-bs-dismiss="modal">
            <span class="icon icon-close"></span>
          </button>
        </div>
        <div class="modal-body no-footer">
          
          <div class="notification-panel info">
            <div class="notification-panel-body">
              <div class="notification-panel-content">
                <span class="icon icon-info info"></span>
                <span class="text">
                  <?php esc_html_e('You can read more about GDPR in our ', 'truvisibility-platform'); ?>
                  <a href="https://support.truvisibility.com/blog-post-marketingsuite/030920" target="_blank">
                    <?php esc_html_e('Article.', 'truvisibility-platform'); ?>
                  </a>                  
                </span>
              </div>
            </div>
          </div>          

          <div class="form-group">
            <label class="align-items-center">
              <input id="chkGdprEnabled" type="checkbox" class="switchbox green" onchange="toggleGdpr(event)" <?php checked( $gdprEnabled ); ?>>
              <?php esc_html_e('GDPR', 'truvisibility-platform'); ?>
            </label>
          </div>
          
          <div class="form-group">
            <div class="control-label">
              <label for="privacyInput">
                <?php esc_html_e('Privacy Policy URL', 'truvisibility-platform'); ?>
              </label>
            </div>
            <div>
              <input id="privacyInput" name="privacy_url" maxlength="2000" class="form-control" 
                  placeholder="<?php esc_html_e('Enter URL', 'truvisibility-platform'); ?>"
                  onchange="enableSaveGdpr()"
                  value="<?php echo esc_attr($gdprPrivacyUrl); ?>"
                  <?php disabled( !$gdprEnabled, true ); ?>>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="saveGdprBtn" type="button" class="btn btn-save btn-primary btn-rounded" disabled="disabled" onclick="saveGdprSettings()"><?php esc_html_e('Save', 'truvisibility-platform'); ?></button>          
        </div>
      </div>
    </div>  
  </div>
</div>