<?php
class MenuOperacionesUsuarios extends ModeloDatos
{
  public function __construct($operacionUsuarioID = null) {
    return parent::__construct('MenuOperacionesUsuarios', 'operacionUsuarioID', $operacionUsuarioID);
  }

  public function nuevo( $menuID, $usuarioID){
    $nuevo = $this->insertar([
      'menuID' => $menuID ,'usuarioID' => $usuarioID
    ]);
    return $this->porID($nuevo);
  }
  public function delUsuario( $usuarioID){
    return $this->todos(['usuarioID' => $usuarioID]);
  }
    /**
     * Recibe un identificador de ControlConsecutivos y elimina el registro.
     * @param int $consecutivosID Identificador del registro
     * ha eliminar.
     * @return int Cantidad de registros eliminados
     */
    public function eliminarParaUsuario($usuarioID)
    {
       return $this->elimina(['usuarioID' => $usuarioID]);
    }


}
