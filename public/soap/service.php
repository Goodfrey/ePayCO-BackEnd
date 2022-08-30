<?php

require_once 'vendor/autoload.php';
$servicio   =   new     soap_server();

$ns = "urn:SoapApi";
$servicio->configureWSDL("SoapApi",$ns);
$servicio->schemaTargetNamespace = $ns;

$servicio->register("Registro", 
    array(
        'nombre'    => 'xsd:string', 
        'documento' => 'xsd:string', 
        'celular'   => 'xsd:integer', 
        'email'     => 'xsd:string'), 
    array('return'  => 'xsd:string'), $ns );

$servicio->register("Consulta", 
    array(
        'celular'   => 'xsd:integer', 
        'documento' => 'xsd:string'), 
    array('return'  => 'xsd:string'), $ns );

$servicio->register("Recarga", 
    array(
        'celular'   => 'xsd:integer', 
        'documento' => 'xsd:string', 
        'valor'     => 'xsd:integer'), 
    array('return'  => 'xsd:string'), $ns );

$servicio->register("Pago", 
    array(
        'celular'   => 'xsd:integer', 
        'documento' => 'xsd:string',), 
    array('return'  => 'xsd:string'), $ns );

$servicio->register("Confirmation", 
    array(
        'token'     => 'xsd:string', 
        'session'   => 'xsd:string',), 
    array('return'  => 'xsd:string'), $ns );

function Registro($nombre, $documento, $celular, $email)
{
   
    $postdata = http_build_query(
        array(
            'nombre'    => $nombre,
            'documento' => $documento,
            'celular'   => $celular,
            'email'     => $email
        )
    );
    $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context    = stream_context_create($opts);
    $result     = json_decode(file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/api/register', false, $context));
    $status     =   ($result->status == true) ? "Satisfactorio" : "Fallido";

    return 'Status de la Operacion: '.$status.' - Mensaje: '.$result->message.'';

}

function Consulta($celular, $documento)
{
    $postdata = http_build_query(
        array(
            'celular'   => $celular,
            'documento' => $documento,
        )
    );
    $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context    = stream_context_create($opts);
    $result     = json_decode(file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/api/consult', false, $context));
    $status     =   ($result->status == true) ? "Satisfactorio" : "Fallido";

    return 'Status de la Operacion: '.$status.' - Mensaje: '.$result->message.'';
 
}

function Recarga($celular, $documento, $valor)
{
    $postdata = http_build_query(
        array(
            'celular'   => $celular,
            'documento' => $documento,
            'valor'     => $valor,
        )
    );
    $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context    = stream_context_create($opts);
    $result     = json_decode(file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/api/recharge', false, $context));
    $status     =   ($result->status == true) ? "Satisfactorio" : "Fallido";

    return 'Status de la Operacion: '.$status.' - Mensaje: '.$result->message.'';
 
}

function Pago($celular, $documento)
{
    $postdata = http_build_query(
        array(
            'celular'   => $celular,
            'documento' => $documento,
        )
    );
    $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context    = stream_context_create($opts);
    $result     = json_decode(file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/api/payment', false, $context));
    $status     =   ($result->status == true) ? "Satisfactorio" : "Fallido";

    return 'Status de la Operacion: '.$status.' - Mensaje: '.$result->message.'';
 
}

function Confirmation($token, $session)
{
    $postdata = http_build_query(
        array(
            'token'     => $token,
            'session'   => $session,
        )
    );
    $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context    = stream_context_create($opts);
    $result     = json_decode(file_get_contents('http://'.$_SERVER['HTTP_HOST'].'/api/confirmation', false, $context));
    $status     =   ($result->status == true) ? "Satisfactorio" : "Fallido";

    return 'Status de la Operacion: '.$status.' - Mensaje: '.$result->message.'';
 
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA)? $HTTP_RAW_POST_DATA : '';
$servicio->service(file_get_contents("php://input"));

?>