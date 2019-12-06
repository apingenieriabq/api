<?php
session_start();
// session_destroy();
// die();
$origin=isset($_SERVER['HTTP_ORIGIN'])?$_SERVER['HTTP_ORIGIN']:$_SERVER['HTTP_HOST'];
// header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: '.$origin);
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept, Origin, Authorization, X-API-KEY, Access-Control-Request-Method');
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header('Access-Control-Allow-Methods: POST, OPTIONS, GET, PUT');
// header('P3P: CP="NON DSP LAW CUR ADM DEV TAI PSA PSD HIS OUR DEL IND UNI PUR COM NAV INT DEM CNT STA POL HEA PRE LOC IVD SAM IVA OTC"');
header('Access-Control-Max-Age: 1');
require './config.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Middleware\HttpBasicAuthentication;

$app = new \Slim\App([
    'settings' => [
        'determineRouteBeforeAppMiddleware' => true,
        'addContentLengthHeader' => false,
        'displayErrorDetails' => true,
        'debug' => true
    ]
]);
$errorAPI = false;
$modoPRUEBA_SINSEGURIDAD = false;

$app->add(function ($request, $response, $next) {
    global $errorAPI;
    Usuario::ip();
    //Antes de la RUTA
    $method = $request->getMethod();
    $uri = $request->getUri();
    $ruta = $uri->getPath();
    $response = $response->withHeader('Content-type', 'application/json; charset=utf-8');
    // $response = $response->withHeader('Content-type', 'text/html; charset=utf-8');

    if ( $ruta === '/') {
    //     if( !$errorAPI ){
    //          $response = $response->withHeader('Content-type', 'application/json; charset=utf-8');
    //     }else{
            $response = $response->withHeader('Content-type', 'text/html; charset=utf-8');
        // }
    // } else {
    //     $response = $response->withHeader('Content-type', 'application/json; charset=utf-8');
    }
    // $myvar1 = $req->getParam('myvar'); //checks both _GET and _POST [NOT PSR-7 Compliant]
    // $myvar2 = $req->getParsedBody()['myvar']; //checks _POST  [IS PSR-7 compliant]
    // $myvar3 = $req->getQueryParams()['myvar']; //checks _GET [IS PSR-7 compliant]

    $response = $next($request, $response);

    Usuario::registrarPosicion();
    return $response;
});

// $checkProxyHeaders = true;
// $trustedProxies = ['10.0.0.1', '10.0.0.2'];
// $app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));
$app->map(['GET','POST'], '/{componente}/{controlador}/{operacion}', function ($request, $response, $args) {

    // echo "<p>Hola {$_SERVER['PHP_AUTH_USER']}.</p>";
    // echo "<p>Introdujo {$_SERVER['PHP_AUTH_PW']} como su contraseña.</p>";
    // echo "<p>El sistema tiene registrado el usuaurio:</p>";
    // // print_r(Usuario::sesionActiva());

    $componente = $request->getAttribute('componente');
    $controlador = $request->getAttribute('controlador');
    $operacion = $request->getAttribute('operacion');
    // echo "<br />VAmos a ejeuctar la funcion en el motor.";
    $errorAPI = Motor::procesar($componente, $controlador, $operacion);

    if(!$errorAPI){
        echo Motor::$respuesta;
    }
    return $response;
})->add( new ControlAutenticacion() );

$app->map(['GET','POST'],'/conectar', function ($request, $response, $args) {
    return $response->write(
        RespuestasSistema::exito("Bienvenido al Api REST de la AP Ingenieria.", Usuario::sesionActiva() )
    );
})->add( new ControlAutenticacion() );
// $app->get('/sesionActiva', function ($request, $response, $args) {
//     return $response->write(
//         RespuestasSistema::exito("Sesion Activa por el Usuario Actual", SesionCliente::completa() )
//     );
// })->add( new ControlAutenticacion() );
// $app->get('/mostrarMenu', function ($request, $response, $args) {
//     $errorAPI = Motor::procesar('seguridad', 'usuarios', 'mostrarMenu');
//     if(!$errorAPI){

//          echo Motor::$respuesta;
//     }
//     return $response;
// })->add( new ControlAutenticacion() );
$app->map(['GET','POST'],'/registrarUbicacion', function ($request, $response, $args) {
    return $response;
})->add( new ControlAutenticacion('GPS') );
$app->get('/desconectar', function ($request, $response, $args) {
    Usuario::cerrarSesion();
    return $response->write(
        RespuestasSistema::exito("Desconectado correctamente.")
    );
});//->add( new ControlAutenticacion('SALIR') );

$app->get('/', function (Request $request,  Response $response, $args = []) {
    global $errorAPI;

    // if(!Usuario::estaLogueado()){
    //     Usuario::iniciarSesion('invitado','invitado');
    //     Usuario::comoInvitado();
    // }
    Vistas::plantilla('basica');
    // $errorAPI = Motor::procesar('sistema', 'api', 'inicio', 'invitado');
    // if(!$errorAPI){
    //      echo Motor::$respuesta;
    // }
    return $response;
});
$app->run();
// echo "99 - Terminó:  ".date('Ymdhisu')."       <br /><br /><br /><br />";
// exit();