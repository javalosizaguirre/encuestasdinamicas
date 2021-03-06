<?php
include_once RUTA_MYSQL . 'connection.class.php';
class encuestas extends connectdb
{
    function buscar($criterio, $buscar, $flagContar = 0, $paginaActual = 1, $regsPorPag = 20)
    {
        $data = array();
        $query = "CALL sp_encuestasBuscar('$criterio', '$buscar', '$flagContar', '$paginaActual', '$regsPorPag')";
        // echo "buscar--->   ".$query;
        $result = parent::query($query);

        if (!isset($result['error'])) {
            foreach ($result as $row) {
                if ($flagContar == 1) {
                    $data['total'] = $row['total'];
                } else {
                    $data[] = $row;
                }
            }
        } else {
            $this->setMsgErr($result['error']);
        }
        return $data;
    }

    function mantenedor($flag, $form)
    {
        $data = array();
        $usuario = $_SESSION["sys_usuario"];
        $encuesta = isset($form["txtCodigo"]) ? $form["txtCodigo"] : '';
        $titulo = isset($form["txtTitulo"]) ? $form["txtTitulo"] : '';
        $bienvenida = isset($form["txtBienvenida"]) ? $form["txtBienvenida"] : '';
        $despedida = isset($form["txtDespedida"]) ? $form["txtDespedida"] : '';
        $activo = isset($form["chk_activo"]) ? $form["chk_activo"] : '0';
        $query = "CALL sp_encuestasMantenedor('$flag', '$encuesta', '$titulo','$bienvenida','$despedida', '$activo', '$usuario')";
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

    function consultar($flag, $criterio)
    {
        $data = array();
        $query = "CALL sp_encuestasConsultar('$flag', '$criterio')";
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

    function mantenedorSeccionPregunta($flag, $seccion, $pregunta, $encuesta)
    {
        $data = array();
        $usuario = $_SESSION["sys_usuario"];
        $query = "CALL sp_seccionpreguntaMantenedor('$flag', '$seccion', '$pregunta','$encuesta', '$usuario')";
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
