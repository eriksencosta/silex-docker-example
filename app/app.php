<?php

$app = new Silex\Application();

require_once __DIR__.'/config/config.php';

$app->register(new Silex\Provider\DoctrineServiceProvider(), $app['doctrine.dbal.options']);
$app->register(new Silex\Provider\TwigServiceProvider(), $app['twig.options']);
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

return $app;
