<?php

use Slim\Container;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use UrlShortener\Controllers\UrlController;

$container = $app->getContainer();

$container[EntityManager::class] = function (Container $container) {
    $settings = $container->get('settings')['doctrine'];
    $config = Setup::createAnnotationMetadataConfiguration(
        $settings['entity_path'],
        $settings['auto_generate_proxies'],
        $settings['proxy_dir'],
        $settings['cache'],
        false
    );
    $eventManager = new \Doctrine\Common\EventManager();
    $entityManager = EntityManager::create($settings['connection'], $config, $eventManager);
    return $entityManager;
};

$container[UrlController::class] = function ($container) {
    return new UrlController($container->get(EntityManager::class));
};