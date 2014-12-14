<?php

// Our impressive routes.
$app->get('/', 'Example\Controller\Example::issues')->bind('home');
$app->get('/issue/{id}', 'Example\Controller\Example::issue')->bind('issue');
