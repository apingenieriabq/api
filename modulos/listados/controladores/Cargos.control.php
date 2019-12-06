<?php

class CargosControlador extends Controladores {

  function todos(){
    $P = new Cargos();
    return Respuestassistema::exito("Todos los cargos registrados en el sistema",$P->todos());
  }
  function todosCompletos(){
    $P = new Cargos();
    return Respuestassistema::exito("Todos los cargos registrados en el sistema",$P->todosCompletos());
  }

}