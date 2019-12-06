<?php

class Pruebas extends ModeloDatos {

    public function __construct($pruebaID = null) {
        return parent::__construct('Pruebas', 'pruebaID ', $pruebaID );
    }


}
