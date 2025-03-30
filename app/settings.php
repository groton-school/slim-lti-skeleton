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
                Settings::TOOL_URL => 'https://example.com', // Should be set CORRECTLY!
                Settings::SCOPES => ['https://purl.imsglobal.org/spec/lti-nrps/scope/contextmembership.readonly']
            ]);
        }
    ]);
};
