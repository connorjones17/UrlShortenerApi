<?php

use UrlShortener\App;

require __DIR__ . '/../vendor/autoload.php';

$app = (new App())->get();

$app->run();