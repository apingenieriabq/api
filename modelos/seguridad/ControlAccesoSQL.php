<?php
class ControlAccesoSQL {

    const OPERACIONES_POR_COMPONENTES = <<<sql
  SELECT
  	MenuComponentes.*,
  	MenuOperaciones.*
  FROM
  	MenuOperaciones
  LEFT JOIN MenuComponentes ON
  	MenuComponentes.componenteID = MenuOperaciones.componenteID

sql;

const DATOS_COMPLETOS = <<<sql
    SELECT
    	MenuComponentes.*,
    	MenuOperaciones.*
    FROM
    	MenuOperaciones
    LEFT JOIN MenuComponentes ON
    	MenuComponentes.componenteID = MenuOperaciones.componenteID

sql;

const DATOS_COMPLETOS_2 = <<<sql
    SELECT
    	MenuComponentes.*
    FROM
    	MenuOperaciones
    LEFT JOIN MenuComponentes ON
    	MenuComponentes.componenteID = MenuOperaciones.componenteID

sql;

    const COMPONENTES_POR_USUARIO_Y_COMPONENTES = <<<sql
    SELECT
      MenuComponentes.*
    FROM
      MenuOperaciones
      INNER JOIN MenuComponentes
        ON MenuOperaciones.componenteID = MenuComponentes.componenteID
      LEFT JOIN MenuOperacionesUsuarios
        ON MenuOperaciones.menuID = MenuOperacionesUsuarios.menuID
      LEFT JOIN Usuarios
        ON MenuOperacionesUsuarios.usuarioID = Usuarios.usuarioID
      LEFT JOIN MenuOperacionesRoles
        ON MenuOperaciones.menuID = MenuOperacionesRoles.menuID
      LEFT JOIN UsuariosRoles
        ON MenuOperacionesRoles.rolID = UsuariosRoles.rolID
      LEFT JOIN Usuarios AS UsuariosRol
        ON UsuariosRoles.usuarioID = UsuariosRol.usuarioID
sql;


    const OPERACIONES_POR_USUARIO_Y_COMPONENTES = <<<sql
    SELECT
      MenuOperaciones.*,
      MenuComponentes.*
    FROM
      MenuOperaciones
      INNER JOIN MenuComponentes
        ON MenuOperaciones.componenteID = MenuComponentes.componenteID
      LEFT JOIN MenuOperacionesUsuarios
        ON MenuOperaciones.menuID = MenuOperacionesUsuarios.menuID
      LEFT JOIN Usuarios
        ON MenuOperacionesUsuarios.usuarioID = Usuarios.usuarioID
      LEFT JOIN MenuOperacionesRoles
        ON MenuOperaciones.menuID = MenuOperacionesRoles.menuID
      LEFT JOIN UsuariosRoles
        ON MenuOperacionesRoles.rolID = UsuariosRoles.rolID
      LEFT JOIN Usuarios AS UsuariosRol
        ON UsuariosRoles.usuarioID = UsuariosRol.usuarioID
sql;

}
