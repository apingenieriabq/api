<?php

class Usuario {

    const INVITADO = 0;
    const SUPERUSER = 1;

    public static function GeoIP($direccionIP = null) {
        if (is_null($direccionIP)) {
            $direccionIP = self::ip();
        }
        $PosicionPorIP = json_decode(APIipapi::datos($direccionIP));
        return SesionCliente::valor('POSICION_IP', $PosicionPorIP);
    }

    public static function latitud($poslat = null) {
        // echo "***************";var_dump($poslat);
        if (is_null($poslat)) {
            // self::GeoIP();
            if (isset(SesionCliente::valor('POSICION_IP')->latitude)) {
                $poslat = SesionCliente::valor('POSICION_IP')->latitude;
            }
            // echo "***************";
        }
        // echo $poslat."***************";
        return SesionCliente::valor('_LATITUD', $poslat);
    }

    public static function longitud($poslon = null) {
        // echo "***************";var_dump($poslat);
        if (is_null($poslon)) {
            // self::GeoIP();
            if (isset(SesionCliente::valor('POSICION_IP')->longitude)) {
                $poslon = SesionCliente::valor('POSICION_IP')->longitude;
            }
            // echo "***************";
        }
        // echo $poslat."***************";
        return SesionCliente::valor('_LONGITUD', $poslon);
    }

    public static function ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } else if (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {

            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            } else {
                $ipaddress = 'DESCONOCIDA';
            }
        }
        return $ipaddress;
    }

    public static function esAdministrador() {
        $dato = SesionCliente::usuario();
        if (!empty($dato)):
            if ($dato->usuarioADMINISTRADOR == 'SI'):
                return true;
            endif;
        endif;
        return false;
    }

    public static function comoInvitado() {
        $_SESSION['INVITADO'] = 1;
    }

    public static function esInvitado() {
        if (isset($_SESSION['INVITADO']) and $_SESSION['INVITADO'] == 1):
            return true;
        else:
            return false;
        endif;
    }

    public static function estaLogueado() {
        SesionCliente::abrir();
        $ObjUSER = SesionCliente::usuario();
        SesionCliente::cerrar();
        if ($ObjUSER) {
            return true;
        }
        return false;
    }

    public static function iniciarSesionColaboradorCedula($cedulaColaborador, $claveUsuario) {
        $Colaborador = new Colaboradores();
        $Colaborador->datosPorCedula($cedulaColaborador);
        // print_r($Colaboradores);
        if (!is_null($Colaborador->colaboradorID)) {
            $Usuario = new Usuarios();
            $Usuario->porColaboradorID($Colaborador->colaboradorID);
            if ($Usuario->usuarioESTADO == 'ACTIVO') {
                $resultado = $Usuario->comprobar($Usuario->usuarioNOMBRE, $claveUsuario);
                // print_r($resultado);
                if (!empty($resultado)) {
                    try {
//                    $Usuario->registrarUltimaVisita(Usuario::ip(), Usuario::latitud(), Usuario::longitud());
                        // SesionCliente::valor('SESION', self::singleton());
                        // SesionCliente::valor('Usuario', $Usuario);
                    } catch (Exception $e) {
                        print_r($e);
                    }
                    return 'CORRECTO';
                }
                return 'COMBINACION';
            } else {
                return 'DESACTIVO';
            }
        } else {
            return 'FALLO CEDULA';
        }
    }

    public static function iniciarSesionColaborador($correoColaborador, $claveUsuario) {
        $Colaboradores = new Colaboradores();
        $Colaboradores->datosPorCorreo($correoColaborador);
        // print_r($Colaboradores);
        if (!is_null($Colaboradores->colaboradorID)) {
            $Usuarios = new Usuarios();
            // $Usuarios->porColaboradorID( $Colaboradores->colaboradorID );
            // // print_r($Usuarios->usuarioNOMBRE);
            // $resultado = $Usuarios->comprobar($Usuarios->usuarioNOMBRE, $claveUsuario);
            // // die();
            // if (!empty($resultado)){
            //     $Usuarios->datosCompletos();
            //     if($Usuarios->usuarioID === 0){
            //       Usuario::comoInvitado();
            //     }
            //     // Usuario::abrirSesion($Usuarios);
            return 'CORRECTO';
            // }
            return 'COMBINACION';
        } else {
            return 'FALLO CORREO';
        }
    }

    public static function iniciarSesion($nombreUsuario, $claveUsuario) {
        $Usuarios = new Usuarios();
        $Usuarios->comprobar($nombreUsuario, $claveUsuario);

        // print_r($Usuarios);
        // die();
        if (isset($Usuarios->usuarioID) and ! is_null($Usuarios->usuarioID)) {
            $Usuarios->datosCompletos();
            if ($Usuarios->usuarioID === 0) {
                Usuario::comoInvitado();
            }
            Usuario::abrirSesion($Usuarios);
            return true;
        }
        return false;
    }

    public static function abrirSesion($Usuario) {

//        $Usuario->registrarUltimaVisita(Usuario::ip(), Usuario::latitud(), Usuario::longitud());
        SesionCliente::valor('SESION', self::singleton());
        SesionCliente::valor('Usuario', $Usuario);
//       print_r($Usuario);
// echo  (' abrir sesion');
        // return true;
    }

    public static function estadoSesion() {
        if (!empty($_SESSION['SESION_ESTADO'])):
            return $_SESSION['SESION_ESTADO'];
        else:
            return null;
        endif;
    }

    public static function sesionSuspendida() {
        if (!empty($_SESSION['SESION_ESTADO'])):
            if ($_SESSION['SESION_ESTADO'] == 'INACTIVIDAD'):
                return true;
            else:
                return false;
            endif;
        else:
            return $_SESSION['SESION_ESTADO'];
        endif;
    }

    public static function cerrarSesion() {
        SesionCliente::destruir();
    }

    public static function dato($atributo) {
        if (self::estaLogueado()):
            $dato = SesionCliente::dato($atributo);
            return $dato;
        endif;
    }

    public static function datoSession($nombre, $valor = NULL) {
        return SesionCliente::valor($nombre, $valor);
    }

    public static function tienePermiso($codigoOperacion) {
        $permisosDefault = ['iniciarSesion', 'perfilUsuarioSeguridad', 'cerrarSesion'];
        if (!in_array($codigoOperacion, $permisosDefault)):
            if (ControlAcceso::porIp(self::ip())):
                if (ControlAcceso::delUsaurioPorCodigoOperacion(self::dato('usuarioID'), $codigoOperacion)):
                    return true;
                else:
                    return false;
                endif;
            else:
                return false;
            endif;
        else:
            return true;
        endif;
    }

    public static function apiTienePermiso($codigoOperacion, $usuario, $ip = null) {
        $permisosDefault = [];
        if (!in_array($codigoOperacion, $permisosDefault) and ! empty($usuario)):
            if (ControlAcceso::porIp($usuario, $ip)):
                //if(ControlAcceso::delUsaurio($usuario->usuarioID, $codigoOperacion)):
                if (true):
                    return true;
                else:
                    return false;
                endif;
            else:
                return false;
            endif;
        else:
            return true;
        endif;
    }

    var $nombre;
    var $correo;
    var $cedula;

    public static function id() {
        return isset($_SESSION['Usuario']) ? $_SESSION['Usuario']->usuarioID : null;
    }

    public static function usuarioID() {
        return self::id();
    }

    public static function usuarioNOMBRE() {
        return isset($_SESSION['Usuario']) ? $_SESSION['Usuario']->usuarioNOMBRE : null;
    }

    public static function usuarioESTADO() {
        return isset($_SESSION['Usuario']) ? $_SESSION['Usuario']->usuarioESTADO : null;
    }

    public static function cargoID() {
        return isset($_SESSION['Usuario']) ? $_SESSION['Usuario']->cargoID : null;
    }

    public static function colaboradorID() {
        return isset($_SESSION['Usuario']) ? $_SESSION['Usuario']->colaboradorID : null;
    }

    public static function cedula() {
        return isset($_SESSION['Usuario']) ? $_SESSION['Usuario']->personaIDENTIFICACION : null;
    }

    public static function nombreCompleto() {
        return isset($_SESSION['Usuario']) ? $_SESSION['Usuario']->personaNOMBRES . " " . $_SESSION['Usuario']->personaAPELLIDOS : null;
    }

    public static function nombres() {
        return isset($_SESSION['Usuario']) ? $_SESSION['Usuario']->personaNOMBRES : null;
    }

    public static function apellidos() {
        return isset($_SESSION['Usuario']) ? $_SESSION['Usuario']->personaAPELLIDOS : null;
    }

    public static function correo() {
        return isset($_SESSION['Usuario']) ? $_SESSION['Usuario']->colaboradorEMAIL : null;
    }

    public static function firma() {
        return isset($_SESSION['Usuario']) ? $_SESSION['Usuario']->colaboradorFIRMA : null;
    }

    public static $instancia;

    public static function singleton() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }

    static $ultimaOperacionRegistrada;

    public static function registrarOperacion($accionCOMPONENTE, $accionCONTROLADOR, $accionOPERACION, $usuarioNOMBRE, $usuarioID) {

        self::$ultimaOperacionRegistrada = new AccionesUsuarios(
          $accionCOMPONENTE, $accionCONTROLADOR, $accionOPERACION, $usuarioNOMBRE, Usuario::ip(), $usuarioID
        );
    }

    public static function registrarRespuesta($respuesta) {

        self::$ultimaOperacionRegistrada->respuesta($respuesta);
    }

    public static function registrarPosicion($lat = null, $lon = null) {
        self::GeoIP();
        if (is_null($lat)) {
            $latSesion = SesionCliente::valor('_LATITUD');
            if (isset($_POST['usuarioULTIMALATITUD'])) {
                $lat = $_POST['usuarioULTIMALATITUD'];
            } elseif (!empty($latSesion)) {
                $lat = SesionCliente::valor('_LATITUD');
            }
        }
        if (is_null($lon)) {
            // $lonSesion = SesionCliente::valor('_LONGITUD');
            if (isset($_POST['usuarioULTIMALONGITUD'])) {
                $lon = $_POST['usuarioULTIMALONGITUD'];
            } elseif (!empty($lonSesion)) {
                //     $lon = SesionCliente::valor('LONGITUD');
            }
        }
        $Usuarios = new Usuarios();
        $GeoIP = Usuario::ip();
        // $lon = Usuario::longitud($lon);
        $lat = Usuario::latitud($lat);
        $usuarioID = Usuario::usuarioID();
        $Usuarios->registrarUltimaVisita($GeoIP, $lat, $lon, $usuarioID);


        $datosIP = SesionCliente::valor('POSICION_IP');
        if (empty($datosIP)) {
            $datosIP = Usuario::GeoIP();
        }
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "";

        try {
            $Ubicacion = new UbicacionesUsuarios();
            $Ubicacion->nuevo(
              Usuario::usuarioID(), $actual_link, $datosIP->ip, $datosIP->continent_name, $datosIP->country_name, $datosIP->city, $datosIP->region_name, $datosIP->zip, $lat, $lon,
              $datosIP->location->capital, $datosIP->location->country_flag, $datosIP->location->calling_code
            );
        } catch (Exception $e) {
            
        }
    }

    public static function sesionActiva() {
        if (!empty(SesionCliente::completa())):
            return SesionCliente::completa();
        else:
            return null;
        endif;
    }

    public static function conectado() {
        if (!empty(SesionCliente::usuario())):
            return SesionCliente::usuario();
        else:
            return null;
        endif;
    }

}
