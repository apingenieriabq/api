<?php

class Unidades extends ModeloDatos {

    public function __construct($unidadID = null) {
        return parent::__construct('UnidadesDivisiones', 'unidadID', $unidadID);
    }

    function todosCompletos() {
        $Unidades = $this->todos();
        foreach ($Unidades as $i => $Unidad) {
            $Unidades[$i]->Padre = null;
            if (!empty($Unidad->unidadCODIGO)) {
                $Unidades[$i]->Padre = new Unidades($Unidad->unidadPADRE);
            }
        }
        return $Unidades;
    }

}
