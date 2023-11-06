<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (file_exists(dirname(__DIR__) . '/config/bootstrap.php')) {
    require dirname(__DIR__) . '/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {

    $files = [
        '.env.test.local',
        '.env.test',
        '.env.local',
        '.env'
    ];

    foreach ($files as $file) {
        if (file_exists(dirname(__DIR__) . '/' . $file)) {
            (new Dotenv())->bootEnv(dirname(__DIR__) . '/' . $file);

            break;
        }
    }}
