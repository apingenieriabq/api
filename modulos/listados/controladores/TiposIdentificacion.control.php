<?php

class TiposIdentificacionControlador extends Controladores {

  function todos(){
    $P = new TiposIdentificacion();
    return Respuestassistema::exito("Todos los cargos registrados en el sistema",$P->todos());
  }

}