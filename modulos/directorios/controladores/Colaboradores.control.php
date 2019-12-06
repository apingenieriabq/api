<?php

class ColaboradoresControlador extends Controladores {

    public $paginado = 6;

    public function buscar() {
        $Colaboradores = new Colaboradores();
        $validacion = $this->validarDatosEnviados(['palabras_buscar']);
        if (empty($validacion)) {
            $Colaboradores = new Colaboradores();
            $Resultado = $Colaboradores->buscarPorCedulaNombresApellidos($this->palabras_buscar);
            return Respuestassistema::exito("Datos para El Directorio de Colaboradores", ['Paginado' => $this->paginado, 'Colaboradores' => $Resultado]
            );
        } else {
            return Respuestassistema::error("No llegarón los datos para la busqueda.");
        }
    }

    public function datosParaNavegador() {
        $Colaboradores = new Colaboradores();
        $Navegador = $Colaboradores->navegador($this->paginado);
        $validacion = $this->validarDatosEnviados(['pagina']);
        if (empty($validacion)) {
            $ini = $this->pagina * $this->paginado;
            $fin = $ini + $this->paginado;
            $Pagina1 = $Colaboradores->limiteDesdeHasta($ini, $this->paginado);
        } else {
            $this->pagina = 0;
            $Pagina1 = $Colaboradores->limiteDesdeHasta(0, $this->paginado);
        }
        return Respuestassistema::exito("Datos para El Directorio de Colaboradores",
                                        ['Navegador' => $Navegador, 'Paginado' => $this->paginado, 'Colaboradores' => $Pagina1, 'PaginaActual' => $this->pagina]
        );
    }

    public function enviarPapelera() {
        $validacion = $this->validarDatosEnviados(['colaboradorID']);
        if (empty($validacion)) {
            $Colaborador = new Colaboradores($this->colaboradorID);
            $actualizado = $Colaborador->actualizarESTADO('DESACTIVO');
            $User = $Colaborador->datosUsuario();
            if ($User) {
                $actualizado = $User->actualizarESTADO('DESACTIVO');
            }
            return Respuestassistema::exito("Los datos del COLABORADOR fueron enviados a la PAPELERA DE RECICLAJE.", $Colaborador);
        } else {
            return Respuestassistema::error("No llegarón los datos OBLIGATORIOS para la operación. <br />" . $validacion);
        }
    }

    public function cambiarEstado() {
        $validacion = $this->validarDatosEnviados(['colaboradorID']);
        if (empty($validacion)) {
            $Colaborador = new Colaboradores($this->colaboradorID);
            switch ($Colaborador->colaboradorESTADO) {
                case 'ACTIVO':
                    $nuevoEstado = 'SUSPENDIDO';
                    break;
                case 'SUSPENDIDO':
                    $nuevoEstado = 'ACTIVO';
                    break;
                case 'DESACTIVO':
                    $nuevoEstado = 'SUSPENDIDO';
                    break;
            }
            $actualizado = $Colaborador->actualizarESTADO($nuevoEstado);

            $User = $Colaborador->datosUsuario();
            if ($User) {
                $actualizado = $User->actualizarESTADO($nuevoEstado);
            }
            return Respuestassistema::exito("Cambió el estado de " . $Colaborador->colaboradorESTADO . " -> " . $nuevoEstado . ".", $Colaborador);
        } else {
            return Respuestassistema::error("No llegarón los datos OBLIGATORIOS para la operación. <br />" . $validacion);
        }
    }

    public function cambiarVisibilidad() {
        $validacion = $this->validarDatosEnviados(['colaboradorID']);
        if (empty($validacion)) {
            $Doc = new Colaboradores($this->colaboradorID);
            switch ($Doc->colaboradorPUBLICADO) {
                case 'SI':
                    $actualizado = $Doc->actualizarVISIBILIDAD('NO');
                    return Respuestassistema::exito("Cambió la visibilidad de PUBLICADO -> NO PUBLICADO.", $Doc);
                    break;
                case 'NO':
                    $actualizado = $Doc->actualizarVISIBILIDAD('SI');
                    return Respuestassistema::exito("Cambió la visibilidad de NO PUBLICADO -> PUBLICADO.", $Doc);
                    break;
            }
        } else {
            return Respuestassistema::error("No llegarón los datos OBLIGATORIOS para la operación. <br />" . $validacion);
        }
    }

    public function cambiarSeguridad() {
        $validacion = $this->validarDatosEnviados(['colaboradorID']);
        if (empty($validacion)) {
            $Doc = new Colaboradores($this->colaboradorID);
            switch ($Doc->colaboradorPUBLICO) {
                case 'SI':
                    $actualizado = $Doc->actualizarSEGURIDAD('NO');
                    return Respuestassistema::exito("Cambió la visibilidad de PUBLICO -> RESTRINGIDO.", $Doc);
                    break;
                case 'NO':
                    $actualizado = $Doc->actualizarSEGURIDAD('SI');
                    return Respuestassistema::exito("Cambió la seguridad de RESTRINGIDO -> PUBLICO.", $Doc);
                    break;
            }
        } else {
            return Respuestassistema::error("No llegarón los datos OBLIGATORIOS para la operación. <br />" . $validacion);
        }
    }

    public function datosCompletosConPermisos() {
        $validacion = $this->validarDatosEnviados(['colaboradorID']);
        if (empty($validacion)) {
            $Colaborador = new Colaboradores();
            $Colaborador->datosCompletosConPermisos($this->colaboradorID);
            return Respuestassistema::exito("Los datos completos del COLABORADOR.", $Colaborador);
        } else {
            return Respuestassistema::error("No llegarón los datos OBLIGATORIOS para la operación. <br />" . $validacion);
        }
    }

    public function datosCompletos() {
        $validacion = $this->validarDatosEnviados(['colaboradorID']);
        if (empty($validacion)) {
            $Colaborador = new Colaboradores();
            $Colaborador->datosCompletos($this->colaboradorID);
            return Respuestassistema::exito("Los datos completos del COLABORADOR.", $Colaborador);
        } else {
            return Respuestassistema::error("No llegarón los datos OBLIGATORIOS para la operación. <br />" . $validacion);
        }
    }

    function porCargo() {
        $P = new Colaboradores();
        return Respuestassistema::exito("Todos los Colaboradoes con cargo de AP INGENENIERIA", $P->conCargo($this->cargoID)
        );
    }

    function todos() {
        $P = new Colaboradores();
        return Respuestassistema::exito("Todos los Colaboradoes de AP INGENENIERIA", $P->todos());
    }

    function todosCompletos() {
        $P = new Colaboradores();
        return Respuestassistema::exito("Todos los Colaboradoes de AP INGENENIERIA", $P->todosCompletos());
    }

    public function datos() {
        if (empty($this->colaboradorID)) {
            return Respuestassistema::error("No llego colaboradorID. Verifique los datos, o contacte al Centro TICS.");
        } else {
            return Respuestassistema::exito("Datos para el Colaborador " . $this->colaboradorID, new Colaboradores($this->colaboradorID));
        }
    }

    public function guardarCambios() {
        // echo ">        ";print_r($this);
        if (empty($this->colaboradorID)) {
            return $this->nuevo();
        } else {
            return $this->actualizar();
        }
    }

    private function nuevo() {
        $validacion = $this->validarDatosEnviados(
          ['tipoIdentificacionID', 'personaIDENTIFICACION', 'personaNOMBRES', 'personaAPELLIDOS', 'personaEMAIL', 'cargoID', 'tipoColaboradorID', 'usuarioNOMBRE']
        );
        if (empty($validacion)) {

            $URL_FOTO = URL_API . 'media/img/usuario-invitado.jpg';
            if (isset($this->colaboradorFOTO)) {
                $DIR_FOTOS_COLABORADORES = 'colaboradores' . DS . $this->personaIDENTIFICACION . DS . 'fotos' . DS;
                $NOMBRE_FOTO = "" . uniqid() . "." . Archivos::extension($this->colaboradorFOTO);
                $movido = Archivos::moverArchivoRecibido(
                    $this->colaboradorFOTO, DIR_ARCHIVOS . $DIR_FOTOS_COLABORADORES, $NOMBRE_FOTO
                );
                if ($movido) {
                    $URL_FOTO = URL_ARCHIVOS . $DIR_FOTOS_COLABORADORES . $NOMBRE_FOTO;
                }
            }

            $URL_FIRMA = URL_API . 'media/img/firma.png';
            if (isset($this->colaboradorFIRMA)) {
                $DIR_FIRMAS_COLABORADORES = 'colaboradores' . DS . $this->personaIDENTIFICACION . DS . 'fotos' . DS;
                $NOMBRE_FIRMA = "" . uniqid() . "." . Archivos::extension($this->colaboradorFIRMA);
                $movido = Archivos::moverArchivoRecibido(
                    $this->colaboradorFIRMA, DIR_ARCHIVOS . $DIR_FIRMAS_COLABORADORES, $NOMBRE_FIRMA
                );
                if ($movido) {
                    $URL_FIRMA = URL_ARCHIVOS . $DIR_FIRMAS_COLABORADORES . $NOMBRE_FIRMA;
                }
            }

            $Persona = new Personas($this->tipoIdentificacionID, $this->personaIDENTIFICACION);
            if (empty($Persona->personaID)) {
                $Persona->personaID = $Persona->crear(
                  $this->tipoIdentificacionID, $this->personaIDENTIFICACION, $this->personaNOMBRES, $this->personaAPELLIDOS, $this->personaMUNICIPIO, $this->personaDIRECCION,
                  $this->personaEMAIL, $this->personaTELEFONO, $this->personaCELULAR, $this->personaNIT, $URL_FOTO, $this->verificar('personaSEXO'),
                                                                                                                                     $this->verificar('personaFCHNACIMIENTO'),
                                                                                                                                                      $this->verificar('personaTIPOSANGRE')
                );
            }
            if (!empty($Persona->personaID)) {
                $Colaborador = new Colaboradores($this->verificar('colaboradorEMAIL', $this->personaEMAIL));
                if (empty($Colaborador->colaboradorID)) {
                    $Colaborador->crear(
                      $this->cargoID, $Persona->personaID, $this->tipoColaboradorID, $this->verificar('colaboradorEMAIL', $this->personaEMAIL),
                                                                                                      $this->verificar('colaboradorEXTENSION'),
                                                                                                                       $this->verificar('colaboradorCELULAR'),
                                                                                                                                        $this->verificar('colaboradorFCHINGRESO',
                                                                                                                                                         date('Y-m-d')), $URL_FOTO,
                                                                                                                                                              $URL_FIRMA,
                                                                                                                                                              $this->verificar('colaboradorJEFEINMEDIATO'),
                                                                                                                                                                               $this->verificar('colaboradorAPROBADOR')
                    );
                } else {
                    $Colaborador->datosCompletos();
                    // return Respuestassistema::fallo("El Colaborador ya está registrado en el sistema con el cargo <b>[".$Colaborador->Cargo->cargoTITULO."]</b>.");
                }

                if (!empty($Colaborador->colaboradorID)) {
                    $Usuario = new Usuarios();
                    $Usuario->porNombre($this->usuarioNOMBRE);


                    if (empty($Usuario->usuarioID) or $Usuario->usuarioID == 0) {
                        $usuarioHASH = empty($this->usuarioCLAVE) ? hash('crc32', $this->usuarioNOMBRE) : $this->usuarioCLAVE;
                        $Usuario->nuevo($this->usuarioNOMBRE, $usuarioHASH, $Colaborador->colaboradorID);
                    } else {
                        return Respuestassistema::fallo("El nombre de usuario <b>[" . $this->usuarioNOMBRE . "]</b> ya está registrado en el sistema.");
                    }
                    if (!empty($Usuario->usuarioID)) {
                        $Usuario->datosCompletos();
                        $Colaborador->Usuario = $Usuario;
                        return Respuestassistema::exito('Nuevo Usuario/Colaborador creado Exitosamente. Los datos de inicio son: <br /> Usuario: <b>' . $Usuario->usuarioNOMBRE . '</b><br /> Clave: <b>' . $usuarioHASH . '</b>',
                                                        $Colaborador);
                    } else {
                        return Respuestassistema::error("No se pudo guardar los datos de USUARIO.");
                    }
                } else {
                    return Respuestassistema::error("No se pudo guardar el nuevo COLABORADOR");
                }
            } else {
                return Respuestassistema::error("No se pudo guardar los datos personales del nuevo COLABORADOR");
            }
        } else {
            return Respuestassistema::error("No llegarón los datos OBLIGATORIOS para la operación. <br />" . $validacion);
        }
    }

    private function actualizar() {

        $validacion = $this->validarDatosEnviados(
          ['colaboradorID', 'tipoIdentificacionID', 'cargoID', 'tipoColaboradorID', 'tipoIdentificacionID', 'personaIDENTIFICACION', 'colaboradorEMAIL', 'personaNOMBRES', 'personaAPELLIDOS']
        );
        if (empty($validacion)) {


            $URL_FOTO_COLABORADOR = null;
            if (isset($this->colaboradorFOTO)) {



                $DIR_FOTOS_COLABORADORES = 'colaboradores' . DS . $this->personaIDENTIFICACION . DS . 'fotos' . DS;
                $NOMBRE_FOTO = "" . uniqid() . "." . Archivos::extension($this->colaboradorFOTO);
                $movido = Archivos::moverArchivoRecibido(
                    $this->colaboradorFOTO, DIR_ARCHIVOS . $DIR_FOTOS_COLABORADORES, $NOMBRE_FOTO
                );
                if ($movido) {
                    $URL_FOTO_COLABORADOR = URL_ARCHIVOS . $DIR_FOTOS_COLABORADORES . $NOMBRE_FOTO;
                }
            }
            $URL_FIRMA = null;
            if (isset($this->colaboradorFIRMA)) {
                $DIR_FIRMAS_COLABORADORES = 'colaboradores' . DS . $this->personaIDENTIFICACION . DS . 'fotos' . DS;
                $NOMBRE_FIRMA = "" . uniqid() . "." . Archivos::extension($this->colaboradorFIRMA);
                $movido = Archivos::moverArchivoRecibido(
                    $this->colaboradorFIRMA, DIR_ARCHIVOS . $DIR_FIRMAS_COLABORADORES, $NOMBRE_FIRMA
                );
                if ($movido) {
                    $URL_FIRMA = URL_ARCHIVOS . $DIR_FIRMAS_COLABORADORES . $NOMBRE_FIRMA;
                }
            }

            $Persona = new Personas($this->tipoIdentificacionID, $this->personaIDENTIFICACION);
            $URL_FOTO = is_null($URL_FOTO_COLABORADOR) ? $Persona->personaIMAGEN : $URL_FOTO_COLABORADOR;
            if (empty($Persona->personaID)) {
                $Persona->personaID = $Persona->crear(
                  $this->tipoIdentificacionID, $this->personaIDENTIFICACION, $this->personaNOMBRES, $this->personaAPELLIDOS, $this->personaMUNICIPIO, $this->personaDIRECCION,
                  $this->personaEMAIL, $this->personaTELEFONO, $this->personaCELULAR, $this->personaNIT, $URL_FOTO, $this->verificar('personaSEXO'),
                                                                                                                                     $this->verificar('personaFCHNACIMIENTO'),
                                                                                                                                                      $this->verificar('personaTIPOSANGRE')
                );
            } else {
                $Persona->modificar(
                  $this->tipoIdentificacionID, $this->personaIDENTIFICACION, $this->personaNOMBRES, $this->personaAPELLIDOS, $this->personaMUNICIPIO, $this->personaDIRECCION,
                  $this->personaEMAIL, $this->personaTELEFONO, $this->personaCELULAR, $this->personaNIT, $URL_FOTO, $this->verificar('personaSEXO'),
                                                                                                                                     $this->verificar('personaFCHNACIMIENTO'),
                                                                                                                                                      $this->verificar('personaTIPOSANGRE'),
                                                                                                                                                                       $Persona->personaID
                );
            }

            if (!empty($Persona->personaID)) {
                $Colaborador = new Colaboradores($this->colaboradorID);
                $URL_FOTO = is_null($URL_FOTO_COLABORADOR) ? $Colaborador->colaboradorFOTO : $URL_FOTO_COLABORADOR;
                $URL_FIRMA = is_null($URL_FIRMA) ? $Colaborador->colaboradorFIRMA : $URL_FIRMA;
                $actualizoColaborador = $Colaborador->modificar(
                  $this->cargoID, $Persona->personaID, $this->tipoColaboradorID, $this->verificar('colaboradorEMAIL', $this->personaEMAIL),
                                                                                                  $this->verificar('colaboradorEXTENSION'), $this->verificar('colaboradorCELULAR'),
                                                                                                                                                             $this->verificar('colaboradorFCHINGRESO',
                                                                                                                                                                              date('Y-m-d')),
                                                                                                                                                                                   $URL_FOTO,
                                                                                                                                                                                   $URL_FIRMA,
                                                                                                                                                                                   $this->verificar('colaboradorJEFEINMEDIATO'),
                                                                                                                                                                                                    $this->verificar('colaboradorAPROBADOR'),
                                                                                                                                                                                                                     $this->colaboradorID
                );

                if (!empty($Colaborador->colaboradorID)) {

                    $Usuario = new Usuarios();
                    $Usuario->porNombre($this->usuarioNOMBRE);
                    if (empty($Usuario->usuarioID) or $Usuario->usuarioID == 0) {

                        $usuarioHASH = empty($this->usuarioCLAVE) ? hash('crc32', $this->usuarioNOMBRE) : $this->usuarioCLAVE;
                        $Usuario->nuevo($this->usuarioNOMBRE, $usuarioHASH, $Colaborador->colaboradorID);
                    } else {
                        if ($Usuario->usuarioID == $this->usuarioID) {
                            if (empty($this->usuarioCLAVE)) {
                                $Usuario->modificarSinClave($this->usuarioNOMBRE, $Colaborador->colaboradorID, $this->usuarioID);
                            } else {
                                $Usuario->modificar($this->usuarioNOMBRE, $this->usuarioCLAVE, $Colaborador->colaboradorID, $this->usuarioID);
                            }
                        } else {
                            return Respuestassistema::fallo("El nombre de usuario <b>[" . $this->usuarioNOMBRE . "]</b> ya está registrado en el sistema. Intenta con otro nombre.",
                                                            $Colaborador);
                        }
                    }
                    if (!empty($Usuario->usuarioID)) {
                        $Usuario->datosCompletos();
                        $Colaborador->Usuario = $Usuario;

                        $Usuario->asignarConfidencialidad(explode(",", $this->listadoCONFIDENCIALIDAD));
                        $Usuario->asignarPermisos(explode(",", $this->listadoPERMISOS));


                        return Respuestassistema::exito('Se ha ACTUALIZADO Usuario/Colaborador [<b>' . $Usuario->usuarioNOMBRE . '</b>] Exitosamente.',
                                                        ['colaboradorID' => $Colaborador->colaboradorID]);
                    } else {
                        return Respuestassistema::error("No se pudo actualizar los datos de USUARIO para el COLABORADOR");
                    }
                } else {
                    return Respuestassistema::error("No fue posible actualizar los datos del COLABORADOR");
                }
            } else {
                return Respuestassistema::error("No se pudo guardar el actualizar los datos de la persona asociada como COLABORADOR");
            }
        } else {
            return Respuestassistema::error("No llegarón los datos OBLIGATORIOS para la operación. <br />" . $validacion);
        }
    }

    public function activar() {
        if (empty($this->colaboradorID)) {
            return Respuestassistema::error("No llego colaboradorID. Verifique los datos, o contacte al Centro TICS.");
        } else {
            $Colaborador = new Colaboradores($this->colaboradorID);
            $Colaborador->activar();
            return Respuestassistema::exito("Colaborador " . $this->colaboradorID . " ACTIVADO.", $Colaborador);
        }
    }

    public function desactivar() {
        if (empty($this->colaboradorID)) {
            return Respuestassistema::error("No llego colaboradorID. Verifique los datos, o contacte al Centro TICS.");
        } else {
            $Colaborador = new Colaboradores($this->colaboradorID);
            $Colaborador->desactivar();
            return Respuestassistema::exito("Colaborador " . $this->colaboradorID . " DESACTIVADO.", $Colaborador);
        }
    }

    public function suspender() {
        if (empty($this->colaboradorID)) {
            return Respuestassistema::error("No llego colaboradorID. Verifique los datos, o contacte al Centro TICS.");
        } else {
            $Colaborador = new Colaboradores($this->colaboradorID);
            $Colaborador->suspender();
            return Respuestassistema::exito("Colaborador " . $this->colaboradorID . " SUSPENDIDO.", $Colaborador);
        }
    }

}
