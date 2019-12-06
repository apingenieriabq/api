<?php

class ComponentesSQL {


    const DATOS =<<<sql
  SELECT
      MenuComponentes.* , Usuarios.*
  FROM MenuComponentes
      LEFT JOIN Usuarios ON (MenuComponentes.componenteUSRCREO = Usuarios.usuarioID)

sql;

    const DATOS_COMPLETOS =<<<sql
  SELECT
      MenuComponentes.* , Usuarios.usuarioNOMBRE ,
      COUNT(ControlControladores.controladorID) AS cantidadCONTROLADORES
  FROM MenuComponentes
      LEFT JOIN ControlControladores  ON (MenuComponentes.componenteID = ControlControladores.componenteID)
      LEFT JOIN Usuarios ON (MenuComponentes.componenteUSRCREO = Usuarios.usuarioID)

sql;


    const CREAR_REGISTRO =<<<sql

  INSERT INTO MenuComponentes ( `componenteORDEN`
  , `componenteMENU`
  , `componenteMENUICONO`
  , `componenteMENUTITULO`
  , `componenteCARPETA`
  , `componenteCODIGO`
  , `componenteTITULO`
  , `componenteESTADO`
  , `componenteDESCRIPCION`
  , `componenteUSRCREO`
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ;

sql;



    const ACTUALIZAR_REGISTRO =<<<sql

  UPDATE `MenuComponentes`
  SET
    `componenteORDEN` = ?,
    `componenteMENU` = ?,
    `componenteMENUICONO` = ?,
    `componenteMENUTITULO` = ?,
    `componenteCARPETA` = ?,
    `componenteCODIGO` = ?,
    `componenteTITULO` = ?,
    `componenteESTADO` = ?,
    `componenteDESCRIPCION` = ?

  WHERE `componenteID` = ?



sql;





}