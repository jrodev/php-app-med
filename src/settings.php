<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/app/views/',
        ],

		'jsonPath' => __DIR__ . '/../data/',

        // Monolog settings
        'logger' => [
            'name' => 'php-app-med',
            'path' => __DIR__ . '/../logs/app.log',
            //'level' => \Monolog\Logger::ERROR, Registrar segun NIVEL DEFINIDIO (por defecto todos los niveles)
        ],
        'env' => getenv('APP_ENV'),
        'socket-url' => (getenv('APP_ENV')=='production') ? 'https://socket-menu.herokuapp.com' : 'http://192.168.10.17:8686'
    ],
];
