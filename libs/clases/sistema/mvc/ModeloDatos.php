<?php
if (!defined('CLAVE_SECRETA')) die('acceso no autorizado');
class ModeloDatos {

  private $nombreTabla;
  private $nombreCampoID;
  public $Registros = array();

  public function __construct($nombreTabla, $nombreCampoID, $valorCampoID = null) {
    if(count($this->Registros)) { $this->Registros = array(); }

    // echo "<br />Dentro del Modelo [".$nombreTabla."] -> ".$valorCampoID;
    $this->nombreTabla = $nombreTabla;
    $this->nombreCampoID = $nombreCampoID;
    $this->{$nombreCampoID} = null;
    if(!is_null($valorCampoID)){
        // krumo($this);
        // echo "<br />Se va a asignar la variable [".$nombreCampoID."] es valor -> ".$valorCampoID;
        $this->{$nombreCampoID} = $valorCampoID;
        // echo "<br />Se asigno a la variable [".$nombreCampoID."] es valor -> ".$this->$nombreCampoID;
        $this->datos();
    }
    // echo "<br />asi quedó el objeto de ID ".$valorCampoID;
    // krumo($this);
    return $this;
  }



    function porID($valorCampoID){
        if(count($this->Registros)) { $this->Registros = array(); }
        return $this->datos([$this->nombreCampoID=> $valorCampoID]);
    }
    public function datos($donde = null){
        if(count($this->Registros)) { $this->Registros = array(); }
        if(is_null($donde)){
            $nombreCampo = $this->nombreCampoID;
            $valorCampo = $this->$nombreCampo;
            $donde = [$nombreCampo =>$valorCampo];
            // echo "<br />asi quedó el objeto donde ";
            // krumo($donde);
        }
        
        $datosRegistro = self::fila($donde);
        if(is_array($datosRegistro)){
            if(count($datosRegistro)){
                foreach($datosRegistro as $variable => $dato){
                    //  echo $variable."  =  ".$dato. " <br />  ";
                    $this->$variable = $dato;
                }
                return $this;
            }
        }
        return null;

    }

    public function todos($donde = null){
        if(is_null($donde)){
            $nombreCampo = $this->nombreCampoID;
            $valorCampo = $this->$nombreCampo;
            // echo "<br />asi quedó el objeto donde ";
            // krumo($donde);
        }
        $Registros = self::variasFilas($donde);
        if(count($Registros)){
            $this->Registros = array();
            foreach($Registros as $k => $datosRegistro ){
                $this->Registros[$k] = new stdClass();
                foreach($datosRegistro as $variable => $dato){
                    //  echo $variable."  =  ".$dato. " <br />  ";
                    $this->Registros[$k]->$variable = $dato;
                }
            }
            return $this->Registros;
        }
        return null;

    }










    function consulta($sql, $donde = null){
        global $BD_AP_PRINCIPAL;
        return $BD_AP_PRINCIPAL->query($sql, $donde)->fetchAll();
    }
    function consultaUNO($sql, $donde = null){
        global $BD_AP_PRINCIPAL;
        $filas = $BD_AP_PRINCIPAL->query($sql, $donde)->fetchAll();
        if(count($filas)){
            if(count($filas[0])){
                foreach($filas[0] as $variable => $dato){
                    //  echo $variable."  =  ".$dato. " <br />  ";
                    $this->$variable = $dato;
                }
                return $this;
            }
        }
        return null;
    }
    function consultaMUCHOS($sql, $donde = array()){
        global $BD_AP_PRINCIPAL;
        $Registros = $BD_AP_PRINCIPAL->query($sql, $donde)->fetchAll();
// print_r($Registros);
        if(count($Registros)){
            $this->Registros = array();
            foreach($Registros as $k => $datosRegistro ){
                $this->Registros[$k] = new stdClass();
                foreach($datosRegistro as $variable => $dato){
                    //  echo $variable."  =  ".$dato. " <br />  ";
                    $this->Registros[$k]->$variable = $dato;
                }
            }
            return $this->Registros;
        }
    }
    function fila($donde = null, $columnas = '*' ){ 
        global $BD_AP_PRINCIPAL;
        return $BD_AP_PRINCIPAL->get($this->nombreTabla, $columnas, $donde);
    }
    function variasFilas($donde = null, $columnas = '*' ){
        global $BD_AP_PRINCIPAL;
        return $BD_AP_PRINCIPAL->select($this->nombreTabla, $columnas, $donde);
    }


    function insertar($datos){
        global $BD_AP_PRINCIPAL;
        $BD_AP_PRINCIPAL->insert($this->nombreTabla, $datos);
        // $nombreCampo = $this->nombreCampoID;
        // $this->$nombreCampo =  $BD_AP_PRINCIPAL->id();
        return $BD_AP_PRINCIPAL->id();
    }
    function actualiza($datos, $donde){
        global $BD_AP_PRINCIPAL;
        $datos = $BD_AP_PRINCIPAL->update($this->nombreTabla, $datos, $donde );
        return $datos->rowCount();
    }
    function elimina($donde){
        global $BD_AP_PRINCIPAL;
        $datos = $BD_AP_PRINCIPAL->delete($this->nombreTabla,$donde );
        return $datos->rowCount();
    }


}
