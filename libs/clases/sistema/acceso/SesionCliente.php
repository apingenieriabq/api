<?php

class SesionCliente {

    static function abrir() {
        $status = session_status();
        switch ($status) {
            case PHP_SESSION_NONE:
error_reporting(0);
// ini_set('display_errors', TRUE);
// ini_set('display_startup_errors', TRUE);
                session_start();
 error_reporting(E_ALL);
// ini_set('display_errors', TRUE);
// ini_set('display_startup_errors', TRUE);
                break;
            case PHP_SESSION_ACTIVE:
                return true;
                break;
        }
    }

    static function cerrar() {
      $status = session_status();
      if($status == PHP_SESSION_ACTIVE){
        session_write_close();
      }
    }

    static function activa(){
        self::abrir();
        $SesionActiva = self::valor('SESION');
        // self::cerrar();
        return $SesionActiva;
    }

    static function usuario(){
        self::abrir();
        $SesionActiva = self::valor('Usuario');
        // self::cerrar();
        return $SesionActiva;
    }

    static function completa(){
        self::abrir();
        $SesionActiva = $_SESSION;
        // self::cerrar();
        return $SesionActiva;
    }

    static function dato($variable){
        $valor = false;
        $SesionActiva = self::valor('SESION');
        if (property_exists( $SesionActiva, $variable)) {
            $valor = $SesionActiva->$variable;
        }
        return $valor;
    }

    static public function valor($nombre, $valor = null) {

        if (!is_null($valor)) {
            self::abrir();
            // print_r($valor);
            $_SESSION[$nombre] = $valor;
            self::cerrar();
        } else {
                self::abrir();
            if (!empty($_SESSION[$nombre])) {
                try {
                    try {
                        $valor = $_SESSION[$nombre];
                    } catch (Exception $e) { $valor =  null; }
                } catch (Exception $e) { $valor =  null; }
                self::cerrar();
            } else {
                self::cerrar();
                return null;
            }
        }
        return $valor;

    }

    static public function eliminar($nombre) {
        self::abrir();
        unset($_SESSION[$nombre]);
        self::abrir();
    }

    static public function destruir() {

        // echo "<p>Hola {$_SERVER['PHP_AUTH_USER']}.</p>";
        // echo "<p>Introdujo {$_SERVER['PHP_AUTH_PW']} como su contraseña.</p>";

        self::abrir();
        $_SESSION = array();
        $helper = array_keys($_SESSION);
        foreach ($helper as $key){
            unset($_SESSION[$key]);
        }
        session_destroy();
        self::cerrar();

        header('WWW-Authenticate: Basic realm="Test Authentication System"');
        header('HTTP/1.0 401 Unauthorized');
        echo "Debes escribir un usuario y clave validos.\n";
        exit;
    }

}
