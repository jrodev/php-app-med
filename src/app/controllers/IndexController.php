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
        $this->session->set('my_key', 'my_value');

        d($this->session->get('my_key'));
        exit;
        //d($req, $resp, $args); //exit;
        return $this->render($resp, 'index/index.twig');
    }

}
