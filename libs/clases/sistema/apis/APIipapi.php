<?php

class APIipapi extends APIS {

  static $API_AccessKey = 'b93f283af99af60eeb5fa738b15ff5ac';
  static $API_Url = 'http://api.ipapi.com/';

  static function datos($direccionIP){
    return self::llamarAPI('GET', self::$API_Url.WS.$direccionIP,
      ['access_key' => self::$API_AccessKey, 'language' => 'es']
    );
  }


}