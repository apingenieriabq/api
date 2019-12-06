<?php

class UnidadesControlador extends Controladores {

  function todos(){
    $P = new Unidades();
    return Respuestassistema::exito("Todos los cargos registrados en el sistema",$P->todos());
  }
  function todosCompletos(){
    $P = new Unidades();
    return Respuestassistema::exito("Todos los cargos registrados en el sistema",$P->todosCompletos());
  }

    function listadosFormulario() {

        $Car = new Cargos();
        $unidades = new Unidades();
        $PyC = array("Cargos" => $Car->todos(), "Unidades" => $unidades->todos());
        return Respuestassistema::exito("Datos para el Formulario de Unidades Adminsitartivas", $PyC);
    }

}