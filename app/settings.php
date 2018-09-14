<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/modules/Portal/views/',
        ],

		'jsonPath' => __DIR__ . '/../data/',

        // MONOLOG SETTINGS
        /*
        DEBUG(100): Detailed debug information.
        INFO(200): Interesting events. Examples: User logs in, SQL logs.
        NOTICE(250): Normal but significant events.
        WARNING(300): Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
        ERROR(400): Runtime errors that do not require immediate action but should typically be logged and monitored.
        CRITICAL(500): Critical conditions. Example: Application component unavailable, unexpected exception.
        ALERT(550): Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
        EMERGENCY(600): Emergency: system is unusable.
        */
        'logger' => [
            'name' => 'php-app-med',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::WARNING, //Registrar NIVEL MINIMO(> OR =) para registrar (por defecto todos los niveles)
        ],

        'env' => getenv('APP_ENV'),
        'socket-url' => (getenv('APP_ENV')=='production') ? 'https://socket-menu.herokuapp.com' : 'http://192.168.10.17:8686'
    ],
];
