<?php

class Variables {

  public static function info($variable){
    return json_encode($variable);
  }
  public static function imprimir($variable){
    echo self::info($variable);
  }

}