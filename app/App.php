<?php

namespace UrlShortener;

class App
{
    /**
     * Stores an instance of the Slim application.
     *
     * @var \Slim\App
     */
    private $app;

    /**
     * Application setup
     */
    public function __construct() {

        $settings = require __DIR__ . '/../config/settings.php';

        $app = new \Slim\App($settings);

        // Register routes
        $routeFactory = require __DIR__ . '/../config/routes.php';
        $routeFactory($app);

        // Register dependencies
        require __DIR__ . '/../config/dependencies.php';

        $this->app = $app;
    }


    /**
     * Get an instance of the application.
     *
     * @return \Slim\App
     */
    public function get()
    {
        return $this->app;
    }
}