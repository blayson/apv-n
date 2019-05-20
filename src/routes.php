<?php

use \Slim\App;

$app->get('/', Controllers\MainController::class)->setName('main');

$app->group('/persons', function (App $app) {
    $app->get('', Controllers\PersonController::class)->setName('personsList');
    $app->get('/detail/{id:[0-9]+}', Controllers\PersonController::class . ':detail')->setName('personDetail');
    $app->map(['GET', 'POST'], '/new', Controllers\PersonController::class . ':newPerson')->setName('newPerson');
    $app->map(['GET', 'POST'], '/edit[/{id:[0-9]+}]',
        Controllers\PersonController::class . ':edit')->setName('editPerson');
    $app->map(['GET', 'POST'], '/delete', Controllers\PersonController::class . ':delete')->setName('deletePerson');
});

$app->group('/meetings', function (App $app) {
    $app->get('', Controllers\MeetingController::class . ':home')->setName('meetingsList');
    $app->get('/{id:[0-9]+}', Controllers\MeetingController::class . ':detail')->setName('meetingDetail');
    $app->map(['GET', 'POST'], '/new', Controllers\MeetingController::class . ':newMeeting')->setName('newMeeting');
});

//$app->group('/auth', function (App $app) {
//
//});
//$app->group('/api', function (App $app) {
//    $app->get('/persons',
//        Api\PersonController::class . ':home')->setName('personsListApi');
//    $app->map(['GET', 'POST'], '/add', Controllers\PersonController::class . ':addPerson')->setName('addPerson');
//    $app->get('/persons/{id}', Controllers\PersonController::class . ':detail')->setName('personDetail');
//});