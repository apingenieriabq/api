<?php
class Motor {

  static $modulo;
  static $controlador;
  static $operacion;
  static $respuesta;

  static function procesar($modulo, $controlador, $operacion, $usuarioNOMBRE = null){
    // echo 'procesando /'.$modulo.'/'.$controlador.'/'.$operacion.'/      ';
    $Usuario = new Usuarios();
    if(!is_null($usuarioNOMBRE)){
      $Usuario->porNombre($usuarioNOMBRE);
    }else{
      $Usuario =  Usuario::conectado();
    }

    global $modoPRUEBA_SINSEGURIDAD;
    // print_r($this);
    self::$modulo = $modulo;
    self::$controlador = $controlador;
    self::$operacion = $operacion;
    Usuario::registrarOperacion($modulo, $controlador, $operacion, $Usuario->usuarioNOMBRE, $Usuario->usuarioID);
    if(!$modoPRUEBA_SINSEGURIDAD){
      if(Usuario::estaLogueado()){
        if(Usuario::usuarioESTADO() == 'ACTIVO' ){
            if(!Usuario::esAdministrador()){
              $ItemMenu = MenuOperaciones::datosPorCombinacion($modulo, $controlador, $operacion);
              // print_r($ItemMenu);
              if(!empty($ItemMenu)){
                if($ItemMenu->menuSEGURIDAD == "RESTRINGIDA"){
                  if(Usuario::tienePermiso($ItemMenu->menuCODIGO) or Usuario::esAdministrador()){
                      return self::ejecutarClassFunction($modulo, $controlador, $operacion);
                  }else{
                    self::$respuesta = RespuestasSistema::error(
                      ''.strtoupper(Usuario::usuarioNOMBRE()).': No estás autorizad@ para realizar la operación '.$ItemMenu->menuTITULO.' [' . $ItemMenu->menuCONTROLADOR . '::' . $ItemMenu->menuOPERACION . '].'
                    );
                  }
                }else{
                  return self::ejecutarClassFunction($modulo, $controlador, $operacion);
                }
              }else{
                self::$respuesta = RespuestasSistema::error('Esta operación no se encuentra registrada en el sistema. Vefirique la ruta .../'.$modulo.'/'.$controlador.'/'.$operacion.'/  a la que está accediendo.');
              }
            }else{
              return self::ejecutarClassFunction($modulo, $controlador, $operacion);
            }
        }else{
          self::$respuesta = RespuestasSistema::error('El usuario '.Usuario::usuarioNOMBRE().' esta en estado '.Usuario::usuarioESTADO().' .');
        }
      }else{
          self::$respuesta = RespuestasSistema::error('El usuario '.Usuario::usuarioNOMBRE().' no ha iniciado sesion en el sistema .');
      }
    }else{
      return self::ejecutarClassFunction($modulo, $controlador, $operacion);
    }

    Usuario::registrarRespuesta( self::$respuesta );
    return false;
  }

  static private function ejecutarClassFunction($modulo, $controlador, $operacion){
    if (isset($controlador) and isset($modulo)) {
      $modulo = trim(strtolower($modulo));
      $controlador = ucfirst(trim(ucwords(($controlador))));
      $archivoControlador =DIR_COMPONENTES . DS .$modulo . DS .'controladores' . DS .$controlador. EXT_CONTROLADOR;
      if (file_exists($archivoControlador)) {
          require_once $archivoControlador;
          $nombreClase = ($controlador) . 'Controlador';
          if (class_exists($nombreClase)) {
              $classCtrl = new $nombreClase();
              if ($classCtrl instanceof $nombreClase) {
                  if (method_exists($classCtrl, $operacion)) {
                      echo self::$respuesta = $classCtrl->$operacion();
                      Usuario::registrarRespuesta( self::$respuesta );
                      return true;
                  } else {
                      self::$respuesta = RespuestasSistema::error('*NO EXISTE LA OPERACION [' . $nombreClase . '::' . $operacion . ']');
                  }
              } else {
                  self::$respuesta = RespuestasSistema::error('NO ES UN OBJETO VALIDO [' . $classCtrl . ']');
              }
          } else {
              self::$respuesta = RespuestasSistema::error('NO EXISTE LA CLASE [' . $nombreClase . ']');
          }
      } else {
          self::$respuesta = RespuestasSistema::error('NO EXISTE EL ARCHIVO [' . $archivoControlador . ']');
      }
    } else {
        self::$respuesta = RespuestasSistema::error('NO LLEGARON DATOS PARA LA OPERACION');
    }
    return false;
  }

  static function init() {
    self::twigConfigPlantilla();
  }

  static function twigConfigPlantilla($dirPlantilla) {
    $loader = new Twig_Loader_Filesystem(array($dirPlantilla));
    $twig = new Twig_Environment($loader, array('debug' => true));
    $twig->addExtension(new Twig_Extension_Debug());
    // $twig->addGlobal('Params', new Parametros());
    // $twig->addGlobal('Parametros', new Parametros());
    $filter = new \Twig\TwigFilter('Parametro', function ($PARAMETRO) {
        return Parametros::valor($PARAMETRO);
    });
    $twig->addFilter($filter);

    $function = new Twig_SimpleFunction('imagenTooltip', function ($idElemento, $srcImagen) {
        echo '<div class="titulo-flotante" data-tooltip-content="#'.$idElemento.'" ><i class="fa fa-picture-o" aria-hidden="true"></i>';
        echo '<div class="tooltip_imagen">'.
                '<span id="'.$idElemento.'">'.'<img src="'.$srcImagen.'" />'.'</span>'.
            '</div>';
        echo '</div>';
    });
    $twig->addFunction($function);

    $function = new Twig_SimpleFunction('pdfTooltip', function ($idElemento, $srcPDF) {
        echo '<div class="titulo-flotante" data-tooltip-content="#'.$idElemento.'" ><i class="fa fa-file-pdf-o fa-lg" aria-hidden="true"></i>';
        echo '<div class="tooltip_imagen">'.
                '<span id="'.$idElemento.'">'.
                    '<iframe src="'.$srcPDF.'" style="border:0px #ffffff none;" name="myiFrame" scrolling="no" frameborder="1" marginheight="0px" marginwidth="0px" height="400px" width="600px" allowfullscreen></iframe>'.
                '</span>'.
            '</div>';
        echo '</div>';
    });
    $twig->addFunction($function);

    $function = new Twig_SimpleFunction('labelEstado', function ($estadoCODIGO, $estadoTITULO = '') {
        if(empty($estadoTITULO)){ $estadoTITULO = $estadoCODIGO; }
        echo '<span class="label label-default label-'.$estadoCODIGO.'">'.$estadoTITULO.'</span>';
    });
    $twig->addFunction($function);

    $function = new Twig_SimpleFunction('enlace', function ( $enlaceURL, $enlaceTITULO = '') {
        if(!empty($enlaceURL))
            echo '<a class="btn-link" href="javascript:void(0)" onclick="popUp(\''.htmlspecialchars($enlaceURL).'\', \''.$enlaceURL.'\');" >'.$enlaceTITULO.'<i class="fa fa-external-link" aria-hidden="true"></i></a>';
    });
    $twig->addFunction($function);

    $function = new Twig_SimpleFunction('existeURL', function ($archivoURL) {
        $curl = curl_init($archivoURL);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        $result = curl_exec($curl);
        if ($result !== false)
        {
          $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
          if ($statusCode == 404)
          {
            return false;
          }
          else
          {
             return true;
          }
        }
        else
        {
          return false;
        }


    });
    $twig->addFunction($function);

    return $twig;
  }

  static function getGlobals() {
        SesionUsuario::abrir();
        return array(
         'favicon' => 'favicon.html.php',
         'login' => PLANTILLA_ACTIVA . 'login.html.php',
         'dashboard' => PLANTILLA_ACTIVA . 'dashboard.html.php',
         'mantenimiento' => PLANTILLA_ACTIVA . 'mantenimiento.html.php',
         'bloqueo' => PLANTILLA_ACTIVA . 'bloqueo.html.php',
         'inactividad' => PLANTILLA_ACTIVA . 'inactividad.html.php',
         'estaLogueado' => Usuario::estaLogueado(),
         'isEstadoSesion' => Usuario::estadoSesion(),
         'session' => Usuario::getUsuario(),
         'estado_session' => Usuario::get('ESTADO'),
         'session_desde' => SesionUsuario::valor('LOGIN_DESDE'),
         'session_ip' => SesionUsuario::valor('LOGIN_IP'),
         'isMantenimiento' => Parametros::valor('MODO_MANTENIMIENTO'),
         'URL_SICAM' => URL_SICAM,
         'VERSION_SICAM_EJECUCION' => VERSION_SICAM_EJECUCION,
         'hash_vista' => uniqid(),
         'URL_ARCHIVOS' => URL_ARCHIVOS,
        );
    }

}

