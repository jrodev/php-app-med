<?php
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");

function errorHandler($error_level, $error_message, $error_file, $error_line, $error_context)
{
    $error = "set_error_handler lvl: " . $error_level . " | msg:" . $error_message . " | file:" . $error_file . " | ln:" . $error_line;
    switch ($error_level) {
        case E_ERROR:
        case E_CORE_ERROR:
        case E_COMPILE_ERROR:
        case E_PARSE:
            mylog($error, "fatal");
            break;
        case E_USER_ERROR:
        case E_RECOVERABLE_ERROR:
            mylog($error, "error");
            break;
        case E_WARNING:
        case E_CORE_WARNING:
        case E_COMPILE_WARNING:
        case E_USER_WARNING:
            echo $error."warn";
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            mylog($error, "info");
            break;
        case E_STRICT:
            mylog($error, "debug");
            break;
        default:
            mylog($error, "warn");
    }
}

function shutdownHandler() //will be called when php script ends.
{
    $lasterror = error_get_last();
    switch ($lasterror['type'])
    {
        case E_ERROR:
        case E_CORE_ERROR:
        case E_COMPILE_ERROR:
        case E_USER_ERROR:
        case E_RECOVERABLE_ERROR:
        case E_CORE_WARNING:
        case E_COMPILE_WARNING:
        case E_PARSE:
            $error = "register_shutdown_function [SHUTDOWN] lvl:" . $lasterror['type'] . " | msg:" . $lasterror['message'] . " | file:" . $lasterror['file'] . " | ln:" . $lasterror['line'];
            echo $error . " fatal";
    }
}

/*
COLOCAR ESTE CONDIGO EN OTRO ARCHIVO PARA PODER CAPTURAR LA RUTA DEL FILE AL MOSTRAR EL ERROR O EXEPCION
*/
$a = function ( $int) {
    console.log($int);
};
$a(5);

exit;
