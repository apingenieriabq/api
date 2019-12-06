<?php

class ParametrosControlador extends Controladores
{
    public function mostrarListado()
    {
      $P = new Parametros();
      return Respuestassistema::exito("Todos los parametros del sistema",$P->todos());
    }

    public function valor()
    {
        if (empty($this->parametroCODIGO)) {
            return Respuestassistema::error("No llego el CODIGO DEL PARAMETRO [parametroCODIGO] para consultar su valor. Verifique los datos, o contacte al Centro TICS.");
        } else {
            $valor = Parametros::valor($this->parametroCODIGO);
            if(empty($valor)){
              return Respuestassistema::error("No existe un parametro para el código [".$this->parametroCODIGO."].");
            }else{
              return Respuestassistema::exito("Valor del Parametro ".$this->parametroCODIGO, $valor);
            }
        }
    }
    public function valores()
    {
        if (empty($this->parametrosCODIGOS)) {
            return Respuestassistema::error("No llego el CODIGO DEL PARAMETRO [parametroCODIGO] para consultar su valor. Verifique los datos, o contacte al Centro TICS.");
        } else {
          $valores = array();
          if(count($this->parametrosCODIGOS)){
            foreach($this->parametrosCODIGOS as $parametroCODIGO){
              array_push($valores, Parametros::valor($parametroCODIGO) );
            }
          }

            if(empty($valores)){
              return Respuestassistema::error("No existe un parametro para los códigos enviados.");
            }else{
              return Respuestassistema::exito("Valores de la Parametros enviados.", $valores);
            }
        }
    }
    public function datos()
    {
        if (empty($this->parametroID)) {
            return Respuestassistema::error("No llego parametroID. Verifique los datos, o contacte al Centro TICS.");
        } else {
            $Parametro = new Parametros($this->parametroID);
            return Respuestassistema::exito("Datos para el Parametro ".$this->parametroID,$Parametro);
        }
    }

    public function tiposParametros()
    {
      return Respuestassistema::exito("Listado de tipos de parametros", Parametros::tipos() );
    }

    public function nuevo()
    {

      if(empty($this->parametroTIPO) or empty($this->parametroCODIGO) or empty($this->parametroVALOR)){
        $errores = '';
        if(empty($this->parametroTIPO)){
          $errores .= 'Es necsario enviar el TIPO DE PARAMETRO [parametroTIPO]. ';
        }
        if(empty($this->parametroCODIGO)){
          $errores .= 'Es necsario enviar el CODIGO DEL PARAMETRO [parametroCODIGO]. ';
        }
        if(empty($this->parametroVALOR)){
          $errores .= 'Es necsario enviar el VALOR DEL PARAMETRO [parametroVALOR]. ';
        }
        return Respuestassistema::error("No llegarón los datos necesario para la operación. <br />" . $errores);
      }else{
        $creado = Parametros::guardar($this->parametroTIPO,$this->parametroCODIGO,
          isset($this->parametroTITULO) ? $this->parametroTITULO : null,
          isset($this->parametroDESCRIPCION) ? $this->parametroDESCRIPCION : null,
          $this->parametroVALOR
        );
        if ($creado) {
            return Respuestassistema::exito(null, new Parametros($creado));
        } else {
            return Respuestassistema::error("No se pudo guardar el nuevo parametro");
        }
      }

    }

    public function actualizar(){
      if (!empty($this->parametroID)) {

        if(empty($this->parametroTIPO) or empty($this->parametroCODIGO) or empty($this->parametroVALOR)){
          $errores = '';
          if(empty($this->parametroTIPO)){
            $errores .= 'Es necsario enviar el TIPO DE PARAMETRO [parametroTIPO]. ';
          }
          if(empty($this->parametroCODIGO)){
            $errores .= 'Es necsario enviar el CODIGO DEL PARAMETRO [parametroCODIGO]. ';
          }
          if(empty($this->parametroVALOR)){
            $errores .= 'Es necsario enviar el VALOR DEL PARAMETRO [parametroVALOR]. ';
          }
          return Respuestassistema::error("No llegarón los datos necesario para la operación. <br />" . $errores);
        }else{
          $Parametro = new Parametros($this->parametroID);
          $actualizado = Parametros::actualizar(
              $this->parametroID,
              $this->parametroTIPO,
              $this->parametroCODIGO,
              isset($this->parametroTITULO) ? $this->parametroTITULO : $Parametro->parametroTITULO,
              isset($this->parametroDESCRIPCION) ? $this->parametroDESCRIPCION : $Parametro->parametroDESCRIPCION,
              $this->parametroVALOR
          );
          if ($actualizado>0) {
              return Respuestassistema::exito(null, new Parametros($this->parametroID));
          } else {
              return Respuestassistema::error("No se actualizó el paramtro. Verifique los datos, o contacte al Centro TICS.");
          }
        }
      }else{
        return Respuestassistema::error('Es necsario enviar el ID DEL PARAMETRO [parametroID] para poder actualizar su valor. ');
      }
    }


}
