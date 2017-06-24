<?php

return [
    'settings' => [
        'doctrine' => [
            'entity_path' => ['app/Entities'],
            'auto_generate_proxies' => true,
            'proxy_dir' =>  __DIR__ . '/../data/cache/proxies',
            'cache' => null,
            'connection' => [
                'driver' => 'pdo_mysql',
                'path' => __DIR__ . '/../data/database.sq3',
                'host' => '192.168.20.56',
                'dbname' => 'url_shortener',
                'user' => 'root',
                'password' => '',
            ],
        ],
        'displayErrorDetails' => true
    ]
];