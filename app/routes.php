<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use GrotonSchool\Slim\LTI\Actions\JWKSAction;
use GrotonSchool\Slim\LTI\Actions\LaunchAction;
use GrotonSchool\Slim\LTI\Actions\LoginAction;
use GrotonSchool\Slim\LTI\Actions\RegistrationStartAction;
use Odan\Session\Middleware\SessionStartMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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
