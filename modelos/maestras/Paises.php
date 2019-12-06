<?php

class Paises extends ModeloDatos {
  const COLOMBIA = 47;
  public function __construct($paisID = null) {
    return parent::__construct('Paises', 'paisID', $paisID);
  }
}