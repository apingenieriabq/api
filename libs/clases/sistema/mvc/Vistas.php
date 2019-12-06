<?php
class Vistas {
    public static function mostrar($componente, $vista, $datos = array()) {
        $twig = Motor::twigConfigPlantilla(DIR_COMPONENTES);
        // require DIR_SICAM.'libs/FuncionesVistas.php';
        if( isset($datos['hash_vista_padre'])){
          $datos['hash_vista'] = $datos['hash_vista_padre'];
        }else{
          $datos['hash_vista'] = uniqid();
        }
//        $datos['session'] = Cliente::getUsuario();
        $datos['URL_ARCHIVOS'] = URL_ARCHIVOS;
        try{
            echo $twig->render( $componente . DS . 'vistas' . DS . $vista . EXT_VISTA,
                $datos
            );
        }catch (Exception $e){
            echo RespuestasSistema::error(
                'ERROR AL CARGAR VISTA [' . DIR_COMPONENTES. $componente . DS . 'vistas' . DS . $vista . EXT_VISTA . '], COMUNICARSE CON GESTION TICS.' .
                ' '.print_r($e,true)
                );
        }
    }
    public static function plantilla($nombre, $datos = array()) {
        $twig = Motor::twigConfigPlantilla(DIR_PLANTILLAS);
        if( isset($datos['hash_vista_padre'])){
          $datos['hash_vista'] = $datos['hash_vista_padre'];
        }else{
          $datos['hash_vista'] = uniqid();
        }

        $datos['SesionCompleta'] = SesionCliente::completa();
        try{
            echo $twig->render( $nombre . DS . 'inicio.php',$datos);
        }catch (Exception $e){
            echo RespuestasSistema::error(
                'ERROR AL CARGAR LA PLANTILLA [' . $nombre .' ]. COMUNICATE CON SOPORTE TICS.' .
                ' '.print_r($e,true)
                );
        }
    }

}
