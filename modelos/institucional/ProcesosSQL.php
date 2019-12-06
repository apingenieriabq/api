<?php

class ProcesosSQL
{
    const DATOS_COMPLETOS = <<<sql
    SELECT
    	Procesos.*
    FROM
    	ProcesosAP
sql;

    const CREAR_REGISTRO = <<<sql
    INSERT INTO ProcesosAP (
      procesoCODIGO,
      procesoTITULO,
      procesoDESCRIPCION,
      procesoRESPONSABLE,
      procesoFCHACTUALIZACION,
      procesoUSRCREA
    )
    VALUES  ( ?, ?, ?, ?, CURRENT_TIMESTAMP, ? );

sql;

    /**
     * Consulta SQL que ACTUALIZA los datos básicos de un registro
     */
    const ACTUALIZAR_REGISTRO = <<<sql
    UPDATE
        ProcesosAP
    SET
      procesoCODIGO = ?,
      procesoTITULO = ?,
      procesoDESCRIPCION = ?,
      procesoRESPONSABLE = ?,
      procesoFCHACTUALIZACION = NOW(),
      procesoUSRMODIFICA = ?
    WHERE procesoID = ?;
sql;

    /**
     * Consulta SQL que permite ELIMINAR un registro
     */
    const ELIMINAR_REGISTRO = "DELETE FROM ProcesosAP WHERE procesoID = ?;";
}
