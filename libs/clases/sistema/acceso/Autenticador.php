<?php
use \Slim\Middleware\HttpBasicAuthentication\AuthenticatorInterface;
class Autenticador implements AuthenticatorInterface
{
    var $SENTIDO;
    function __construct($Sentido = 'ENTRAR') {
        $this->SENTIDO = $Sentido;
    }

    public function __invoke(array $arguments)
    {
        global $modoPRUEBA_SINSEGURIDAD;
        if($modoPRUEBA_SINSEGURIDAD) return true;
        // switch($this->SENTIDO){
        //     case 'GPS':
                // return true;
        //         break;
        //     default:
                return $this->validarDatos($arguments);
        //         break;
        // }
    }


    private function validarDatos(array $arguments){
    // echo "<p>Hola {$_SERVER['PHP_AUTH_USER']}.</p>";
    // echo "<p>Introdujo {$_SERVER['PHP_AUTH_PW']} como su contraseña.</p>";
        if (strlen($arguments["user"]) >= 0) {
            try {
                // $nombreUsuario = Encriptacion::desencriptar(CLAVE_SECRETA_SICAM, $user);
                $nombreUsuario = $arguments["user"];
            } catch (Exception $e) {
            }
        }

        if (strlen($arguments["password"]) >= 0) {
            try {
                // $claveUsuario = Encriptacion::desencriptar(CLAVE_SECRETA_SICAM, $password);
                $claveUsuario = $arguments["password"];
            } catch (Exception $e) {
            }
        }
        
        

        if (!empty($nombreUsuario) and !empty($claveUsuario)){
          return Usuario::iniciarSesion($nombreUsuario, $claveUsuario);
        }

        session_destroy();
        return false;
    }

}
