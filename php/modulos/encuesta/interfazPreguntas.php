<?php
include_once RUTA_CLASES . 'preguntas.class.php';
include_once RUTA_CLASES . 'tipopreguntas.class.php';
include_once RUTA_CLASES . 'opciones.class.php';
class interfazPregunta
{
    function principal()
    {
        $clsabstract = new interfazAbstract();
        $clsabstract->legenda('fa fa-user-edit', 'Editar');
        $clsabstract->legenda('fa fa-times-circle', 'Eliminar');
        $clsabstract->legenda('fas fa-list', 'Agregar Opciones');
        $clsabstract->legenda('fas fa-search', 'Visualizar Pregunta');



        $leyenda = $clsabstract->renderLegenda('30%');
        $titulo = "Preguntas";
        $html = ' 
    
    <div class="card">
        <div class="card-header">' . $titulo . '</div>
            <div class="card-body">        
                <form class="form-inline" onsubmit="return false;">
                    <input type="text" class="form-control" id="txtBuscar" placeholder="Buscar por descripcion" style="width:70%">                           
                    <button type="button" class="btn btn-secondary" id="btnBuscar"><i class="fas fa-search"></i>Buscar</button>
                    <button type="button" class="btn btn-secondary" id="btnNuevo">Nuevo</button>                        
                </form>             
            </div>
            
            <div class="card-body" id="outQuery">
                ' . $this->datagrid('') . '
            </div>
            <p><center>' . $leyenda . '</center></p>
        
        
           <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contenido">
                    
                </div>
                <div class="modal-footer" id="footer">
                </div>
                </div>
            </div>
            </div>


        </div>

        </div>
    </div>';

        return $html;
    }

    function datagrid($criterio, $total_regs = 0, $pagina = 1, $nreg_x_pag = 50)
    {
        $Grid = new eGrid();
        $Grid->numeracion();
        $Grid->columna(array(
            "titulo" => "Pregunta",
            "campo" => "nombre",
            "width" => "400"
        ));

        $Grid->columna(array(
            "titulo" => "Tipo de Pregunta",
            "campo" => "descripcion",
            "width" => "100"
        ));

        $Grid->columna(array(
            "titulo" => "Orientacion",
            "campo" => "orientacion",
            "width" => "100"
        ));


        $Grid->columna(array(
            "titulo" => "Obligatorio",
            "campo" => "obligatorio",
            "width" => "20",
            "fnCallback" => function ($row) {
                if ($row["obligatorio"] == '1') {
                    $cadena = '<span class = "badge badge-success">Si</span>';
                } elseif ($row["obligatorio"] == '0') {
                    $cadena = '<span class = "badge badge-danger">No</span>';
                }
                return $cadena;
            }
        ));

        $Grid->columna(array(
            "titulo" => "Estado",
            "campo" => "activo",
            "width" => "20",
            "fnCallback" => function ($row) {
                if ($row["activo"] == '1') {
                    $cadena = '<span class = "badge badge-success">Activo</span>';
                } elseif ($row["activo"] == '0') {
                    $cadena = '<span class = "badge badge-danger">Inactivo</span>';
                }
                return $cadena;
            }
        ));




        $Grid->accion(array(
            "icono" => "fa fa-times-circle",
            "titulo" => "Eliminar Registro",
            "xajax" => array(
                "fn" => "xajax__preguntaMantenimiento",
                "msn" => "¿Esta seguro de eliminar el registro?",
                "parametros" => array(
                    "flag" => "3",
                    "campos" => array("pregunta")
                )
            )
        ));

        $Grid->accion(array(
            "icono" => "fa fa-user-edit",
            "titulo" => "Editar Usuario",
            "xajax" => array(
                "fn" => "xajax__interfazPreguntaEditar",
                "parametros" => array(
                    "flag" => "2",
                    "campos" => array("pregunta")
                )
            )
        ));

        $Grid->accion(array(
            "titulo" => "Agregar Opciones",
            "fnCallback" => function ($row) {
                if ($row["tipopregunta"] == '19' || $row["tipopregunta"] == '21' || $row["tipopregunta"] == '22') {
                    $cadena = '<a style="color: black;" href="javascript:void(0)" onclick="xajax__interfazOpcionNueva(\'' . $row["pregunta"] . '\')"><i class="fas fa-list"></i></a>';
                }
                return $cadena;
            }
        ));

        $Grid->accion(array(
            "icono" => "fas fa-search",
            "titulo" => "Visualizar Pregunta",
            "xajax" => array(
                "fn" => "xajax__interfazVisualizarPregunta",
                "parametros" => array(
                    "flag" => "1",
                    "campos" => array("pregunta")
                )
            )
        ));





        $Grid->data(array(
            "criterio" => $criterio,
            "total" => $Grid->totalRegistros,
            "pagina" => $pagina,
            "nRegPagina" => $nreg_x_pag,
            "class" => "pregunta",
            "method" => "buscar"
        ));
        $Grid->paginador(array(
            "xajax" => "xajax__preguntaDatagrid",
            "criterio" => array($criterio),
            "total" => $Grid->totalRegistros,
            "nRegPagina" => $nreg_x_pag,
            "pagina" => $pagina,
            "nItems" => "5",
            "lugar" => "in"
        ));
        $html = $Grid->render();
        return $html;
    }

    function datagridOpciones($criterio, $total_regs = 0, $pagina = 1, $nreg_x_pag = 50)
    {
        $Grid = new eGrid();
        $Grid->numeracion();
        $Grid->columna(array(
            "titulo" => "Opcion",
            "campo" => "descripcion",
            "width" => "100"
        ));

        $Grid->columna(array(
            "titulo" => "Orden",
            "campo" => "orden",
            "width" => "50"
        ));


        $Grid->columna(array(
            "titulo" => "Estado",
            "campo" => "activo",
            "width" => "20",
            "fnCallback" => function ($row) {
                if ($row["activo"] == '1') {
                    $cadena = '<span class = "badge badge-success">Activo</span>';
                } elseif ($row["activo"] == '0') {
                    $cadena = '<span class = "badge badge-danger">Inactivo</span>';
                }
                return $cadena;
            }
        ));




        $Grid->accion(array(
            "icono" => "fa fa-times-circle",
            "titulo" => "Eliminar Registro",
            "xajax" => array(
                "fn" => "xajax__opcionMantenimiento",
                "msn" => "¿Esta seguro de eliminar el registro?",
                "parametros" => array(
                    "flag" => "3",
                    "campos" => array("opcion", "pregunta")
                )
            )
        ));

        $Grid->data(array(
            "criterio" => $criterio,
            "total" => $Grid->totalRegistros,
            "pagina" => $pagina,
            "nRegPagina" => $nreg_x_pag,
            "class" => "opciones",
            "method" => "buscar"
        ));
        $html = $Grid->render();
        return $html;
    }

    public function interfazNuevo()
    {
        $clasetipopregunta = new tipopregunta();
        $datatipopregunta = $clasetipopregunta->consultar("1", "");
        $html = '
            <form name="form" id="form" class="form-horizontal" onsubmit="return false" method="post">                
                <div id="cont-img-opt" style="position:absolute;left:250px;border:2px solid #585858;display:none"><img src=img/pregunta1.png></div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" >Tipo de Pregunta:</label>
                        <div class="col-sm-9">
                            <select id="lstTipoPregunta" name="lstTipoPregunta" class="form-control">
                            
                                <option value="">SELECCIONAR...</option>';
        $catTMP = '';
        foreach ($datatipopregunta as $value) {
            if ($value['categoria'] != $catTMP) {
                switch ($value['categoria']) {
                    case 1:
                        $opg = 'Preguntas de opción múltiple';
                        break;
                    case 2:
                        $opg = 'Preguntas de respuesta única';
                        break;
                    case 3:
                        $opg = 'Preguntas de texto';
                        break;
                    case 4:
                        $opg = 'Preguntas de lista desplegable';
                        break;
                }

                $html .= '<optgroup label="' . $opg . '" >';
                foreach ($datatipopregunta as $opt) {
                    if ($opt['categoria'] == $value['categoria']) {
                        $html .=    '<option value="' . $opt['tipopregunta'] . '">' . $opt["descripcion"] . '</option>';
                    }
                }
                $html .= '</optgroup>';
            }
            $catTMP = $value['categoria'];
        }

        $html .= '</select>
       
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" ><span style="color:red">(*)</span> Pregunta:</label>
                        <div class="col-sm-9">
                            <textarea style ="width:100%" class="form-control"   id="txtDescripcion" name="txtDescripcion" rows="5"></textarea>
                        </div>
                    </div>   
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" ><span style="color:red">(*)</span> Orientacion:</label>
                        <div class="col-sm-9">
                            <select id="lstOrientacion" name="lstOrientacion" class="form-control">
                                <option value="">SELECCIONAR...</option>
                                <option value="H">Horizontal</option>
                                <option value="V">Vertical</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" ><span style="color:red">(*)</span> Obligatorio:</label>
                        <div class="col-sm-9">
                            <input id="chkObligatorio"  name="chkObligatorio" type="checkbox" value="1" style="vertical-align:middle" >
                        </div>
                    </div>                                                                         
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" >Activo:</label>
                        <div class="col-sm-9">
                            <input id="chk_activo"  name="chk_activo" type="checkbox" value="1" style="vertical-align:middle" checked>
                        </div>
                    </div>                                                                                                                                                                                                                          
            </form>';

        $botones = '<span style="color:red">(*) Campos Obligatorios.</span>
                        <button type="button" class="btn btn-primary" id="btnGuardar"><i class="icon-ok icon-white"></i><span id="spanSave01">Guardar</span></button>                   
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="icon-remove"></i>Cerrar</button>                        
            </fieldset>
            </form>';
        return array($html, $botones);
    }

    public function interfazEditar($pregunta)
    {
        $clasepregunta = new pregunta();
        $datapregunta = $clasepregunta->consultar('1', $pregunta);

        $clasetipopregunta = new tipopregunta();
        $datatipopregunta = $clasetipopregunta->consultar("1", "");
        $html = '
            <form name="form" id="form" class="form-horizontal" onsubmit="return false" method="post">                                
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" >Tipo de Pregunta:</label>
                        <div class="col-sm-9">
                            <input type="hidden" id="txtCodigo" name="txtCodigo" value="' . $pregunta . '">
                            <select id="lstTipoPregunta" name="lstTipoPregunta" class="form-control" disabled>
                            
                                <option value="">SELECCIONAR...</option>';
        $catTMP = '';
        foreach ($datatipopregunta as $value) {
            if ($value['categoria'] != $catTMP) {
                switch ($value['categoria']) {
                    case 1:
                        $opg = 'Preguntas de opción múltiple';
                        break;
                    case 2:
                        $opg = 'Preguntas de respuesta única';
                        break;
                    case 3:
                        $opg = 'Preguntas de texto';
                        break;
                    case 4:
                        $opg = 'Preguntas de lista desplegable';
                        break;
                }

                $html .= '<optgroup label="' . $opg . '" >';
                foreach ($datatipopregunta as $opt) {
                    if ($opt['categoria'] == $value['categoria']) {
                        if ($datapregunta[0]["tipopregunta"] == $opt["tipopregunta"]) {
                            $selected = "selected";
                        } else {
                            $selected = "";
                        }
                        $html .=    '<option value="' . $opt['tipopregunta'] . '" ' . $selected . '>' . $opt["descripcion"] . '</option>';
                    }
                }
                $html .= '</optgroup>';
            }
            $catTMP = $value['categoria'];
        }

        $html .= '</select>
       
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" ><span style="color:red">(*)</span> Pregunta:</label>
                        <div class="col-sm-9">
                            <textarea style ="width:100%" class="form-control"   id="txtDescripcion" name="txtDescripcion" rows="5">' . $datapregunta[0]["nombre"] . '</textarea>
                        </div>
                    </div>   
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" ><span style="color:red">(*)</span> Orientacion:</label>
                        <div class="col-sm-9">
                            <select id="lstOrientacion" name="lstOrientacion" class="form-control">
                                <option value="">SELECCIONAR...</option>
                                <option value="H" ' . ($datapregunta[0]["orientacion"] == "H" ? 'selected' : '') . '>Horizontal</option>
                                <option value="V" ' . ($datapregunta[0]["orientacion"] == "V" ? 'selected' : '') . '>Vertical</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" ><span style="color:red">(*)</span> Obligatorio:</label>
                        <div class="col-sm-9">
                            <input id="chkObligatorio"  name="chkObligatorio" type="checkbox" value="1" style="vertical-align:middle" ' . ($datapregunta[0]["obligatorio"] == "1" ? 'checked' : '') . '>
                        </div>
                    </div>                                                                         
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" >Activo:</label>
                        <div class="col-sm-9">
                            <input id="chk_activo"  name="chk_activo" type="checkbox" value="1" style="vertical-align:middle" ' . ($datapregunta[0]["activo"] == "1" ? 'checked' : '') . '>
                        </div>
                    </div>                                                                                                                                                                                                                          
            </form>';

        $botones = '<span style="color:red">(*) Campos Obligatorios.</span>
                        <button type="button" class="btn btn-primary" id="btnModificar"><i class="icon-ok icon-white"></i><span id="spanSave01">Guardar</span></button>                   
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="icon-remove"></i>Cerrar</button>                        
            </fieldset>
            </form>';
        return array($html, $botones);
    }


    public function interfazOpcionNueva($pregunta)
    {
        $clasepregunta = new pregunta();
        $html = '
            <form name="form" id="form" class="form-horizontal" onsubmit="return false" method="post">                                
                    <input type="hidden" id="txtCodigo" name="txtCodigo" value="' . $pregunta . '">                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" ><span style="color:red">(*)</span> Opcion:</label>
                        <div class="col-sm-9">
                            <textarea style ="width:100%" class="form-control"   id="txtDescripcion" name="txtDescripcion"></textarea>
                        </div>
                    </div>   
                     <div class="form-group row">
                        <label class="col-sm-3 col-form-label" >Orden:</label>
                        <div class="col-sm-9">
                            <input id="txtOrden" style="text-align:right"  name="txtOrden" type="text" class="form-control span1">
                        </div>
                    </div>                                                                   
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" >Activo:</label>
                        <div class="col-sm-9">
                            <input id="chk_activo"  name="chk_activo" type="checkbox" value="1" style="vertical-align:middle" checked>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12" style="text-align:center">
                            <button type="button" class="btn btn-primary" id="btnAgregarOpcion"><i class="icon-ok icon-white"></i><span id="spanSave01">Agregar</span></button>
                        </div>
                    </div>
                    <div id="outQueryOpciones" class="card-body">
                        ' . $this->datagridOpciones($pregunta) . '
                    </div>
            </form>';

        $botones = '<span style="color:red">(*) Campos Obligatorios.</span>
                        
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="icon-remove"></i>Cerrar</button>                        
            </fieldset>
            </form>';
        return array($html, $botones);
    }


    public function visualizarPregunta($pregunta)
    {
        $clasepregunta = new pregunta();
        $claseopciones = new opciones();

        $datapregunta = $clasepregunta->consultar('1', $pregunta);
        $dataopciones = $claseopciones->consultar('1', $pregunta);

        if ($datapregunta[0]["tipopregunta"] == '16') {
            $html = '<div class="contenedor" style="width:100%">
                        <div class="contenidos">
                            <div class="columnas" style="width:100%">
                                1. ' . $datapregunta[0]["nombre"] . '
                            </div>
                        </div>';

            $html .= '<div class="contenidos">
                            <div class="columnas" style="width:100%">
                                <input type="text" class="form-control">
                            </div>
                        </div>';

            $html .= '</div>';
        }

        if ($datapregunta[0]["tipopregunta"] == '17') {
            $html = '<div class="contenedor" style="width:100%">
                        <div class="contenidos">
                            <div class="columnas" style="width:100%">
                                1. ' . $datapregunta[0]["nombre"] . '
                            </div>
                        </div>';

            $html .= '<div class="contenidos">
                            <div class="columnas" style="width:100%">
                                <input type="text" class="form-control datepicker" readonly style="width:40%">
                            </div>
                        </div>';

            $html .= '</div>';
        }

        if ($datapregunta[0]["tipopregunta"] == '18') {
            $html = '<div class="contenedor" style="width:100%">
                        <div class="contenidos">
                            <div class="columnas" style="width:100%">
                                1. ' . $datapregunta[0]["nombre"] . '
                            </div>
                        </div>';

            $html .= '<div class="contenidos">
                            <div class="columnas" style="width:100%">
                                <textarea class="form-control" style="width:100%"></textarea>
                            </div>
                        </div>';

            $html .= '</div>';
        }

        if ($datapregunta[0]["tipopregunta"] == '19') {
            $html = '<div class="contenedor" style="width:100%">
                        <div class="contenidos">
                            <div class="colspan">
                                1. ' . $datapregunta[0]["nombre"] . '
                            </div>
                            <div class="celdarelleno">
                                &nbsp;
                            </div>
                            <div class="celdarelleno">
                                &nbsp;
                            </div>                            
                        </div>';

            if ($datapregunta[0]["orientacion"] == 'V') {
                foreach ($dataopciones as $value) {
                    $html .= '<div class="contenidos" style="width:100%">
                                <div class="columnas" style="width:2%">
                                    &nbsp;
                                </div>
                                <div class="columnas" style="width:5%; text-align:center">
                                    <input type="checkbox" class="form-control">
                                </div>
                                <div class="columnas" style="width:93%">
                                    ' . $value["descripcion"] . '
                                </div>
                            </div>';
                }
            } else {
                $html .= '<div class="contenidos" style="width:100%">';
                foreach ($dataopciones as $value) {
                    $html .= '  <div class="columnas" style="width:5%; text-align:center">
                                    <input type="checkbox" class="form-control">
                                </div>
                                <div class="columnas" >
                                    ' . $value["descripcion"] . '
                                </div>';
                }
                $html .= '</div>';
            }

            $html .= '</div>';
        }


        if ($datapregunta[0]["tipopregunta"] == '21') {
            $html = '<div class="contenedor" style="width:100%">
                        <div class="contenidos">
                            <div class="colspan">
                                1. ' . $datapregunta[0]["nombre"] . '
                            </div>
                            <div class="celdarelleno">
                                &nbsp;
                            </div>
                            <div class="celdarelleno">
                                &nbsp;
                            </div>                            
                        </div>';

            if ($datapregunta[0]["orientacion"] == 'V') {
                foreach ($dataopciones as $value) {
                    $html .= '<div class="contenidos" style="width:100%">
                                <div class="columnas" style="width:2%">
                                    &nbsp;
                                </div>
                                <div class="columnas" style="width:5%; text-align:center">
                                    <input type="radio" class="form-control" name="rbt">
                                </div>
                                <div class="columnas" style="width:93%">
                                    ' . $value["descripcion"] . '
                                </div>
                            </div>';
                }
            } else {
                $html .= '<div class="contenidos" style="width:100%">';
                foreach ($dataopciones as $value) {
                    $html .= '  <div class="columnas" style="width:5%; text-align:center">
                                    <input type="radio" class="form-control" name="rbt">
                                </div>
                                <div class="columnas" >
                                    ' . $value["descripcion"] . '
                                </div>';
                }
                $html .= '</div>';
            }

            $html .= '</div>';
        }


        if ($datapregunta[0]["tipopregunta"] == '22') {
            $html = '<div class="contenedor" style="width:100%">
                        <div class="contenidos">
                            <div class="columnas" style="width:100%">
                                1. ' . $datapregunta[0]["nombre"] . '
                            </div>
                        </div>';

            $html .= '<div class="contenidos">
                            <div class="columnas" style="width:100%">
                                <select id="" name="" class="form-control">
                                <option value="">SELECCIONAR...</option>';
            foreach ($dataopciones as $value) {
                $html .= '<option value="">' . $value["descripcion"] . '</option>';
            }
            $html .= '</select>
                            </div>
                        </div>';
        }





        if ($datapregunta[0]["tipopregunta"] == '20') {
            $html = '<div class="contenedor" style="width:100%">
                        <div class="contenidos">
                            <div class="colspan">
                                1. ' . $datapregunta[0]["nombre"] . '
                            </div>
                            <div class="celdarelleno">
                                &nbsp;
                            </div>
                            <div class="celdarelleno">
                                &nbsp;
                            </div>                            
                        </div>';

            if ($datapregunta[0]["orientacion"] == 'V') {

                $html .= '<div class="contenidos" style="width:50%">                                
                                <div class="columnas" style="width:5%; text-align:center">
                                    <input type="radio" class="form-control" name="rbt">
                                </div>
                                <div class="columnas" style="width:93%">
                                    Si
                                </div>
                            </div>
                            <div class="contenidos" style="width:50%">                                
                                <div class="columnas" style="width:5%; text-align:center">
                                    <input type="radio" class="form-control" name="rbt">
                                </div>
                                <div class="columnas" style="width:93%">
                                    No
                                </div>                                
                            </div>';
            } else {
                $html .= '<div class="contenidos" style="width:50%">';

                $html .= '      <div class="columnas" style="width:5%; text-align:center">
                                    <input type="radio" class="form-control" name="rbt">
                                </div>
                                <div class="columnas" style="width:10%;">
                                    Si
                                </div>
                                
                                <div class="columnas" style="width:5%; text-align:center">
                                    <input type="radio" class="form-control" name="rbt">
                                </div>
                                <div class="columnas">
                                    No
                                </div>';

                $html .= '</div>';
            }

            $html .= '</div>';
        }




        $botones = '<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="icon-remove"></i>Cerrar</button>';
        return array($html, $botones);
    }
}

function _interfazPregunta()
{
    $rpta = new xajaxResponse();
    $cls = new interfazPregunta();
    $html = $cls->principal();
    $rpta->assign("container", "innerHTML", $html);
    $rpta->script("
    $('#btnNuevo').unbind('click').click(function() {
        xajax__interfazPreguntaNuevo();
    });");
    $rpta->script("
    $('#btnBuscar').unbind('click').click(function() {
    xajax__preguntaDatagrid(document.getElementById('txtBuscar').value);
    });");
    $rpta->script("
    $('#txtBuscar').unbind('keypress').keypress(function() {
        validarEnter(event)
    });");
    return $rpta;
}

function _interfazPreguntaNuevo()
{
    $rpta = new xajaxResponse('UTF-8');
    $cls = new interfazPregunta();
    $html = $cls->interfazNuevo();
    $rpta->script("$('#modal .modal-header h5').text('Registrar Pregunta');");
    $rpta->assign("contenido", "innerHTML", $html[0]);
    $rpta->assign("footer", "innerHTML", $html[1]);
    $rpta->script("$('#modal').modal('show')");
    $rpta->script("
        $('#btnGuardar').unbind('click').click(function() {
            xajax__preguntaMantenimiento('1',xajax.getFormValues('form'));
        });");
    return $rpta;
}

function _interfazPreguntaEditar($flag, $pregunta)
{
    $rpta = new xajaxResponse('UTF-8');
    $cls = new interfazPregunta();
    $html = $cls->interfazEditar($pregunta);
    $rpta->script("$('#modal .modal-header h5').text('Editar Pregunta');");
    $rpta->assign("contenido", "innerHTML", $html[0]);
    $rpta->assign("footer", "innerHTML", $html[1]);
    $rpta->script("$('#modal').modal('show')");
    $rpta->script("
        $('#btnModificar').unbind('click').click(function() {
            xajax__preguntaMantenimiento('" . $flag . "',xajax.getFormValues('form'));
        });");
    return $rpta;
}


function _interfazOpcionNueva($pregunta)
{
    $rpta = new xajaxResponse('UTF-8');
    $cls = new interfazPregunta();
    $html = $cls->interfazOpcionNueva($pregunta);
    $rpta->script("$('#modal .modal-header h5').text('Editar Pregunta');");
    $rpta->assign("contenido", "innerHTML", $html[0]);
    $rpta->assign("footer", "innerHTML", $html[1]);
    $rpta->script("$('#modal').modal('show')");
    $rpta->script("
        $('#btnAgregarOpcion').unbind('click').click(function() {
            xajax__opcionMantenimiento('1', xajax.getFormValues('form'));
        });");
    return $rpta;
}



function _preguntaDatagrid($criterio, $total_regs = 0, $pagina = 1, $nregs = 50)
{
    $rpta = new xajaxResponse();
    $cls = new interfazPregunta();
    $html = $cls->datagrid($criterio, $total_regs, $pagina, $nregs);
    $rpta->assign("outQuery", "innerHTML", $html);
    return $rpta;
}

function _preguntaMantenimiento($flag, $form = '')
{
    $rpta = new xajaxResponse();
    $clase = new pregunta();
    $interfaz = new interfazPregunta();
    if ($flag == '3') {
        $form = array("txtCodigo" => $form);
    }

    $msj1 = '';
    $msj = 'LOS SIGUIENTES CAMPOS SON REQUERIDOS (*)';
    $msj .= '\\n-----------------------------------------------------------------------\\n';


    if ($flag == '1') {
        if ($form["lstTipoPregunta"] == '') {
            $msj1 .= '- Tipo de Pregunta\\n';
        }
    }

    if ($form["txtDescripcion"] == '') {
        $msj1 .= '- Pregunta\\n';
    }

    if ($msj1 != '' && $flag != '3') {
        $rpta->script('alert("' . $msj . $msj1 . '")');
    } else {
        $result = $clase->mantenedor($flag, $form);
        if ($result[0]['mensaje'] == 'MSG_001') {
            $rpta->alert(MSG_001);
            $rpta->script("jQuery('#modal').modal('hide');");
            $rpta->assign("outQuery", "innerHTML", $interfaz->datagrid(''));
        } elseif ($result[0]['mensaje'] == 'MSG_002') {
            $rpta->alert(MSG_002);
            $rpta->script("jQuery('#modal').modal('hide');");
            $rpta->assign("outQuery", "innerHTML", $interfaz->datagrid(''));
        } elseif ($result[0]['mensaje'] == 'MSG_003') {
            $rpta->alert(MSG_003);
            $rpta->assign("outQuery", "innerHTML", $interfaz->datagrid(''));
        } elseif ($result[0]['mensaje'] == 'MSG_004') {
            $rpta->alert(MSG_004);
        } elseif ($result[0]['mensaje'] == 'MSG_005') {
            $rpta->alert(MSG_005);
        } elseif ($result[0]["mensaje"] == 'MSG_009') {
            $rpta->alert(MSG_009);
        }
    }
    return $rpta;
}


function _opcionMantenimiento($flag, $form = '', $pregunta = '')
{
    $rpta = new xajaxResponse();
    $clase = new opciones();
    $interfaz = new interfazPregunta();
    if ($flag == '3') {
        $form = array("hhddOpcion" => $form, "txtCodigo" => $pregunta);
    }

    $msj1 = '';
    $msj = 'LOS SIGUIENTES CAMPOS SON REQUERIDOS (*)';
    $msj .= '\\n-----------------------------------------------------------------------\\n';


    if ($flag == '1') {
        if ($form["txtDescripcion"] == '') {
            $msj1 .= '- Opcion\\n';
        }
    }
    if ($msj1 != '' && $flag != '3') {
        $rpta->script('alert("' . $msj . $msj1 . '")');
    } else {
        $result = $clase->mantenedor($flag, $form);
        if ($result[0]['mensaje'] == 'MSG_001') {
            $rpta->alert(MSG_001);
            $rpta->assign("outQueryOpciones", "innerHTML", $interfaz->datagridOpciones($form["txtCodigo"]));
            $rpta->assign("txtDescripcion", "value", "");
            $rpta->assign("txtOrden", "value", "");
            $rpta->script("$('#chk_activo').prop('checked', 'true')");
        } elseif ($result[0]['mensaje'] == 'MSG_002') {
            $rpta->alert(MSG_002);
            $rpta->assign("outQueryOpciones", "innerHTML", $interfaz->datagridOpciones($form["txtCodigo"]));
        } elseif ($result[0]['mensaje'] == 'MSG_003') {
            $rpta->alert(MSG_003);
            $rpta->assign("outQueryOpciones", "innerHTML", $interfaz->datagridOpciones($form["txtCodigo"]));
        } elseif ($result[0]['mensaje'] == 'MSG_004') {
            $rpta->alert(MSG_004);
        } elseif ($result[0]['mensaje'] == 'MSG_005') {
            $rpta->alert(MSG_005);
        }
    }
    return $rpta;
}

function _interfazVisualizarPregunta($flag, $pregunta)
{
    $rpta = new xajaxResponse('UTF-8');
    $cls = new interfazPregunta();
    $html = $cls->visualizarPregunta($pregunta);
    $rpta->script("$('#modal .modal-header h5').text('Visualizar Pregunta');");
    $rpta->assign("contenido", "innerHTML", $html[0]);
    $rpta->assign("footer", "innerHTML", $html[1]);
    $rpta->script("$('#modal').modal('show')");

    $rpta->script("$('.datepicker').datepicker({
        clearBtn: true,
        language: 'es'
    });");
    return $rpta;
}



$xajax->register(XAJAX_FUNCTION, '_interfazPregunta');
$xajax->register(XAJAX_FUNCTION, '_interfazPreguntaNuevo');
$xajax->register(XAJAX_FUNCTION, '_preguntaDatagrid');
$xajax->register(XAJAX_FUNCTION, '_preguntaMantenimiento');
$xajax->register(XAJAX_FUNCTION, '_interfazPreguntaEditar');
$xajax->register(XAJAX_FUNCTION, '_interfazOpcionNueva');
$xajax->register(XAJAX_FUNCTION, '_opcionMantenimiento');
$xajax->register(XAJAX_FUNCTION, '_interfazVisualizarPregunta');
