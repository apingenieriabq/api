<?php

class TiposColaboradores extends ModeloDatos {
  public function __construct($tipoColaboradorID = null) {
    return parent::__construct('TiposColaboradores', 'tipoColaboradorID', $tipoColaboradorID);
  }
}