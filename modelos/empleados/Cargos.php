<?php

class Cargos extends ModeloDatos {

    public function __construct($cargoID = null) {
        return parent::__construct('Cargos', 'cargoID', $cargoID);
    }

    function todosCompletos() {
        $SQL = 'SELECT `UnidadesDivisiones`.* , `TiposCargos`.* , `Cargos`.* FROM `Cargos` LEFT JOIN `UnidadesDivisiones` ON (`Cargos`.`unidadID` = `UnidadesDivisiones`.`unidadID`) LEFT JOIN `TiposCargos` ON (`Cargos`.`tipoCargoID` = `TiposCargos`.`tipoCargoID`) ';
        $listaCargos = $this->consultaMUCHOS($SQL);
//        $Cargos = array();
        foreach ($listaCargos as $i => $Cargo) {
            $listaCargos[$i]->Padre = null;
            if (!empty($Cargo->cargoPADRE)) {
                $listaCargos[$i]->Padre = new Cargos($Cargo->cargoPADRE);
            }
        }
        return $listaCargos;
    }

}
