<?php

namespace App;

class Controller
{

    protected $cnf; // Settings o Config
    protected $view;
    protected $router;
    protected $session;

    /**
     * La instanciación del controller se hace en dependencies.php
     * @param settings   $settings Variables configuracion
     * @param Twig       $view     Motor de plantillas
     * @param Routes     $router   Ruteo
     */
    public function __construct($container)
    {
        $this->cnf     = $container->get('settings');
        $this->view    = $container['view'];
        $this->router  = $container['router']; //d($router);
        $this->session = $container['session'];
        //$this->logger  = $container['logger'];
    }

    public function render($resp, $view, $args = []) {

        //$pagesPath = $this->cnf['renderer']['template_path']."pages/".$view;
        //$pagesPath, no necesario! El contexto se define en la definicion de twig en dependencies.ph
        //d($pagesPath); exit;
        //$route = $req->getAttribute('route');
        //d($this->router); // $this->router <> $req->getAttribute('route')
        return $this->view->render($resp, "pages/".$view, $args);
    }

    /*
    protected $ci;

    public function __construct($ci) {
        $this->ci = $ci;
        $this->ci->db;
        $this->flash = $ci->flash;
    }

    public function redirect($route) {
        header('Location: ' . $this->ci->router->pathFor($route), true, 302);
        die();
    }
    */
}
