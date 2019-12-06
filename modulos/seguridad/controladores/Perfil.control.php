<?php

class PerfilControlador extends Controladores {

    public function delUsuario(){
        // print_r($_SESSION);
        if(!isset($this->usuarioID)){
            $this->usuarioID = Usuario::usuarioID();
        }
        $Usuario = new Usuarios();
        echo RespuestasSistema::exito( 'Datos del Pefil del Usuario', $Usuario->datosCompletos($this->usuarioID) );
    }

    public function actualizarDatosPersonales(){

        $validacion = $this->validarDatosEnviados(['personaID']);
        if(empty($validacion)){
          $Persona = new Personas($this->personaID);
          $Persona->modificarDatosBasicos(
              $this->personaNOMBRES, $this->personaAPELLIDOS, $this->personaMUNICIPIO,
              $this->personaDIRECCION, $this->personaEMAIL, $this->personaID
          );
          return Respuestassistema::exito("Se actualizarón los datos del colaborador.");
        }else{
          return Respuestassistema::error("No llegarón los datos OBLIGATORIOS para la operación. <br />" . $validacion);
        }
    }


}