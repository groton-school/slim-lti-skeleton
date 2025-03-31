<?php

declare(strict_types=1);

use App\Application\Handlers\LaunchHandler;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use GrotonSchool\Slim\LTI;
use GrotonSchool\Slim\LTI\Actions\RegistrationConfigureActionInterface;
use GrotonSchool\Slim\LTI\Actions\RegistrationConfigurePassthruAction;
use GrotonSchool\Slim\LTI\Handlers\LaunchHandlerInterface;
use GrotonSchool\Slim\LTI\Infrastructure\Cookie;
use GrotonSchool\Slim\LTI\Infrastructure\CookieInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Packback\Lti1p3\Interfaces\ICookie;
use Packback\Lti1p3\Interfaces\ILtiRegistration;
use Packback\Lti1p3\LtiRegistration;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        // all settings interfaces map to the App Settings
        LTI\SettingsInterface::class => DI\get(SettingsInterface::class),

        // autowire packbackbooks/lti-1p3-tool implementations
        ILtiRegistration::class => DI\autowire(LtiRegistration::class),
        ICookie::class => DI\autowire(LTI\Infrastructure\Cookie::class),
        // TODO ICache and IDatabase need implementations

        // autowire groton-school/slim-lti-shim implementations
        CookieInterface::class => DI\autowire(Cookie::class),
        // TODO CacheInterface and DatabaseInterface need implementations
        LaunchHandlerInterface::class => DI\autowire(LaunchHandler::class),

        /*
        * autowire registration configuration passthru (no interactive
        * configuration)
        * 
        * to set up interactive configurattion of the registration, implement
        * (and autowire) GrotonSchool\Slim\LTI\Actions\RegistrationConfigureActionInterface
        *
        * Interactive configuration ends either by invoking
        * GrotonSchool\Slim\LTI\Action\RegistrationCompleteAction::complete()
        * or by POSTing the complete registration (with the parameter name
        * registration) to an endpoint handled by
        * GrotonSchool\Slim\LTI\Action\RegistrationCompleteAction
        */
        RegistrationConfigureActionInterface::class => DI\autowire(RegistrationConfigurePassthruAction::class)
    ]);
};
