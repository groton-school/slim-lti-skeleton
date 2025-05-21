<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;
use Odan\Session\SessionInterface;

return function (ContainerBuilder $containerBuilder) {

    // Global Settings Object
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            $TOOL_NAME = 'slim-lti-skeleton';
            $TOOL_URL = 'https://' . getenv('HTTP_HOST');
            $SCOPES = ['https://purl.imsglobal.org/spec/lti-nrps/scope/contextmembership.readonly'];
            return new Settings([
                'displayErrorDetails' => true, // Should be set to false in production
                'logError'            => false,
                'logErrorDetails'     => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                Settings::TOOL_NAME => 'slim-lti-skeleton',
                Settings::TOOL_URL => 'https://example.com', // TODO gShould be set CORRECTLY!
                Settings::SCOPES => ['https://purl.imsglobal.org/spec/lti-nrps/scope/contextmembership.readonly'],
                Settings::TOOL_REGISTRATION => [
                    'application_type' => 'web',
                    'client_name' => $TOOL_NAME,
                    'client_uri' => $TOOL_URL,
                    'grant_types' => ['client_credentials', 'implicit'],
                    'jwks_uri' => "{$TOOL_URL}/lti/jwks",
                    'token_endpoint_auth_method' => 'private_key_jwt',
                    'initiate_login_uri' =>  "{$TOOL_URL}/lti/login",
                    'redirect_uris' => ["{$TOOL_URL}/lti/launch"],
                    'response_types' => ['id_token'],
                    "scope" => join(' ', $SCOPES),
                    'https://purl.imsglobal.org/spec/lti-tool-configuration' => [
                        'domain' => preg_replace('@^https?://@', '', $TOOL_URL),
                        'target_link_uri' => "{$TOOL_URL}/lti/launch",
                        'messages' => [
                            [
                                "type" => "LtiResourceLinkRequest",
                                "label" => $TOOL_NAME,
                                "custom_parameters" => [
                                    'user_id' => '$Canvas.user.id',
                                    'brand_config_json_url' => '$com.instructure.brandConfigJSON.url',
                                    'brand_config_js_url' => '$com.instructure.brandConfigJS.url',
                                    'common_css_url' => '$Canvas.css.common',
                                    'prefers_high_contrast' => '$Canvas.user.prefersHighContrast',
                                    'placement' => 'course_navigation'
                                ],
                                "placements" => ["course_navigation"],
                                "roles" => []
                            ]
                        ],
                        "claims" => [
                            "sub",
                            "iss",
                            "name",
                            "given_name",
                            "family_name",
                            "nickname",
                            "picture",
                            "email",
                            "locale"
                        ],
                        'https://canvas.instructure.com/lti/privacy_level' => 'public'

                    ]
                ],
                SessionInterface::class => [
                    'name' => "php-session",
                    'lifetime' => 60 * 60 * 24,
                    'cookie_samesite' => 'None',
                    'secure' => true,
                    'httponly' => true
                ]
            ]);
        }
    ]);
};
