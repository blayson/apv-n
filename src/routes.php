<?php

use \Slim\App;

$app->get('/', Controllers\MainController::class)->setName('main');

$app->group('/persons', function (App $app) {
    $app->get('[/]', Controllers\PersonController::class)->setName('personsList');
    $app->get('/detail/{id:[0-9]+}', Controllers\PersonController::class . ':detail')->setName('personDetail');
    $app->map(['GET', 'POST'], '/new', Controllers\PersonController::class . ':newPerson')->setName('newPerson');
    $app->map(['GET', 'POST'], '/edit[/{id:[0-9]+}]',
        Controllers\PersonController::class . ':edit')->setName('editPerson');
    $app->map(['GET', 'POST'], '/delete', Controllers\PersonController::class . ':delete')->setName('deletePerson');
});

$app->group('/meetings', function (App $app) {
    $app->get('[/]', Controllers\MeetingController::class . ':home')->setName('meetingsList');
    $app->get('/{id:[0-9]+}', Controllers\MeetingController::class . ':detail')->setName('meetingDetail');
    $app->map(['GET', 'POST'], '/new', Controllers\MeetingController::class . ':newMeeting')->setName('newMeeting');
    $app->map(['GET', 'POST'], '/edit[/{id:[0-9]+}]',
        Controllers\MeetingController::class . ':editMeeting')->setName('editMeeting');
    $app->map(['GET', 'POST'], '/delete', Controllers\MeetingController::class . ':delete')->setName('deleteMeeting');
    $app->map(['GET', 'POST'], '/addPersonToMeeting[/{idMeeting:[0-9]+}]',
        Controllers\MeetingController::class . ':addPersonToMeeting')->setName('addPersonToMeeting');
});

$app->group('/ajax', function (App $app) {
    $app->map(['GET', 'POST'], '/location',
        Controllers\AjaxController::class . ':getLocationAjax')->setName('getLocationAjax');
    $app->post('/addPersonToMeeting',
        Controllers\AjaxController::class . ':addPersonToMeetingAjax')->setName('addPersonToMeetingAjax');
});
