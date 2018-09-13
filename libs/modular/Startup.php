<?php
/**
 * Slim Modular
 * @author Jrodev <jrodev@yahoo.es>
 */

namespace Libs\Modular;

use Composer\Autoload\ClassLoader;

class Startup
{
    /**
     * @var Slim App
     */
    protected $app;

    /**
     * @var array
     */
    protected $moduleInstances = [];

    public function __construct($app, $modules=array())
    {
        $this->app = $app;

        // build an class map of [[module => moduleClassPath], ..]
        foreach ($modules as $module) {
            $moduleClassName = sprintf('%s\Module', $module);
            $this->moduleInstances[$module] = new $moduleClassName();
        }
    }

    /**
     * Load the module. This will run for all modules, use for routes mainly
     * @param string $moduleName Module name
     */
    public function initModules()
    {
        $container = $this->app->getContainer();
        // $this->initClassLoader($classLoader);
        $this->initModuleConfig($container);
        $this->initDependencies($container);
        $this->initMiddleware($this->app);
        $this->initRoutes($this->app);
    }

    /**
     * Recolectando los configs's de todos los modulos
     */
    private function getAllConfig()
    {
        $allConfigs = [];
        foreach ($this->moduleInstances as $moduleName => $module) {
            $allConfigs[$moduleName] = $module->getModuleConfig();
        }
        return $allConfigs;
    }

    /**
     * Almacenando los config's de todos los modulos debajo del nombre asociativo: "modules"
     * @param Container $container Container instancia
     */
    private function initModuleConfig($container)
    {
        $allSettings = $container['settings']->all();

        if (!isset($allSettings['modules']) or !is_array($allSettings['modules'])) {
            $allSettings['modules'] = [];
        }

        $allSettings = array_merge_recursive($allSettings, $this->getAllConfig());
        $container['settings']->__construct( $allSettings );
    }

    /**
     * inicializando dependencias, ejecutando initDependencies() de cada modulo.
     * @param Container $container Container instancia
     */
    public function initDependencies($container)
    {
        foreach ($this->moduleInstances as $module) {
            $module->initDependencies($container);
        }
    }

    /**
     * Load the module. This will run for all modules, use for routes mainly
     * @param Slim $app App instancia
     */
    public function initMiddleware($app)
    {
        foreach ($this->moduleInstances as $module) {
            $module->initMiddleware($app);
        }
    }

    /**
     * Cargar los routers de todos los modulos
     * @param Slim $app App instancia
     */
    public function initRoutes($app)
    {
        foreach ($this->moduleInstances as $module) {
            $module->initRoutes($app);
        }
    }
}
