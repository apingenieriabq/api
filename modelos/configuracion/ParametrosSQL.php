<?php




class ParametrosSQL
{
    const DATOS_COMPLETOS = <<<sql
    SELECT
    	Parametros.parametroID,
    	Parametros.parametroTIPO,
    	Parametros.parametroCODIGO,
    	Parametros.parametroTITULO,
    	Parametros.parametroDESCRIPCION,
    	Parametros.parametroVALOR
    FROM
    	Parametros
sql;

    const TIPOS_PARAMETROS = <<<sql
    SHOW COLUMNS FROM Parametros LIKE 'parametroTIPO'
sql;


    const CREAR_REGISTRO = <<<sql
    INSERT INTO Parametros (
      parametroTIPO,
      parametroCODIGO,
      parametroTITULO,
      parametroDESCRIPCION,
      parametroVALOR,
      parametroFCHCREADO,
      parametroUSRCREA
    )
    VALUES
      (
        ?, ?, ?, ?, ?, NOW(), ? );

sql;


    const ASIGNAR_APLICACION = <<<sql
    INSERT INTO ParametrosAplicaciones (
      aplicacionID,
      parametroID,
      parametroAplicacionFCHACTIVO,
      parametroAplicacionUSRACTIVA
    )
    VALUES
      ( ?, ?, NOW(), ? );
sql;
    /**
     * Consulta SQL que ACTUALIZA los datos básicos de un registro
     */
    const ACTUALIZAR_REGISTRO = <<<sql
    UPDATE
        Parametros
    SET
      parametroTIPO = ?,
      parametroCODIGO = ?,
      parametroTITULO = ?,
      parametroDESCRIPCION = ?,
      parametroVALOR = ?,
      parametroFCHMODIFICADO = NOW(),
      parametroUSRMODIFICA = ?
    WHERE parametroID = ?;
sql;
    /**
     * Consulta SQL que permite ELIMINAR un registro
     */
    const ELIMINAR_REGISTRO = "DELETE FROM Parametros WHERE parametroID = ?;";

    const ELIMINAR_REGISTRO_ASIGNACION_APLICACION = "DELETE FROM ParametrosAplicaciones WHERE aplicacionID = ? AND parametroID = ?;";
}
