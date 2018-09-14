<?php
namespace App\Portal;

use Composer\Autoload\ClassLoader;
use Slim\App;
use Slim\Container;
use Libs\Modular\AbstractModule;

class Module extends AbstractModule
{
    public function getModuleConfig()
    {
        return [
            'renderer' => [ 'template_path' => __DIR__ . "/views/" ]
        ];
    }

    public function initClassLoader(ClassLoader $classLoader)
    {
        //$classLoader->setPsr4("App\\Portal\\", __DIR__."/app/");
    }

    public function initDependencies(Container $container)
    {
        // Adicionando directorio de templates
        $container["view"]->getLoader()->addPath(__DIR__ . "/views/", 'portal');

        // Asignando los controllers al container
        // Lista de Controllers
        $ctrlls = [ "IndexController", "MenuController" ];
        // Agregando dinamicamente los controllers al container
        array_walk($ctrlls, function (&$val, $key) use ($container) {
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
          'name'=>'dummy_session', 'lifetime'=>'5 min'/*,'autorefresh' => true*/
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
