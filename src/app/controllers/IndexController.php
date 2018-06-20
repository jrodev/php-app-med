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
        d($GLOBALS); exit;
        //d($req, $resp, $args); //exit;
        return $this->render($resp, 'index/index.twig');
    }

}
