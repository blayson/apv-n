<?php

// Routes
$app->get('/', Controllers\MainController::class);
$app->get('/list', Controllers\PersonListController::class);
$app->get('/person/{id}', Controllers\PersonController::class);