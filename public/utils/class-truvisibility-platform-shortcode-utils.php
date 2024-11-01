<?php
/**
 * The utils responsible for rendering shortcodes.
 *
 * @link       https://truvisibility.com
 * @since      1.0.0
 *
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/services
 */

/**
 * @package    TruVisibility_Platform
 * @subpackage TruVisibility_Platform/services
 * @author     TruVisibility, LLC
 */
class TruVisibility_Platform_Shortcode_Utils
{

    /**
     * Render truvisibility shortcodes output
     *
     * @param array $shortcode_attrs Shortcode attributes.
     */
    public static function render_shortcode($shortcode_attrs)
    {
        $parsed_attributes = shortcode_atts(array('type' => null), $shortcode_attrs);

        $type = $parsed_attributes['type'];
        if (!isset($type)) {
            return;
        }

        switch ($type) {
            case 'chat':
                return self::render_chat($shortcode_attrs);
            case 'form':
                return self::render_form($shortcode_attrs);
        }
    }

    /**
     * Render truvisibility chat shortcodes
     *
     * @param array $attrs Shortcode attributes.
     *
     * https://chat.truVisibility.com/tracking.staging.js - javascript for chat operation
     *
     */
    private static function render_chat($attrs)
    {
        $parsed_attributes = shortcode_atts(
            array(
                'id'       => null,
                'embedded' => 'false',
            ),
            $attrs
        );

        $id       = $parsed_attributes['id'];
        $embedded = $parsed_attributes['embedded'] === 'true';

        if (!isset($id)) {
            return;
        }

        $code = '<!-- Start of TruChat (chat.' . TruVisibility_Platform_Config::$TvUmbrellaRoot . ') code -->';
        if ($embedded) {
            $code = $code . '<div class="tru-chat-container"><div id="tc-' . $id . '"></div></div>';
        }

        return $code . '
                <script type="text/javascript">
					window.__tc = window.__tc || { };
					window.__tc.channelId = "' . $id . '";'
					. self::gdpr_setting_for_chat() . '
					window.__tc.query = window.location.search;
					(function() {
						var tc = document.createElement("script"); tc.type = "text/javascript"; tc.async = true;
						tc.src = "https://chat.' . TruVisibility_Platform_Config::$TvUmbrellaRoot . '/' . TruVisibility_Platform_Config::$ChatTrackingScriptName . '";
						var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(tc, s);
					})();
				</script>
				<!--End of TruChat code -->';
    }

    private static function gdpr_setting_for_chat()
    {
        $gdprEnabled = boolval(get_option(TruVisibility_Platform_Config::GDPR_ENABLED, false));
        if ($gdprEnabled) {
            $gdprPrivacyUrl = get_option(TruVisibility_Platform_Config::GDPR_PRIVACY_URL, "");
            return "
                    window.__tc.privacyPolicyUrl = '" . $gdprPrivacyUrl . "';";
        }

        return "";
    }

    /**
     * Render truvisibility form shortcodes
     *
     * https://forms.truVisibility.com/static/scripts/embed.js - javascript for form operation
     *
     * @param array $attrs Shortcode attributes.
     */
    private static function render_form($attrs)
    {
        $parsed_attributes = shortcode_atts(
            array(
                'id' => null,
            ),
            $attrs
        );

        $id = $parsed_attributes['id'];

        if (!isset($id)) {
            return;
        }

        return '<div tru-forms-app-id="tfb-' . $id . '"></div>
				<script src="https://forms.' . TruVisibility_Platform_Config::$TvUmbrellaRoot . '/static/scripts/embed.js" type="text/javascript"></script>
				<script>
                    window.__truForms = window.__truForms || {};
                    '
					. self::gdpr_setting_for_form() . 
                    '
                    TruForms.createApp("tfb-' . $id . '", window.__truForms.truFormsAppOptions);
                </script>';
    }

    private static function gdpr_setting_for_form()
    {
        $gdprEnabled = boolval(get_option(TruVisibility_Platform_Config::GDPR_ENABLED, false));
        if ($gdprEnabled) {
            $gdprPrivacyUrl = get_option(TruVisibility_Platform_Config::GDPR_PRIVACY_URL, "");
            return "window.__truForms.truFormsAppOptions = {
                        gdprOptions: {
                            isGdprEnabled: true,
                            privacyPolicyUrl: '" . $gdprPrivacyUrl . "'
                        }
                    };";
        }

        return "window.__truForms.truFormsAppOptions = {}";
    }
}
