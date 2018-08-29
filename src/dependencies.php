<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// $container['phpErrorHandler'] Se define en SLIM en : vendor\slim\slim\Slim\DefaultServicesProvider.php line:133
// unset($app->getContainer()['errorHandler']);
unset($app->getContainer()['phpErrorHandler']);

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);

    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushProcessor(new Monolog\Processor\WebProcessor());

    $logger->pushHandler(new Monolog\Handler\BrowserConsoleHandler());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path']/*, $settings['level']*/));
    //d(LogLevel);
    //
    //d($logger);
    return $logger;
};


/*$container['errorHandler'] = $container['phpErrorHandler'] = function ($c) {
  return function ($request, $response, $exception) use ($c) {
    $data = [
      'code' => $exception->getCode(),
      'message' => $exception->getMessage(),
      'file' => $exception->getFile(),
      'line' => $exception->getLine(),
      'trace' => explode("\n", $exception->getTraceAsString()),
    ];

    return $c->get('response')->withStatus(500)
             ->withHeader('Content-Type', 'application/json')
             ->write(json_encode($data));
  };
};*/

// registrando en el handler
//Monolog\ErrorHandler::register($container['logger'], [], [], true);


$handler = new Monolog\ErrorHandler($container['logger']);

$handler->registerErrorHandler([], false);
$handler->registerExceptionHandler();
$handler->registerFatalHandler();
/*
$handler::register($container['logger'], $errorLevelMap = false, $exceptionLevelMap = false);
$handler->registerErrorHandler($levelMap = [], $callPrevious = false);
$handler->registerExceptionHandler($levelMap = [], $callPrevious = false);
*/
// Cargando motor de plantillas twig
$container['view'] = function ($c) {
    //nos indica el directorio donde están las plantillas
    $settings = $c->get('settings');
    $rendered = $settings['renderer'];
    // puede ser false o el directorio donde se guardará la cache
    $view = new Slim\Views\Twig($rendered['template_path'], ['cache' => false]);

    // Vie Helpers
    $twig = $view->getEnvironment();

    // Variable Global
    $twig->addGlobal('twigGlobalVar', 'Hi Global Var!');

    // Funcion Helper
    $twig->addFunction(new Twig_SimpleFunction('baseUrl', function ($all=FALSE) use ($settings) {
        $strBaseUrl = sprintf(
            "%s://%s%s",
            ( $settings['env']=='production' || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='off') ) ? 'https' : 'http',
            $_SERVER['HTTP_HOST'],
            $all ? $_SERVER['REQUEST_URI'] : "/"
        );
        return $strBaseUrl;
    }));

    // Function get Socket Url
    $twig->addFunction(new Twig_SimpleFunction('socketUrl', function () use ($settings) {
        $socketUrl = $settings['socket-url'];
        return $socketUrl;
    }));

    // instancia y añade la extensión especifica de slim
    $basePath =  rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');

    $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));
    return $view;
};

// Cargando libreria para cargar JSON
$container['loadJson'] = function ($c) {
	$jsonPath = $c->get('settings')['jsonPath'];
    $capsule = new Libs\DataLoader($jsonPath);
    return $capsule;
};

// Agregegando Controller
/*
$fileList = glob('test/*');

//Loop through the array that glob returned.
foreach($fileList as $filename){
   //Simply print them out onto the screen.
   echo $filename, '<br>';
}
*/

// Asignando los controllers al container
// Lista de Controllers
$ctrlls = [
    "IndexController",
    "MenuController"
];
// Agregando dinamicamente los controllers al container
array_walk ($ctrlls, function (&$val, $key) use ($container) {
    $className = "App\\Controllers\\{$val}";
    //d($container, $className);
    $container[$val] = function ($c) use ($className) {
        $reflection = new ReflectionClass($className);
        return $reflection->newInstanceArgs(array($c));
    };
});

/*
$container['MenuController'] = function ($c) {
	return new App\Controllers\MenuController($c['view'], $c['router'], $c['loadJson']);
};

$container['CocinaController'] = function ($c) {
	return new App\Controllers\CocinaController($c['view'], $c['router'], $c['loadJson']);
};*/
/*
$container['HomeController'] = function ($c) {
    $settings = $c->get('settings');
	return new App\Controllers\IndexController($settings, $c['view'], $c['router']);
};*/
