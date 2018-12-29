<?php //echo sprintf('%s\Module', "module"); exit;
if  (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

//session_start();

// Instantiate the app
$settings = require __DIR__ . '/../app/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
//require __DIR__ . '/../app/dependencies.php';

//require __DIR__ . '/../src/middleware.php'; // Register middleware
//require __DIR__ . '/../src/routes.php'; // Register routes

$moduleInitializer = new \Libs\Modular\Startup($app, [
    'App\Portal',
]);

$moduleInitializer->initModules();

$app->run(); // Run app
