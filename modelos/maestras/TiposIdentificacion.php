<?php

class TiposIdentificacion extends ModeloDatos {
  public function __construct($tipoIdentificacionID = null) {
    return parent::__construct('TiposIdentificacion', 'tipoIdentificacionID', $tipoIdentificacionID);
  }
}