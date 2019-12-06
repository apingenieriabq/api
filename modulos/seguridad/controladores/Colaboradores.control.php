<?php

class ColaboradoresControlador extends Controladores {


  function mostrarTodos(){            
    $C = new Colaboradores();
    return Respuestassistema::exito("Todos los Colaboradoes para la gestión",$C->todosParaLaTablaGestion());
  }

  function guardarNuevo() {
      print_r($_SERVER);
  }
}