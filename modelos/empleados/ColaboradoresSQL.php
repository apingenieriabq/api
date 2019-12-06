<?php
class ColaboradoresSQL {

  const DATOS_COMPLETOS = <<<sql
  SELECT
    `ProcesosAP`.`procesoCODIGO`
    , `ProcesosAP`.`procesoTITULO`
    , `DocumentosAP`.*
    , `DatosPersonales`.`personaIDENTIFICACION`
    , `DatosPersonales`.`personaNOMBRES`
    , `DatosPersonales`.`personaAPELLIDOS`
    , `CargoResponsable`.`cargoCODIGO`
    , `CargoResponsable`.`cargoTITULO`
    , `Responsable`.`colaboradorEMAIL`
    , `Usuarios`.`usuarioNOMBRE`
  FROM
      `DocumentosAP` 
      INNER JOIN `ProcesosAP`
          ON (`DocumentosAP`.`procesoID` = `ProcesosAP`.`procesoID`)
      LEFT JOIN `Colaboradores` AS `Responsable`
          ON (`DocumentosAP`.`documentoRESPONSABLE` = `Responsable`.`colaboradorID`)
      LEFT JOIN `Usuarios`
          ON (`DocumentosAP`.`documentoUSRACTUALIZACION` = `Usuarios`.`usuarioID`)
      LEFT JOIN `Cargos` AS `CargoResponsable`
          ON (`Responsable`.`cargoID` = `CargoResponsable`.`cargoID`)
      LEFT JOIN `Personas` AS `DatosPersonales`
          ON (`Responsable`.`personaID` = `DatosPersonales`.`personaID`)

sql;

}