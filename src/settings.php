<?php

$env = Dotenv\Dotenv::create(__DIR__ . DIRECTORY_SEPARATOR . '..');
$env->load();

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],
         'db' => [
           //instead of actual values, use getenv() function to load from .env file
           'dbtype' => getenv('DB_TYPE'),
           'dbhost' => getenv('DB_HOST'),
           'dbname' => getenv('DB_NAME'),
           'dbuser' => getenv('DB_USER'),
           'dbpass' => getenv('DB_PASS')
       ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];
