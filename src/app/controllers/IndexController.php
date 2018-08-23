<?php
namespace App\Controllers;

use App\Controller;
use Libs\ChangeString;
use Libs\CompleteRange;
use Libs\ClearPar;
/**
 * Acciones para el Controlador Home
 */
class IndexController extends Controller
{

    public function index($req, $resp, $args)
    {
        //d($req); exit;

        //$this->session->set('my_key', 'my_value');
        //d($this->session->get('my_key'));
        //$this->logger->warning('Foo!');
        //$this->logger->error('Bar!!');

        $a = function(int $int){
            console.log($int)
        };
        $a(1);

        //exit;
        //d($req, $resp, $args); //exit;
        return $this->render($resp, 'index/index.twig');
    }

}
