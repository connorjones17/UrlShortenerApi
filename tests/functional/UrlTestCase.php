<?php

namespace UrlShortener\Tests\Functional;

use UrlShortener\App;
use Slim\Http\Request;
use Slim\Http\Environment;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManager;

abstract class UrlTestCase extends TestCase
{
    protected $app;
    protected $container;
    protected $entityManager;

    protected function setUp()
    {
        $_SERVER['HTTP_HOST'] = 'localhost:8000';

        $this->app = (new App())->get();
        $this->container = $this->app->getContainer();
        $this->entityManager = $this->container->get(EntityManager::class);
    }

    protected function tearDown()
    {
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL('url'));
        $this->app = null;
    }

    protected function makeRequest(array $mockOptions = [])
    {
        $env = Environment::mock($mockOptions);
        $req = Request::createFromEnvironment($env);
        $this->container['request'] = $req;
    }
}