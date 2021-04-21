<?php
include_once RUTA_MYSQL . 'connection.class.php';
class tipopregunta extends connectdb
{
    function consultar($flag, $criterio)
    {
        $data = array();
        $query = "CALL sp_tipopreguntaConsultar('$flag', '$criterio')";
        $result = parent::query($query);
        if (!isset($result['error'])) {
            foreach ($result as $row) {
                $data[] = $row;
            }
        } else {
            $this->setMsgErr($result['error']);
        }
        return $data;
    }
}
