<?php
class Controladores {

    public function __construct() {
        if (isset($_POST) and ! empty($_POST)){
            foreach ($_POST as $variable => $valor) {
                // ($variable === 'controlador' or $variable === 'operacion' or $variable === 'componente')
                //  continue;
                $this->$variable = $valor;
                $variable = str_replace("-","_",$variable);
                $this->$variable = $valor;
            }
        }
        if (isset($_FILES) and ! empty($_FILES)){
            $cntArchivos = 0;
            foreach ($_FILES as $variable => $valor) {
                if( is_array($valor['name']) ){
                    $this->$variable = array();
                    foreach ($valor['name'] as $nombre) {
                        if( $valor['size'][$cntArchivos] > 0 ){
                            $archivo = array();
                            $archivo['name'] = $valor['name'][$cntArchivos];
                            $archivo['type'] = $valor['type'][$cntArchivos];
                            $archivo['tmp_name'] = $valor['tmp_name'][$cntArchivos];
                            $archivo['error'] = $valor['error'][$cntArchivos];
                            $archivo['size'] = $valor['size'][$cntArchivos];
                            array_push( $this->$variable, $archivo );
                            $cntArchivos++;
                        }
                    }

                }else{
                    if( $valor['size'] > 0 ){
                        $this->$variable = $valor;
                    }
                }
            }
        }
    }


    protected function validarDatosEnviados($datos = array()){
        $errores = '';
        foreach($datos as $nombreVariable ){
            if( empty($this->$nombreVariable) ){
                $errores .= 'Es obligatorios enviar el parametro <b>['.$nombreVariable.']</b>.<br />';
            }
        }
        return $errores;
    }

    protected function verificar($nombreVariable, $valorDefault = null){
        if(isset($this->$nombreVariable) ){
            if(!empty($valorDefault) and empty($this->$nombreVariable)){
                return $valorDefault;
            }else{
                return $this->$nombreVariable;
            }
        }
        return $valorDefault;
    }

}