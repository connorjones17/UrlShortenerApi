<?php

use Slim\App;
use UrlShortener\Controllers\UrlController;

return function (App $app) {
    $app->get('/create', UrlController::class . ':create');
    $app->get('/{hash}', UrlController::class . ':index');
};
