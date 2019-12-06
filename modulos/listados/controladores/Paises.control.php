<?php

class PaisesControlador extends Controladores {

  function todos(){
    $P = new Paises();
    return Respuestassistema::exito("Todos los Paises",$P->todos());
  }

  function todosCompletos(){
    $P = new Paises();
    return Respuestassistema::exito("Todos los Paises",$P->todosCompletos());
  }

}