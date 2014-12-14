<?php

require_once __DIR__.'/../vendor/autoload.php';

Symfony\Component\HttpKernel\Debug\ErrorHandler::register();
Symfony\Component\HttpKernel\Debug\ExceptionHandler::register();

try {
    Dotenv::load(__DIR__.'/../');
} catch (\InvalidArgumentException $e) {
    $message = 'It seems you did not configured the application. See the README file for more instructions.';

    throw new \RuntimeException($message);
}

require_once __DIR__.'/../app/app.php';
require_once __DIR__.'/../app/routes.php';

$app->run();
