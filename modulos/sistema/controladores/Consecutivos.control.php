<?php

class ConsecutivosControlador extends Controladores {

    public function todosBasicos() {
        $P = new ControlConsecutivos();
        return Respuestassistema::exito("Todos los consecutivos del sistema", $P->todos());
    }

    function actualizarActualConsecutivo() {
        if (!empty($this->consecutivoID)) {

            if (!empty($this->consecutivoACTUAL)) {
                $ControlConsecutivos = new ControlConsecutivos($this->consecutivoID);
                $actualizado = $ControlConsecutivos->actualiza(
                array( 'consecutivoACTUAL' => $this->consecutivoACTUAL ),
                array( 'consecutivoID' => $this->consecutivoID )
                );
                if ($actualizado > 0) {
                    return Respuestassistema::exito(null, new ControlConsecutivos($this->consecutivoID));
                } else {
                    return Respuestassistema::error("No se actualizó el consecutivo. Verifique los datos, o contacte al Centro TICS.");
                }
            } else {
                return Respuestassistema::error("No llegarón los datos necesario para la operación. <br />" . $errores);
            }
        } else {
            return Respuestassistema::error('Es necsario enviar el ID DEL CONSECUTIVO  [consecutivoID] para poder actualizar su valor. ');
        }
    }

}
