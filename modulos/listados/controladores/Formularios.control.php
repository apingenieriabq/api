<?php

class FormulariosControlador extends Controladores {

  function formularioUsuarioColaborador(){
    $TiposColaboradores = new TiposColaboradores();
    $TiposIDs = new TiposIdentificacion();
    $Paises = new Paises();
    $Departamentos = new Departamentos();
    $Municipios = new Municipios();
    $Cargos = new Cargos();
    $Colaboradores = new Colaboradores();
    $Permisos = new MenuOperaciones();
    $Institucional = new ProcesosAP();
    return Respuestassistema::exito("Listados",[
      'TiposColaboradores' => $TiposColaboradores->todos(),
      'TiposIdentificacion' => $TiposIDs->todos(),
      'Paises' => $Paises->todos(),
      'Departamentos' => $Departamentos->todos(),
      'Municipios' => $Municipios->todos(),
      'Cargos' => $Cargos->todos(),
      'Colaboradores' => $Colaboradores->todosCompletos(),
      'Permisos' => $Permisos->arbolCompleto(),
      'Institucional' => $Institucional->todosCompletos(),
    ]);
  }

  function formularioPerfilUsuario(){
    $Paises = new Paises();
    $Departamentos = new Departamentos();
    $Municipios = new Municipios();
    return Respuestassistema::exito("Listados",[
      'Paises' => $Paises->todos(),
      'Departamentos' => $Departamentos->todos(),
      'Municipios' => $Municipios->todos(),
    ]);

  }

}