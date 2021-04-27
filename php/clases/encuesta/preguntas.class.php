<?php
include_once RUTA_MYSQL . 'connection.class.php';
class pregunta extends connectdb
{
    function buscar($criterio, $buscar, $flagContar = 0, $paginaActual = 1, $regsPorPag = 20)
    {
        $data = array();
        $query = "CALL sp_preguntaBuscar('$criterio', '$buscar', '$flagContar', '$paginaActual', '$regsPorPag')";
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
        $tipopregunta = isset($form["lstTipoPregunta"]) ? $form["lstTipoPregunta"] : '';
        $descripcion = isset($form["txtDescripcion"]) ? $form["txtDescripcion"] : '';
        $orientacion = isset($form["lstOrientacion"]) ? $form["lstOrientacion"] : '';
        $obligatorio = isset($form["chkObligatorio"]) ? $form["chkObligatorio"] : '0';
        $activo = isset($form["chk_activo"]) ? $form["chk_activo"] : '0';

        $query = "CALL sp_preguntaMantenedor('$flag', '$pregunta', '$tipopregunta','','$descripcion','$orientacion','$obligatorio','$activo', '$usuario')";
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
        $query = "CALL sp_preguntaConsultar('$flag', '$criterio')";
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

    function buscarDisponibles($criterio, $buscar, $flagContar = 0, $paginaActual = 1, $regsPorPag = 20)
    {
        $data = array();
        $encuesta = $criterio["hhddEncuesta"];
        $seccion = $criterio["lstSeccion"];
        $query = "CALL sp_preguntasDisponiblesBuscar('$encuesta', '$buscar', '$flagContar', '$paginaActual', '$regsPorPag','$seccion')";
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

    function buscarAgregadas($criterio, $buscar, $flagContar = 0, $paginaActual = 1, $regsPorPag = 20)
    {
        $data = array();
        $encuesta = $criterio["hhddEncuesta"];
        $seccion = $criterio["lstSeccion"];
        $query = "CALL sp_preguntaAgregadaBuscar('$seccion', '$buscar', '$flagContar', '$paginaActual', '$regsPorPag')";
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
}
