<?php
class AutoCargaClases{
    public static function start()
    {
        AutoCargaClases::cargarClasesSistema();
        AutoCargaClases::cargarClasesUtilidades();
        spl_autoload_register(function ($nombre_clase) {
            // echo $nombre_clase. "  <<<<<<<<        ";
            AutoCargaClases::cargarModelos(DIR_API . 'modelos'.DS, $nombre_clase);
        });
    }

    private static function cargarClasesSistema(){
        $archivos = self::cargarModelos(DIR_LIBRERIA . 'clases'.DS.'sistema'.DS);
        // print_r($archivos);
    }

    private static function cargarClasesUtilidades(){
        self::cargarModelos(DIR_LIBRERIA . 'clases'.DS.'utilidades'.DS);
    }

    private static function cargarClasesModelos(){
        self::cargarModelos(DIR_API . 'modelos'.DS);
    }

    protected static function cargarModelos($directorio, $nombreArchivo = null)
    {

        $listArchivos = array();
        if (is_dir($directorio)){
            if(!is_null($nombreArchivo)){
                $listArchivos = self::buscarArchivos($directorio, array($nombreArchivo) );
            }else{
                $listArchivos = self::buscarArchivos($directorio);
            }

        // var_dump($listArchivos);
        // echo "<br />";
            foreach ($listArchivos as $archivo) {
                $Ext = pathinfo($archivo, PATHINFO_EXTENSION);
                if ($Ext == "php") {
                    try {
                        require_once $archivo;
                    }
                    catch (Exception $e) {
                        var_dump($e);
                    }
                }
            }
        }
        // krumo($listArchivos);
    }

    private static function buscarCarpetas($directorio)
    {
        $listDireccionCarpetas = array();
        if (is_dir($directorio)):
            $openDirectorio = scandir($directorio);
            foreach ($openDirectorio as $key => $componente):
                if (!in_array($componente, array(
                    '.',
                    '..'
                ))):
                    if (is_dir($directorio . $componente)):
                        array_push($listDireccionCarpetas, $directorio . $componente . '/');
                    endif;
                endif;
            endforeach;
        endif;
        return $listDireccionCarpetas;
    }

    private static function buscarArchivos($carpetas, $listArchivos = array() )
    {
        // print_r($listArchivos);
            // echo "buscando archivos en $carpetas ".date("ymdhisu").".....<br />";
            if (is_dir($carpetas)):
                $openCarpetas = scandir($carpetas);
                foreach ($openCarpetas as $key => $nombre):
                    if (!in_array($nombre, array('.','..'))):
                        if (is_file($carpetas . $nombre)):
                            array_push($listArchivos, $carpetas . $nombre);
                        else:
                            $listArchivos = self::buscarArchivos($carpetas.$nombre.DS, $listArchivos);
                        endif;
                    else:

                    endif;
                endforeach;
            endif;

        return $listArchivos;
    }
}
AutoCargaClases::start();