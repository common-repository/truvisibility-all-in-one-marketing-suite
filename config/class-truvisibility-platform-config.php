<?php

final class TruVisibility_Platform_Config
{
    const PLUGIN_NAME = 'truvisibility-platform';

    const PLUGIN_VERSION = '1.1.3';

    const CLIENT_ACCESS_TOKEN_OPTION = 'truvisibility_platform_client_access_token';

    const ACCOUNT_ID_OPTION = 'truvisibility_platform_account_id';

    const SERVER_ACCESS_TOKEN_OPTION = 'truvisibility_platform_server_access_token';
    const SERVER_REFRESH_TOKEN_OPTION = 'truvisibility_platform_server_refresh_token';
    const SERVER_ACCESS_TOKEN_EXPIRES_OPTION = 'truvisibility_platform_server_expires';    
    
    const GDPR_ENABLED = 'truvisibility_platform_gdpr_enabled';
    const GDPR_PRIVACY_URL = 'truvisibility_platform_gdpr_privacy_url';
    
    const ACTIVATION_REDIRECT_OPTION = 'truvisibility_platform_activation_redirect';

    public static $TvUmbrellaRoot         = 'truvisibility.com';
    public static $SslVerify              = true;
    public static $ChatTrackingScriptName = 'tracking.js';

}
