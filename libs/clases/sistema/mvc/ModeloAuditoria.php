<?php
if (!defined('CLAVE_SECRETA')) die('acceso no autorizado');
class ModeloAuditoria {
    static $nombreTabla;
    static function insertar($datos){
        global $BD_AP_LOGS;
        $BD_AP_LOGS->insert(self::$nombreTabla, $datos);
        return $BD_AP_LOGS->id();
    }

    static function fila($donde = null, $columnas = '*' ){
        global $BD_AP_LOGS;
        return $BD_AP_LOGS->get(self::$nombreTabla, $columnas, $donde);
    }

    static function variasFilas($donde = null, $columnas = '*' ){
        global $BD_AP_LOGS;
        return $BD_AP_LOGS->select(self::$nombreTabla, $columnas, $donde);
    }

    static function consulta($sql, $donde = null){
        global $BD_AP_LOGS;
        return $BD_AP_LOGS->query($sql, $donde)->fetchAll();
    }

    static function actualizar($datos, $donde){
        global $BD_AP_LOGS;
        $datos = $BD_AP_LOGS->update(self::$nombreTabla, $datos, $donde );
        return $datos->rowCount();
    }

}
