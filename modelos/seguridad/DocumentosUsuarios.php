<?php
class DocumentosUsuarios extends ModeloDatos
{
  public function __construct($documentoUsuarioID = null) {
    return parent::__construct('DocumentosUsuarios', 'documentoUsuarioID', $documentoUsuarioID);
  }

  public function nuevo( $documentoID, $usuarioID){
    $nuevo = $this->insertar([
      'documentoID' => $documentoID ,'usuarioID' => $usuarioID
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
