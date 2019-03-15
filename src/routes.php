<?php

// Routes
$app->get('/', Controllers\MainController::class)->setName('main');

$app->group('/persons', function (\Slim\App $app) {
    $app->get('', Controllers\PersonController::class . ':home')->setName('personsList');
    $app->map(['GET', 'POST'], '/add', Controllers\PersonController::class . ':addPerson')->setName('addPerson');
    $app->get('/{id}', Controllers\PersonController::class . ':detail')->setName('personDetail');
});

$app->group('/auth', function (\Slim\App $app) {

});

$app->group('/meetings', function (\Slim\App $app) {

});
