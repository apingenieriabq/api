<?php

class ApiControlador extends Controladores {

  function inicio(){
  }

  function probandoRecepcionPOST(){
    echo RespuestasSistema::exito( "Recepción de datos: POST. ", $_POST);
  }

}