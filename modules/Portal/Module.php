<?php
namespace App\Portal;

use Composer\Autoload\ClassLoader;
use Slim\App;
use Slim\Container;
use MartynBiz\Slim3Module\AbstractModule;

class Module extends AbstractModule
{
    public function getModuleConfig()
    {
        return [
            'logger' => [
                //...
            ],
        ];
    }

    public function initClassLoader(ClassLoader $classLoader)
    {
        //$classLoader->setPsr4("App\\Portal\\", __DIR__."/app/");
    }

    public function initDependencies(Container $container)
    {
        // $container['phpErrorHandler'] Se define en SLIM en : vendor\slim\slim\Slim\DefaultServicesProvider.php line:133
        // unset($app->getContainer()['errorHandler']);
        unset($container['phpErrorHandler']);

        // monolog
        $container['logger'] = function ($c) {
            $settings = $c->get('settings')['logger'];
            $logger = new \Monolog\Logger($settings['name']);

            $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
            $logger->pushProcessor(new \Monolog\Processor\WebProcessor());

            $logger->pushHandler(new \Monolog\Handler\BrowserConsoleHandler());
            $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
            //d(LogLevel); // //d($logger);
            return $logger;
        };

        // view renderer
        $container['renderer'] = function ($c) {
            $settings = $c->get('settings')['renderer'];
            return new \Slim\Views\PhpRenderer($settings['template_path']);
        };

        // registrando en el handler
        //Monolog\ErrorHandler::register($container['logger'], [], [], true);
        $handler = new \Monolog\ErrorHandler($container['logger']);
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
            $view = new \Slim\Views\Twig($rendered['template_path'], ['cache' => false]);

            // Vie Helpers
            $twig = $view->getEnvironment();

            // Variable Global
            $twig->addGlobal('twigGlobalVar', 'Hi Global Var!');

            // Funcion Helper
            $twig->addFunction(new \Twig_SimpleFunction('baseUrl', function ($all=FALSE) use ($settings) {
                $strBaseUrl = sprintf(
                    "%s://%s%s",
                    ( $settings['env']=='production' || (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='off') ) ? 'https' : 'http',
                    $_SERVER['HTTP_HOST'],
                    $all ? $_SERVER['REQUEST_URI'] : "/"
                );
                return $strBaseUrl;
            }));

            // Function get Socket Url
            $twig->addFunction(new \Twig_SimpleFunction('socketUrl', function () use ($settings) {
                $socketUrl = $settings['socket-url'];
                return $socketUrl;
            }));

            // instancia y añade la extensión especifica de slim
            $basePath =  rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');

            $view->addExtension(new \Slim\Views\TwigExtension($c['router'], $basePath));
            return $view;
        };

        // Cargando libreria para cargar JSON
        $container['loadJson'] = function ($c) {
        	$jsonPath = $c->get('settings')['jsonPath'];
            $capsule = new \Libs\DataLoader($jsonPath);
            return $capsule;
        };

        // Asignando los controllers al container
        // Lista de Controllers
        $ctrlls = [ "IndexController", "MenuController" ];
        // Agregando dinamicamente los controllers al container
        array_walk ($ctrlls, function (&$val, $key) use ($container) {
            $className = "App\\Portal\\Controllers\\{$val}";
            //d($container, $className);
            $container[$val] = function ($c) use ($className) {
                $reflection = new \ReflectionClass($className);
                return $reflection->newInstanceArgs(array($c));
            };
        });
    }

    public function initMiddleware(App $app)
    {
        $app->add(new \Slim\Middleware\Session([
          'name' => 'dummy_session',
          //'autorefresh' => true,
          'lifetime' => '5 min'
        ]));

        // Register globally to app
        $app->getContainer()['session'] = function ($c) {
          return new \SlimSession\Helper;
        };
    }

    public function initRoutes(App $app)
    {
        $app->get('[/[index[/index[/]]]]', 'IndexController:index');

        $app->get('/menu[/index[/]]', 'MenuController:index');

        //$app->get('/cocina[/index[/]]', 'CocinaController:index');

        $app->get('/test', function ($request, $response, $args) {

            d($this->session); exit;

        });
    }
}
