<?php

declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Logger;

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
                Settings::TOOL_URL => 'https://example.com', // TODO Should be set CORRECTLY!
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
                                    "foo" => "bar",
                                    "context_id" => '$Context.id'
                                ],
                                "placements" => ["course_navigation"],
                                "roles" => [],
                                "target_link_uri" => "{$TOOL_URL}/lti/launch?placement=course_navigation"
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
                ]
            ]);
        }
    ]);
};
