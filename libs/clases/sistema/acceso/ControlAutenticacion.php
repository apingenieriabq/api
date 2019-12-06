<?php

class ControlAutenticacion extends \Slim\Middleware\HttpBasicAuthentication {

  function __construct($SENTIDO = 'ENTRAR') {
    parent::__construct(([
      "secure" => false,
      'realm' => 'Protected',
      "ignore" => ["","/","api/", "/api"],
      "authenticator" => new Autenticador($SENTIDO),
      "error" => function ($request, $response, $arguments) {
          $data = [];
          $data["status"] = "error";
          $data["message"] = $arguments["message"];
          return $response->write(json_encode($data));
      }
    ]));
  }

}