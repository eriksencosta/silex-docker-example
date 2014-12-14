<?php

if (!$environmentReady) {
    $app['console']->add(new Example\Command\ConfigureEnvironment);

    return;
}

$app['console']->add(new Example\Command\ConfigureEnvironment);
$app['console']->add(new Example\Command\DatabaseCreate);
$app['console']->add(new Example\Command\FixtureLoad);
$app['console']->add(new Example\Command\SchemaCreate);
