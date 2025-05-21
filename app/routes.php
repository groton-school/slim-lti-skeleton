<?php

declare(strict_types=1);

use GrotonSchool\Slim\LTI\Actions\JWKSAction;
use GrotonSchool\Slim\LTI\Actions\LaunchAction;
use GrotonSchool\Slim\LTI\Actions\LoginAction;
use GrotonSchool\Slim\LTI\Actions\RegistrationStartAction;
use Odan\Session\Middleware\SessionStartMiddleware;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    // standard LTI endpoints
    $app->group('/lti', function (Group $lti) {
        $lti->post('/launch', LaunchAction::class);
        $lti->get('/jwks', JWKSAction::class);
        $lti->get('/register', RegistrationStartAction::class);
        $lti->post('/login', LoginAction::class);
    })->add(SessionStartMiddleware::class);
};
