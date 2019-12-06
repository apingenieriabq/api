<?php

class OperacionesControlador extends Controladores {

  public function datosOperacion(){
        if (!empty($this->modulo) and !empty($this->operacion)){
          $Operacion = MenuOperaciones::porCombinacion($this->modulo, $this->operacion );
          if($Operacion){
                return Respuestassistema::exito("Datos de la Operacion.",$Operacion);
          }
        }
        return Respuestassistema::error("Los datos no son validos");;
  }

    public static function arbolCompleto() {
        $menuComponente = Componentes::todos();
        // print_r($menuComponente);
        foreach ($menuComponente as $componentes):
            $componentes->Operaciones = MenuOperaciones::padresDelComponente($componentes->componenteID);
            foreach($componentes->Operaciones as $OperacionMenu){
                $OperacionMenu->SubOperaciones = MenuOperaciones::deLaOperacion($OperacionMenu->menuID);
        // echo RespuestasSistema::exito( "Todas las operación.", $OperacionMenu->SubOperaciones );die();
            }
        endforeach;
        echo RespuestasSistema::exito( "Todas las operación.", $menuComponente );
    }

}