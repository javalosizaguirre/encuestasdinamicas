<?php
include_once RUTA_CLASES . 'encuestas.class.php';
include_once RUTA_CLASES . 'seccion.class.php';
class interfazEncuestas
{
    function principal()
    {
        $clsabstract = new interfazAbstract();
        $clsabstract->legenda('fa fa-user-edit', 'Editar');
        $clsabstract->legenda('fa fa-times-circle', 'Eliminar');
        $clsabstract->legenda('fas fa-puzzle-piece', 'Agregar Sección');
        $clsabstract->legenda('fas fa-question', 'Agregar Pregunta');



        $leyenda = $clsabstract->renderLegenda('30%');
        $titulo = "Encuestas";
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
            <div class="modal-dialog" role="document" id="modalMaestro">
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
            "titulo" => "Título",
            "campo" => "titulo",
            "width" => "400"
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
                "fn" => "xajax__encuestasMantenimiento",
                "msn" => "¿Esta seguro de eliminar el registro?",
                "parametros" => array(
                    "flag" => "3",
                    "campos" => array("encuesta")
                )
            )
        ));

        $Grid->accion(array(
            "icono" => "fa fa-user-edit",
            "titulo" => "Editar Usuario",
            "xajax" => array(
                "fn" => "xajax__interfazEncuestasEditar",
                "parametros" => array(
                    "flag" => "2",
                    "campos" => array("encuesta")
                )
            )
        ));

        $Grid->accion(array(
            "icono" => "fas fa-puzzle-piece",
            "titulo" => "Agregar Sección",
            "xajax" => array(
                "fn" => "xajax__interfazEncuestasSeccionNueva",
                "parametros" => array(
                    "flag" => "2",
                    "campos" => array("encuesta")
                )
            )
        ));

        $Grid->accion(array(
            "icono" => "fas fa-question",
            "titulo" => "Agregar Preguntas",
            "xajax" => array(
                "fn" => "xajax__interfazEncuestasPreguntaNueva",
                "parametros" => array(
                    "flag" => "2",
                    "campos" => array("encuesta")
                )
            )
        ));



        $Grid->data(array(
            "criterio" => $criterio,
            "total" => $Grid->totalRegistros,
            "pagina" => $pagina,
            "nRegPagina" => $nreg_x_pag,
            "class" => "encuestas",
            "method" => "buscar"
        ));
        $Grid->paginador(array(
            "xajax" => "xajax__encuestasDatagrid",
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

    function datagridSeccion($criterio, $total_regs = 0, $pagina = 1, $nreg_x_pag = 50)
    {
        $Grid = new eGrid();
        $Grid->numeracion();
        $Grid->columna(array(
            "titulo" => "Título",
            "campo" => "titulo",
            "width" => "400"
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
                "fn" => "xajax__seccionMantenimiento",
                "msn" => "¿Esta seguro de eliminar el registro?",
                "parametros" => array(
                    "flag" => "3",
                    "campos" => array("encuesta", "seccion")
                )
            )
        ));

        $Grid->accion(array(
            "icono" => "fa fa-user-edit",
            "titulo" => "Editar Usuario",
            "xajax" => array(
                "fn" => "xajax__interfazSeccionEditar",
                "parametros" => array(
                    "flag" => "2",
                    "campos" => array("encuesta", "seccion", "titulo", "orden", "activo")
                )
            )
        ));




        $Grid->data(array(
            "criterio" => $criterio,
            "total" => $Grid->totalRegistros,
            "pagina" => $pagina,
            "nRegPagina" => $nreg_x_pag,
            "class" => "seccion",
            "method" => "buscar"
        ));

        $html = $Grid->render();
        return $html;
    }


    function datagridPreguntasDisponibles($criterio, $total_regs = 0, $pagina = 1, $nreg_x_pag = 50)
    {
        $Grid = new eGrid();
        $Grid->numeracion();
        $Grid->columna(array(
            "titulo" => "Pregunta",
            "campo" => "nombre",
            "width" => "100"
        ));

        /*
        $Grid->columna(array(
            "titulo" => "Tipo de Pregunta",
            "campo" => "descripcion",
            "width" => "100"
        ));
        */


        $Grid->accion(array(
            "icono" => "fas fa-arrow-circle-left",
            "width" => "20",
            "fnCallback" => function ($row) {
                $cadena = '<a href="javascript:void(0)" style="color:black" onclick="xajax__operacionAgregarPregunta(\'' . $row["pregunta"] . '\', document.getElementById(\'lstSeccion\').value)"><i class="fas fa-arrow-circle-left"></i></a>';
                return $cadena;
            }
        ));


        $Grid->data(array(
            "criterio" => $criterio,
            "total" => $Grid->totalRegistros,
            "pagina" => $pagina,
            "nRegPagina" => $nreg_x_pag,
            "class" => "pregunta",
            "method" => "buscarDisponibles"
        ));

        $html = $Grid->render();
        return $html;
    }

    function datagridPreguntasAgregadas($criterio, $total_regs = 0, $pagina = 1, $nreg_x_pag = 50)
    {
        $Grid = new eGrid();
        $Grid->numeracion();
        $Grid->columna(array(
            "titulo" => "Pregunta",
            "campo" => "nombre",
            "width" => "100"
        ));

        /*
        $Grid->columna(array(
            "titulo" => "Tipo de Pregunta",
            "campo" => "descripcion",
            "width" => "100"
        ));
        */

        $Grid->accion(array(
            "icono" => "fas fa-arrow-circle-left",
            "width" => "20",
            "fnCallback" => function ($row) {
                $cadena = '<a href="javascript:void(0)" style="color:black" onclick="xajax__operacionAgregarPregunta(\'' . $row["pregunta"] . '\', \'' . $row["seccion"] . '\', \'2\')"><i class="fa fa-times-circle"></i></a>';
                return $cadena;
            }
        ));

        $Grid->data(array(
            "criterio" => $criterio,
            "total" => $Grid->totalRegistros,
            "pagina" => $pagina,
            "nRegPagina" => $nreg_x_pag,
            "class" => "pregunta",
            "method" => "buscarAgregadas"
        ));

        $html = $Grid->render();
        return $html;
    }

    public function interfazNuevo()
    {
        $html = '
            <form name="form" id="form" class="form-horizontal" onsubmit="return false" method="post">                
                
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" ><span style="color:red">(*)</span> Título:</label>
                        <div class="col-sm-9">
                            <textarea id="txtTitulo" name="txtTitulo" class="form-control" style="width:100%"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" ><span style="color:red">(*)</span> Bienvenida:</label>
                        <div class="col-sm-9">
                            <textarea id="txtBienvenida" name="txtBienvenida" class="form-control" style="width:100%"></textarea>
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" ><span style="color:red">(*)</span> Despedida:</label>
                        <div class="col-sm-9">
                            <textarea id="txtDespedida" name="txtDespedida" class="form-control" style="width:100%"></textarea>
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

    public function interfazEditar($encuesta)
    {
        $clase = new encuestas();
        $data = $clase->consultar('1', $encuesta);

        $html = '
            <form name="form" id="form" class="form-horizontal" onsubmit="return false" method="post">                
                    <input type="hidden" name="txtCodigo" id="txtCodigo" value="' . $encuesta . '">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" ><span style="color:red">(*)</span> Título:</label>
                        <div class="col-sm-9">
                            <textarea id="txtTitulo" name="txtTitulo" class="form-control" style="width:100%">' . $data[0]["titulo"] . '</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" ><span style="color:red">(*)</span> Bienvenida:</label>
                        <div class="col-sm-9">
                            <textarea id="txtBienvenida" name="txtBienvenida" class="form-control" style="width:100%">' . $data[0]["bienvenida"] . '</textarea>
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" ><span style="color:red">(*)</span> Despedida:</label>
                        <div class="col-sm-9">
                            <textarea id="txtDespedida" name="txtDespedida" class="form-control" style="width:100%">' . $data[0]["despedida"] . '</textarea>
                        </div>
                    </div>                                        
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" >Activo:</label>
                        <div class="col-sm-9">
                            <input id="chk_activo"  name="chk_activo" type="checkbox" value="1" style="vertical-align:middle" ' . ($data[0]["activo"] == '1' ? 'checked' : '') . '>
                        </div>
                    </div>                                                                                                                                                                                                                           
            </form>';

        $botones = '<span style="color:red">(*) Campos Obligatorios.</span>
                        <button type="button" class="btn btn-primary" id="btnEdiar"><i class="icon-ok icon-white"></i><span id="spanSave01">Guardar</span></button>                   
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="icon-remove"></i>Cerrar</button>                        
            </fieldset>
            </form>';
        return array($html, $botones);
    }


    public function interfazNuevaSeccion($encuesta)
    {
        $html = '
            <form name="form" id="form" class="form-horizontal" onsubmit="return false" method="post">                
                    <input type="hidden" id="hhddEncuesta" name="hhddEncuesta" value="' . $encuesta . '">
                    <input type="hidden" id="hhddSeccion" name="hhddSeccion">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" ><span style="color:red">(*)</span> Título:</label>
                        <div class="col-sm-9">
                            <textarea id="txtTitulo" name="txtTitulo" class="form-control" style="width:100%"></textarea>
                        </div>
                    </div>                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" ><span style="color:red">(*)</span> Despedida:</label>
                        <div class="col-sm-9">
                            <input style="text-align:right" type="text" id="txtOrden" name="txtOrden" onkeypress="return gKeyAceptaSoloDigitosPunto(event)" class="form-control span1">
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
                            <button type="button" class="btn btn-primary" id="btnAgregarSeccion"><i class="icon-ok icon-white"></i><span id="spanSave01">Agregar</span></button>
                        </div>
                    </div> 

                    

                    <div class="card-body" id="outQuerySeccion">
                        ' . $this->datagridSeccion($encuesta) . '
                    </div>
            </form>';

        $botones = '<span style="color:red">(*) Campos Obligatorios.</span>                        
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="icon-remove"></i>Cerrar</button>                        
            </fieldset>
            </form>';
        return array($html, $botones);
    }




    public function interfazNuevaPregunta($encuesta)
    {
        $claseseccion = new seccion();
        $dataseccion = $claseseccion->consultar("1", $encuesta);
        $html = '
            <form name="form" id="form" class="form-horizontal" onsubmit="return false" method="post">                
                    <input type="hidden" id="hhddEncuesta" name="hhddEncuesta" value="' . $encuesta . '">                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" ><span style="color:red">(*)</span> Sección:</label>
                        <div class="col-sm-8">
                            <select id="lstSeccion" name="lstSeccion" class="form-control">
                                <option value="">SELECCIONAR...</option>';
        foreach ($dataseccion as $value) {
            $html .= '<option value="' . $value["seccion"] . '">' . $value["titulo"] . '</option>';
        }
        $html .= '</select>
                        </div>
                    </div>                                                            
                    <div class="card-body" id="outQuerySeccion">
                        <table style="width:100%">
                            <tr>
                                <td style="vertical-align: text-top;width:44%">
                                    <div id="DivConten-Selec">
                                                                        
                                    </div>
                                </td>
                                <td style="width:2%">
                                </td>
                                <td style="vertical-align: text-top;width:44%">
                                    <div id="DivConten-NoSelec">
                                                                         
                                    </div>
                                </td>
                            </tr>                    
                        </table>
                    </div>
            </form>';

        $botones = '<span style="color:red">(*) Campos Obligatorios.</span>                        
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="icon-remove"></i>Cerrar</button>                        
            </fieldset>
            </form>';
        return array($html, $botones);
    }
}

function _interfazEncuestas()
{
    $rpta = new xajaxResponse();
    $cls = new interfazEncuestas();
    $html = $cls->principal();
    $rpta->assign("container", "innerHTML", $html);
    $rpta->script("
    $('#btnNuevo').unbind('click').click(function() {
        xajax__interfazEncuestasNuevo();
    });");
    $rpta->script("
    $('#btnBuscar').unbind('click').click(function() {
    xajax__encuestasDatagrid(document.getElementById('txtBuscar').value);
    });");
    $rpta->script("
    $('#txtBuscar').unbind('keypress').keypress(function() {
        validarEnter(event)
    });");
    return $rpta;
}

function _interfazEncuestasNuevo()
{
    $rpta = new xajaxResponse('UTF-8');
    $cls = new interfazEncuestas();
    $html = $cls->interfazNuevo();
    $rpta->script("$('#modalMaestro').removeClass('modal-lg');");
    $rpta->script("$('#modal .modal-header h5').text('Registrar Perfil');");
    $rpta->assign("contenido", "innerHTML", $html[0]);
    $rpta->assign("footer", "innerHTML", $html[1]);
    $rpta->script("$('#modal').modal('show')");
    $rpta->script("
        $('#btnGuardar').unbind('click').click(function() {
            xajax__encuestasMantenimiento('1',xajax.getFormValues('form'));
        });");
    return $rpta;
}

function _interfazEncuestasEditar($flag, $encuesta)
{
    $rpta = new xajaxResponse('UTF-8');
    $cls = new interfazEncuestas();
    $html = $cls->interfazEditar($encuesta);
    $rpta->script("$('#modalMaestro').removeClass('modal-lg');");
    $rpta->script("$('#modal .modal-header h5').text('Editar Encuesta');");
    $rpta->assign("contenido", "innerHTML", $html[0]);
    $rpta->assign("footer", "innerHTML", $html[1]);
    $rpta->script("$('#modal').modal('show')");
    $rpta->script("
        $('#btnEdiar').unbind('click').click(function() {
            xajax__encuestasMantenimiento('" . $flag . "',xajax.getFormValues('form'));
        });");
    return $rpta;
}

function _interfazEncuestasSeccionNueva($flag, $encuesta)
{
    $rpta = new xajaxResponse('UTF-8');
    $cls = new interfazEncuestas();
    $html = $cls->interfazNuevaSeccion($encuesta);
    $rpta->script("$('#modalMaestro').addClass('modal-lg');");
    $rpta->script("$('#modal .modal-header h5').text('Editar Encuesta');");
    $rpta->assign("contenido", "innerHTML", $html[0]);
    $rpta->assign("footer", "innerHTML", $html[1]);
    $rpta->script("$('#modal').modal('show')");
    $rpta->script("
        $('#btnAgregarSeccion').unbind('click').click(function() {
            xajax__seccionMantenimiento('1',xajax.getFormValues('form'));
        });");
    return $rpta;
}




function _encuestasDatagrid($criterio, $total_regs = 0, $pagina = 1, $nregs = 50)
{
    $rpta = new xajaxResponse();
    $cls = new interfazEncuestas();
    $html = $cls->datagrid($criterio, $total_regs, $pagina, $nregs);
    $rpta->assign("outQuery", "innerHTML", $html);
    return $rpta;
}

function _encuestasMantenimiento($flag, $form = '')
{
    $rpta = new xajaxResponse();
    $clase = new encuestas();
    $interfaz = new interfazEncuestas();
    if ($flag == '3') {
        $form = array("txtCodigo" => $form);
    }

    $msj1 = '';
    $msj = 'LOS SIGUIENTES CAMPOS SON REQUERIDOS (*)';
    $msj .= '\\n-----------------------------------------------------------------------\\n';


    if ($form["txtTitulo"] == '') {
        $msj1 .= '- Titulo\\n';
    }
    if ($form["txtBienvenida"] == '') {
        $msj1 .= '- Bienvenida\\n';
    }
    if ($form["txtDespedida"] == '') {
        $msj1 .= '- Despedida\\n';
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
        }
    }
    return $rpta;
}


function _seccionMantenimiento($flag, $form = '', $seccion = '')
{
    $rpta = new xajaxResponse();
    $clase = new seccion();
    $interfaz = new interfazEncuestas();
    if ($flag == '3') {
        $form = array("hhddEncuesta" => $form, "hhddSeccion" => $seccion);
    }

    $msj1 = '';
    $msj = 'LOS SIGUIENTES CAMPOS SON REQUERIDOS (*)';
    $msj .= '\\n-----------------------------------------------------------------------\\n';


    if ($form["txtTitulo"] == '') {
        $msj1 .= '- Titulo\\n';
    }
    if ($form["txtOrden"] == '') {
        $msj1 .= '- Orden\\n';
    }


    if ($msj1 != '' && $flag != '3') {
        $rpta->script('alert("' . $msj . $msj1 . '")');
    } else {

        $result = $clase->mantenedor($flag, $form);
        if ($result[0]['mensaje'] == 'MSG_001') {
            $rpta->alert(MSG_001);
            $rpta->assign("outQuerySeccion", "innerHTML", $interfaz->datagridSeccion($form["hhddEncuesta"]));
            $rpta->assign("txtTitulo", "value", "");
            $rpta->assign("txtOrden", "value", "");
        } elseif ($result[0]['mensaje'] == 'MSG_002') {
            $rpta->alert(MSG_002);
            $rpta->assign("outQuerySeccion", "innerHTML", $interfaz->datagridSeccion($form["hhddEncuesta"]));
            $rpta->assign("hhddSeccion", "value", "");
            $rpta->assign("txtTitulo", "value", "");
            $rpta->assign("txtOrden", "value", "");
            $rpta->assign("spanSave01", "innerHTML", "Agregar");
            $rpta->script("$('#chk_activo').prop('checked', true)");
            $rpta->script("
            $('#btnAgregarSeccion').unbind('click').click(function() {
                xajax__seccionMantenimiento('1',xajax.getFormValues('form'));
            });");
        } elseif ($result[0]['mensaje'] == 'MSG_003') {
            $rpta->alert(MSG_003);
            $rpta->assign("outQuerySeccion", "innerHTML", $interfaz->datagridSeccion($form["hhddEncuesta"]));
        } elseif ($result[0]['mensaje'] == 'MSG_004') {
            $rpta->alert(MSG_004);
        } elseif ($result[0]['mensaje'] == 'MSG_005') {
            $rpta->alert(MSG_005);
        }
    }
    return $rpta;
}

function _interfazSeccionEditar($flag, $encuesta, $seccion, $titulo, $orden, $activo)
{
    $rpta = new xajaxResponse();
    $rpta->assign("hhddEncuesta", "value", $encuesta);
    $rpta->assign("hhddSeccion", "value", $seccion);
    $rpta->assign("txtTitulo", "value", $titulo);
    $rpta->assign("txtOrden", "value", $orden);
    $rpta->assign("spanSave01", "innerHTML", "Actualizar");
    if ($activo == '1') {
        $rpta->script("$('#chk_activo').prop('checked', true)");
    } else {
        $rpta->script("$('#chk_activo').prop('checked', false)");
    }

    $rpta->script("
        $('#btnAgregarSeccion').unbind('click').click(function() {
            xajax__seccionMantenimiento('2',xajax.getFormValues('form'));
        });");

    return $rpta;
}


function _interfazEncuestasPreguntaNueva($flag, $encuesta)
{
    $rpta = new xajaxResponse('UTF-8');
    $cls = new interfazEncuestas();
    $html = $cls->interfazNuevaPregunta($encuesta);
    $rpta->script("$('#modalMaestro').addClass('modal-lg');");
    $rpta->script("$('#modal .modal-header h5').text('Agregar Pregunta');");
    $rpta->assign("contenido", "innerHTML", $html[0]);
    $rpta->assign("footer", "innerHTML", $html[1]);
    $rpta->script("$('#modal').modal('show')");
    $rpta->script("
        $('#btnAgregarSeccion').unbind('click').click(function() {
            xajax__seccionMantenimiento('1',xajax.getFormValues('form'));
        });");

    $rpta->script("
        $('#lstSeccion').unbind('change').change(function() {
            xajax__encuestasPreguntasAgregadas(xajax.getFormValues('form'));
            xajax__encuestasPreguntasDisponibles(xajax.getFormValues('form'));
        });");


    return $rpta;
}

function _encuestasPreguntasDisponibles($criterio, $total_regs = 0, $pagina = 1, $nregs = 50)
{
    $rpta = new xajaxResponse();
    $cls = new interfazEncuestas();
    $html = $cls->datagridPreguntasDisponibles($criterio, $total_regs, $pagina, $nregs);
    $rpta->assign("DivConten-NoSelec", "innerHTML", $html);
    return $rpta;
}

function _encuestasPreguntasAgregadas($criterio, $total_regs = 0, $pagina = 1, $nregs = 50)
{
    $rpta = new xajaxResponse();
    $cls = new interfazEncuestas();
    $html = $cls->datagridPreguntasAgregadas($criterio, $total_regs, $pagina, $nregs);
    $rpta->assign("DivConten-Selec", "innerHTML", $html);
    return $rpta;
}

function _operacionAgregarPregunta($pregunta, $seccion, $flag = '1')
{
    $rpta = new xajaxResponse();
    $claseencuesta = new encuestas();
    if ($seccion == '') {
        $rpta->alert("Debe Seleccionar una sección");
    } else {

        $result = $claseencuesta->mantenedorSeccionPregunta($flag, $seccion, $pregunta);
        $rpta->script("
                xajax__encuestasPreguntasAgregadas(xajax.getFormValues('form'));
                xajax__encuestasPreguntasDisponibles(xajax.getFormValues('form'));
                ");
    }
    return $rpta;
}

$xajax->register(XAJAX_FUNCTION, '_interfazEncuestas');
$xajax->register(XAJAX_FUNCTION, '_interfazEncuestasNuevo');
$xajax->register(XAJAX_FUNCTION, '_encuestasDatagrid');
$xajax->register(XAJAX_FUNCTION, '_encuestasMantenimiento');
$xajax->register(XAJAX_FUNCTION, '_interfazEncuestasEditar');
$xajax->register(XAJAX_FUNCTION, '_interfazEncuestasSeccionNueva');
$xajax->register(XAJAX_FUNCTION, '_seccionMantenimiento');
$xajax->register(XAJAX_FUNCTION, '_interfazSeccionEditar');
$xajax->register(XAJAX_FUNCTION, '_interfazEncuestasPreguntaNueva');
$xajax->register(XAJAX_FUNCTION, '_encuestasPreguntasAgregadas');
$xajax->register(XAJAX_FUNCTION, '_encuestasPreguntasDisponibles');
$xajax->register(XAJAX_FUNCTION, '_operacionAgregarPregunta');
