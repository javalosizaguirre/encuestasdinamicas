<?php
include_once RUTA_MYSQL . 'connection.class.php';
class opciones extends connectdb
{
    function buscar($criterio, $buscar, $flagContar = 0, $paginaActual = 1, $regsPorPag = 20)
    {
        $data = array();
        $query = "CALL sp_opcionesBuscar('$criterio', '$buscar', '$flagContar', '$paginaActual', '$regsPorPag')";
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
        $pregunta = isset($form["txtCodigo"]) ? $form["txtCodigo"] : '';
        $opcion = isset($form["hhddOpcion"]) ? $form["hhddOpcion"] : '';
        $descripcion = isset($form["txtDescripcion"]) ? $form["txtDescripcion"] : '';
        $orden = isset($form["txtOrden"]) ? $form["txtOrden"] : '';
        $activo = isset($form["chk_activo"]) ? $form["chk_activo"] : '0';

        $query = "CALL sp_opcionesMantenedor('$flag','$opcion', '$pregunta','$descripcion','$orden','$activo', '$usuario')";
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
        $query = "CALL sp_opcionesConsultar('$flag', '$criterio')";
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
