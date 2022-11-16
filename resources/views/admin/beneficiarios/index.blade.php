@extends('adminlte::page')

@section('title', 'Beneficiarios')

@section('content_header')
    <div class="form-row">
        <div class="form-group col-md-2">
            <button type="button" name="FormBen" id="FormBen" class="btn btn-info"> <i class="fas fa-fw fa-plus"></i>Nuevo Beneficiario</button>
        </div>
        <div class="form-group col-md-2">
          @can('admin.users')
                <a class="btn btn-info" href="{{ route('beneficiarios.excel') }}">Exportar Beneficiarios</a>
           @endcan  
        </div>
        <div class="form-group col-md-8">
            <h1 class="text-center">Lista de Beneficiarios</h1>
        </div>
    </div>
    <!--<input type="text" name="id" id="id" value="{{ auth()->user()->id }}">-->
@stop

@section('content')
<div class="form-row">
    <div class="form-group col-md-4">
        <label>Lista de Validaciones Registradas:</label>
        <select class="form-control Id_Val" id="Id_Val" name="Id_Val">
            <option value="0">todos los registros</option>
            @foreach($mis_valid as $mis_valid)
            <option value="{{$mis_valid->id}}">{{$mis_valid->solicitudes->Subrepresentante}}</option>
            @endforeach  
        </select>      
    </div>
    <div class="form-group col-md-4">
        <label>Tipo de Representacion:</label>
        <input class="form-control" type="text" name="Tipo_Convenio" id="Tipo_Convenio" readonly>
    </div>

    <div class="form-group col-md-2">
        <label>Tot. Validado:</label>
        <input class="form-control" type="text" name="Cant_Validado" id="Cant_Validado" readonly>
    </div>
    <div class="form-group col-md-1">
        <label>Ben. Reg:</label>
        <input class="form-control" type="text" name="Ben_Reg" id="Ben_Reg" readonly>
    </div>
    @can('admin.users')
    <div class="form-group col-md-1">
        <label>Duplicados</label>
        <input type="text" class="form-control" name="duplicados" id="duplicados" readonly>
    </div>
    @endcan
    <input type="text" class="form-control" id="Id_Val2" value="0" name="Id_Val2" style="display:none;">
    <input type="text" class="form-control" id="Id_Sol" value="0" name="Id_Sol" style="display:none;">
</div>
<!--imprimir tipos de proyectos de este beneficiario-->
<div class="form-row">
    <div class="form-group col-md-2">
        <input type="text" class="form-control" placeholder="NOMBRE" name="nombre" id="nombre">
        <span id="result"></span>
    </div>
    <div class="form-group col-md-2">
        <input type="text" class="form-control" placeholder="CURP" name="curp" id="curp">
    </div>
    <div class="form-group col-md-2">
        <input type="text" class="form-control" placeholder="CLAVE ELECTOR" name="clave" id="clave">
    </div>
    <div class="form-group col-md-4">
        @can('admin.users')
        <select class="form-control" id="localidadbusca" name="localidadbusca">
            <option value="" selected>BUSCAR POR LOCALIDAD</option>
            @foreach($localidades_buscar as $localidades_buscar)
            <option value="{{$localidades_buscar->Id_Loc}}">{{$localidades_buscar->localidades->Nom_Loc}}</option>
            @endforeach  
        </select>
        @endcan      
    </div>
    <div class="form-group col-md-2">
        @can('admin.users')
        <select class="form-control" id="estatusbusca" name="estatusbusca">
            <option value="" selected>SELECCIONA</option>
            <option value="ACEPTADO">ACEPTADO</option>
            <option value="DUPLICADO Y RECHAZADO">DUPLICADO Y RECHAZADO</option>
            <option value="POSPUESTO A 2023">POSPUESTO A 2023</option>
        </select>
        @endcan      
    </div>
</div>

<table class="table table-striped table-bordered beneficiarios"> 
    <thead>
        <tr>
            <th>id</th>
            <th>Representante</th>
            <th>Validador</th>
            <th>Fecha Validacion</th>
            <th>Proyecto Asignado</th>
            <th>Nombre</th>
            <th>Paterno</th>
            <th>Materno</th>
            <th>Clave Elector</th>
            <th>Curp</th>
            <th>Localidad</th>
            <th>Region</th>
            <th>Estatus</th>
            <th>Usuario que registro</th>
            <th>Fecha Registro</th>
            <!--<th>Actualizado</th>-->
            <th width="150px">Opciones</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<!--=================Modal ver pdf=====================-->
<div id="ModalPdf" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="form_pdf" name="form_pdf" class="form-horizontal" method="post" enctype="multipart/form-data" >
            @csrf
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Visualizacion de La Solicitud</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fas fa-fw fa-times-circle"></i>
                    </button>
                </div>
                <div class="modal-body" id="modalVerBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cerrar</button>
                </div>
            </form>
            </div>
        </div>
    </div>

<!--==========inicia modal de guardar los datos=========-->
<div id="ModalAgregar" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="form_Ben" name="form_Ben" class="form-horizontal" method="post" enctype="multipart/form-data" >
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fas fa-fw fa-times-circle"></i>
                    </button>
                </div>
                <div class="modal-body" >

                    <div class="form-row" >
                        <div class="form-group col-md-12" style="display:none;">
                            <label>Apartado de Seccion de Verificacion de Tipo de Proceso</label>
                            <input class="form-control" type="text" name="action" id="action" value="Add" />
                            <input class="form-control" type="text" name="hidden_id" id="hidden_id"/>
                            <input class="form-control" type="text" name="Id_Val_Form" id="Id_Val_Form"/>
                            <input class="form-control" type="text" name="Id_Sol_Form" id="Id_Sol_Form"/>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Nombre</label>
                            <input type="text" name="Nom_Ben" id="Nom_Ben" class="form-control" pattern="[a-zA-ZÑÁÉÍÓÚáéíóú][a-zA-Zñáéíóú ]{1,}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Ap. Paterno:</label>
                            <input type="text" class="form-control" id="Pat_Ben" name="Pat_Ben" pattern="[a-zA-ZÑÁÉÍÓÚáéíóú][a-zA-Zñáéíóú ]{1,}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Ap. Materno:</label>
                            <input type="text" class="form-control" id="Mat_Ben" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Sexo:</label>
                            <select class="form-control" id="Sexo" name="Sexo" required>
                                <option value="">Selecciona</option>
                                <option value="HOMBRE">HOMBRE</option>
                                <option value="MUJER">MUJER</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Clave de elector:</label>
                            <input autocomplete="off" type="text" class="form-control" id="Clave_El" name="Clave_El" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Curp:</label>
                            <input autocomplete="off" type="text" class="form-control" id="Curp" name="Curp" required="true" 
                              placeholder="Ingrese su CURP" 
                              pattern="([A-Z]{4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM](AS|BC|BS|CC|CL|CM|CS|CH|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[A-Z]{3}[0-9A-Z]\d)" required>
                        </div>
                    </div>

                    <div class="form-row">
                        @can('admin.users')
                        <div class="form-group col-md-3">
                            <label>Estatus:</label>
                            <select class="form-control" id="Estatus" name="Estatus" required>
                                <option value="">Selecciona</option>
                                <option value="ACEPTADO">ACEPTADO</option>
                                <option value="DUPLICADO Y RECHAZADO">DUPLICADO Y RECHAZADO</option>
                                <option value="POSPUESTO A 2023">POSPUESTO A 2023</option>
                            </select>
                        </div>
                        @endcan
                        <div class="form-group col-md-4">
                            <label>Localidad del Beneficiario:</label><br>
                            <select class="form-control" id="Id_Loc1" name="Id_Loc1" required>
                                <option value="" selected>Selecciona una Localidad:</option>
                                @foreach($localidades as $localidad)
                                    <option value="{{$localidad->id}}">{{$localidad->Nom_Loc}}</option>
                                @endforeach  
                            </select>      
                        </div>
                        <div class="form-group col-md-1">
                            <label>Nuevo</label>
                            <button type="button" name="FormLoc" id="FormLoc" class="btn btn-success"> <i class="fas fa-fw fa-plus"></i></button>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Region del Beneficiario:</label>
                            <input type="text" class="form-control" id="Id_Reg1" name="Id_Reg1"  style="display:none;" required>
                            <input type="text" class="form-control" id="Nom_Reg1" name="Nom_Reg1" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label id="labelnombre" name="labelnombre"></label>
                            <input type="text" class="form-control" id="Archivo_Viejo" value="" name="Archivo_Viejo" style="display:none;">
                            <input type="text" class="form-control" id="Curp_Viejo" value="" name="Curp_Viejo" style="display:none;">  
                            <input class="form-control" type="file" id="Documentos" name="Documentos" accept=".pdf">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Proyecto Aprobado:</label><br>
                            <select class="form-control" id="Id_Pro" name="Id_Pro" required>
                                <option value="" selected>Selecciona un Proyecto:</option>
                                @foreach($proyectos as $proyecto)
                                    <option value="{{$proyecto->id}}">{{$proyecto->Nom_Pro}}</option>
                                @endforeach  
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Asunto:</label>
                            <textarea class="form-control" name="Comentario" id="Comentario"></textarea>
                        </div>
                    </div>

                    <span id="form_result"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <input type="submit" class="btn btn-primary" name="btnGuardarBen" id="btnGuardarBen" value="Guardar" />
                </div>
            </form>  
        </div>
    </div>
</div>
<!--=============modal eliminar un Beneficiario=============-->
<div class="modal fade" id="ModalEliminar" tabindex="-1" aria-labelledby="ModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="form_eliminar" class="form-horizontal">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 id="Ben_El" name="Ben_El"></h4>
                    <h4 id="Curp_El" name="Curp_El"></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <button type="button" name="btnElBen" id="btnElBen" class="btn btn-primary"><i class="fas fa-fw fa-check"></i>Eliminar</button>
                </div>
            </form>  
        </div>
    </div>
</div>
<!--modal agregar localidades-->
<div id="ModalAgregarLoc" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="form_loc" name="form_loc" class="form-horizontal">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregando Nueva Localidad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" >
                    <div class="form-group" style="display:none;">
                        <label>Apartado de Seccion de Verificacion de Tipo de Proceso</label>
                        <input type="text" name="action2" id="action2" value="Add" />
                        <input type="text" name="hidden_id2" id="hidden_id2"  />
                    </div>
                    <div class="form-group">
                        <label>Nombre Localidad : </label>
                        <input type="text" name="Nom_Loc" id="Nom_Loc" class="form-control" required />

                    </div>
                    <div class="form-group">
                        <label>Regiones</label><br>
                        <select class="form-control" id="Id_Reg" name="Id_Reg" required>
                            <option value="" selected>Selecciona una Region</option>
                            @foreach($regiones as $region)
                                <option value="{{$region->id}}">{{$region->Nom_Reg}}</option>
                            @endforeach  
                        </select>
                    </div>

                    <span id="form_result2"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <input type="submit" class="btn btn-primary" name="btnGuardarLoc" id="btnGuardarLoc" value="Guardar" />
                </div>
            </form>  
        </div>
    </div>
</div>
@stop

@section('css')
<style type="text/css">
   .modal-header {
        background:  #77d457 ;
        color: #030303;
    }
    .modal-footer {
        background:  #77d457 ;
        color: #030303;
    }
</style>
@stop
@section('js')
<script src="../vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="../vendor/datatables-plugins/responsive/js/dataTables.responsive.min.js"></script>
<script src="../vendor/datatables-plugins/select/js/dataTables.select.min.js"></script>

<!--<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>-->
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script>
$(document).ready(function(){
    $('#Id_Val').select2();//iniciar select2 de combo de lista de validaciones
    $("#localidadbusca").select2();
    //================================================
    const table = $('.beneficiarios').DataTable({
            processing: true,
            serverSide:true,
            responsive: true,
            autoWidth: false,
            //dom: 'Bfrtip',
            ajax: {
                    url: "{{ route('beneficiarios.datatable') }}",
                    data: function(d){
                        d.Id_Val = document.getElementById("Id_Val").value;
                        d.nombre = document.getElementById("nombre").value;
                        d.curp = document.getElementById("curp").value;
                        d.clave = document.getElementById("clave").value;
                        @can('admin.users')
                        d.localidadbusca = document.getElementById("localidadbusca").value;
                        d.estatusbusca = document.getElementById("estatusbusca").value;
                        @endcan
                    },
                },
            columns: [
                    {data: 'id', name:'id'},
                    {data: 'solicitudes.Subrepresentante', name:'solicitudes.Subrepresentante'},
                    {data: 'validaciones.Resp_Valid', name:'validaciones.Resp_Valid'},
                    {data: 'validaciones.Fecha_Val_Inicio', name:'validaciones.Fecha_Val_Inicio'},
                    {data: 'proyectos.Nom_Pro', name:'proyectos.Nom_Pro'},
                    {data: 'Nom_Ben',name:'Nom_Ben'},
                    {data: 'Pat_Ben',name:'Pat_Ben'},
                    {data: 'Mat_Ben',name:'Mat_Ben'},
                    {data: 'Clave_El',name:'Clave_El'},
                    {data: 'Curp',name:'Curp'},
                    {data: 'localidades.Nom_Loc',name:'localidades.Nom_Loc'},
                    {data: 'regiones.Nom_Reg',name:'regiones.Nom_Reg'},
                    {data: 'Estatus',name:'Estatus'},
                    {data: 'users.name',name:'users.name'},
                    {data: 'created_at',name:'created_at'},
                    {data: 'action_Ben',name:'action_Ben'},
            ],
           // buttons: [{ extend: 'excelHtml5', exportOptions: { columns: [ 0, 1, 2, 3,4,5,6,7]  } } ],
            "language": {
                "lengthMenu": "Numero _MENU_ registros por pagina",
                "zeroRecords": "Tabla Vacia No hay datos",
                "info": "pagina _PAGE_ de _PAGES_",
                "infoEmpty": "No existe ningun dato con esa busqueda",
                "infoFiltered": "(filtrado de _MAX_ total registros)",
                "search": "Buscador",
                "paginate":{
                    "next":"siguiente",
                    "previous":"anterior"
                }
            },
           
    });
@can('admin.beneficiarios.index')
 table.columns([1,2]).visible(false);//para ver cambios , hay que eliminar cache
 table.columns([3,4]).visible(false);
 table.columns([8,8]).visible(false);
 table.columns([12,12]).visible(false);
 table.columns([13,13]).visible(false);
 table.columns([14,14]).visible(false);
@endcan
@can('admin.users')
table.columns([1,2]).visible(true);//para ver cambios , hay que eliminar cache
 table.columns([3,4]).visible(true);
 table.columns([8,8]).visible(true);
 table.columns([12,12]).visible(true);
 table.columns([13,13]).visible(true);
 table.columns([14,14]).visible(true);
@endcan
$.fn.dataTable.ext.errMode = 'throw';
//======================================
    verificar_seleccion();//verificar opcion de combo seleccionado
//======================================
//visualizar tabla de beneficiarios
        /*const table = $('.beneficiarios').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                autoWidth: false,
                searching: true,
                ajax: {
                    url: "{{ route('beneficiarios.datatable') }}",
                    data: function(d){
                        d.Id_Val = document.getElementById("Id_Val").value;
                    },
                },
                columns: [
                    {data: 'id', name:'id'},
                    {data: 'solicitudes.Subrepresentante', name:'solicitudes.Subrepresentante'},
                    {data: 'Nom_Ben',name:'Nom_Ben'},
                    {data: 'Pat_Ben',name:'Pat_Ben'},
                    {data: 'Mat_Ben',name:'Mat_Ben'},
                    {data: 'Curp',name:'Curp'},
                    {data: 'localidades.Nom_Loc',name:'localidades.Nom_Loc'},
                    {data: 'Estatus',name:'Estatus'},
                    {data: 'updated_at',name:'updated_at'},
                    {data: 'action_Ben',name:'action_Ben'},
                ],
                "language": {
                    "lengthMenu": "Numero _MENU_ registros por pagina",
                    "zeroRecords": "Tabla Vacia No hay datos",
                    "info": "pagina _PAGE_ de _PAGES_",
                    "infoEmpty": "No existe ningun dato con esa busqueda",
                    "infoFiltered": "(filtrado de _MAX_ total registros)",
                    "search": "Buscador",
                    "paginate":{
                        "next":"siguiente",
                        "previous":"anterior"
                    }
                }

        }); */    
//====================================
/*funcion de verificar que opcion esta seleccionado*/
    function verificar_seleccion(){
            var val = document.getElementById("Id_Val").value;
            if(val==0){
                $('#nombre').val('');
                $('#curp').val('');
                $('#clave').val('');
                $('.beneficiarios').DataTable().ajax.reload();
               /* table.column(1)
                             .search("", true, false, true)
                             .draw();*/
                document.getElementById("FormBen").disabled = true;
                $('#Id_Val2').val('0');
                $('#Id_Sol').val('0');
                $('#Tipo_Convenio').val('0');
                $('#Cant_Validado').val('0');
                $('#Ben_Reg').val('0');
                //============================
                $.ajax({
                    url :"beneficiarios/duplicados/"+val+"/",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    dataType:"json",
                    success:function(data)
                    {
                        var tamaño=data.result;//tamaño de los duplicados en general
                        $('#duplicados').val(tamaño);
                    },
                    error: function(data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'algo salio mal al traer los datos...',
                            text: 'Actualiza la Pagina o contacta al administrador!'
                        })
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                });
                //============================
            }else{//selecciono una validacion
                document.getElementById("FormBen").disabled = false;
                $('#Id_Val').val(val);
                    $.ajax({
                        url :"validaciones/edit/"+val+"/",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        dataType:"json",
                        success:function(data)
                        {
                            $('#Id_Val2').val(val);
                            $('#Id_Sol').val(data.result.Id_Sol);
                            $('#Tipo_Convenio').val(data.result.solicitudes.Tipo_Convenio);
                            var validacion = data.result.Cant_Validado;
                            $('#Cant_Validado').val(data.result.Cant_Validado);
                            var combo = document.getElementById("Id_Val");
                            var selected = combo.options[combo.selectedIndex].text;
                            $('.beneficiarios').DataTable().ajax.reload();
                            /* table.column(1)
                             .search("^" + selected + "$", true, false, true)
                             .draw();
*/
                            //==========================mostrar beneficiarios registrados
                            $.ajax({
                                url :"beneficiarios/NumReg/"+val+"/",
                                dataType:"json",
                                success:function(data)
                                {
                                    var tope=data.result;
                                   $('#Ben_Reg').val(data.result);
                                   if(tope==validacion){
                                        document.getElementById("FormBen").disabled = true;
                                   }else{
                                        document.getElementById("FormBen").disabled = false;
                                   }
                                   //===============================
                                  $.ajax({
                                        url :"beneficiarios/VerProyectos/"+val+"/",
                                        dataType:"json",
                                        success:function(data)
                                        {
                                            var tamaño=data.result.length;
                                            let valores=[];
                                            var hola='';
                                            for(var i=0; i<=tamaño-1; i++){
                                              var pro=data.result[i]['proyectos']['Nom_Pro'];
                                              var total=data.result[i]['COUNT(Id_Pro)'];
                                              hola=pro+'='+total+' '+hola;
                                              //valores.push([pro,'=',total])
                                            }
                                            //const dataString = JSON.stringify(valores);
                                            let timerInterval
                                                Swal.fire({
                                                  title: 'DATOS DE LA VALIDACION!',
                                                  html: 'Esto se cerrara en <b></b> milliseconds.<br><br><br>'+hola,
                                                  timer: 3000,
                                                  timerProgressBar: true,
                                                  didOpen: () => {
                                                    Swal.showLoading()
                                                    const b = Swal.getHtmlContainer().querySelector('b')
                                                    timerInterval = setInterval(() => {
                                                      b.textContent = Swal.getTimerLeft()
                                                    }, 100)
                                                  },
                                                  willClose: () => {
                                                    clearInterval(timerInterval)
                                                  }
                                                })
                                                //===========================================
                                                @can('admin.users')
                                                $.ajax({
                                                    url :"beneficiarios/duplicados/"+val+"/",
                                                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                                    dataType:"json",
                                                    success:function(data)
                                                    {
                                                        var tamaño=data.result;//tamaño de los duplicados en general
                                                        $('#duplicados').val(tamaño);
                                                    },
                                                    error: function(data) {
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'algo salio mal al traer los datos...',
                                                            text: 'Actualiza la Pagina o contacta al administrador!'
                                                        })
                                                        var errors = data.responseJSON;
                                                        console.log(errors);
                                                    }
                                                });
                                                @endcan
                                                //===========================================
                                           /* Swal.fire(
                                                  'DATOS DE LO SOLICITADO',
                                                  hola,
                                                  'success'
                                            )*/

                                            //alert(valores.join('\n'));
                                            //const dataString = JSON.stringify(data);
                                            //console.log(data.length);
                                            //console.log(data);
                                            /*console.log(data.result[0]['COUNT(Id_Pro)']);
                                            console.log(data.result[0]['proyectos']['Nom_Pro']);
                                            console.log(data.result[1]['COUNT(Id_Pro)']);
                                            console.log(data.result[1]['proyectos']['Nom_Pro']);*/
                                        },
                                        error: function(data) {
                                            var errors = data.responseJSON;
                                            console.log(errors);
                                        }
                                    })
                                   //===============================  
                                },
                                error: function(data) {
                                    var errors = data.responseJSON;
                                    console.log(errors);
                                }
                            })
                            //==========================
                        },
                        error: function(data) {
                            var errors = data.responseJSON;
                            console.log(errors);
                        }
                    })
            }
    }
 //=================================
 //apartado de busqueda
 nombre.oninput = function() {
    $('.beneficiarios').DataTable().ajax.reload();
  };
 curp.oninput = function() {
    $('.beneficiarios').DataTable().ajax.reload();
  };
  clave.oninput = function() {
    $('.beneficiarios').DataTable().ajax.reload();
  };
  $("#localidadbusca").change(function(){
        $('.beneficiarios').DataTable().ajax.reload();
  });
  $("#estatusbusca").change(function(){
        $('.beneficiarios').DataTable().ajax.reload();
  });
 /*funcion de escuchar los cambios al seleccionar una opcion de la lista de validaciones*/
    $("#Id_Val").change(function(){
        verificar_seleccion();
    });
//=====================================
/* abrir modal de nuevo registro*/
        $('#FormBen').click(function(){
            var Validacion = document.getElementById("Id_Val2").value;
            var Solicitud = document.getElementById("Id_Sol").value;
            $('#action').val('Add');//input para diferenciar con editar
            $('#form_result').html('');//el span donde aparece error
            $('#ModalAgregar').modal('show');
            $('.modal-title').text('Registrando Nuevo Beneficiario');
            $('#form_Ben')[0].reset();
            document.getElementById('labelnombre').innerHTML
                = 'Doc. Beneficiario: ';
            $("#Id_Loc1").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });
            $('#Id_Val_Form').val(Validacion);
            $('#Id_Sol_Form').val(Solicitud);
        });
//=====================================
/* abrir modal registro de localidad*/
        $('#FormLoc').click(function(){
            $('#action2').val('Add');//input para diferenciar con editar
            $('#form_result2').html('');//el span donde aparece error
            $('#ModalAgregarLoc').modal('show');
            $('#form_loc')[0].reset();
        });
//========================================
/* peticion de guardar una localidad*/
        $('#form_loc').on('submit', function(event){
                event.preventDefault(); 
                var action_url = '';
                var contenido = document.getElementById("Nom_Loc").value;
                if($('#action2').val() == 'Add'){
                    $('#btnGuardarLoc').val('Guardando');//cambia valor de boton guardar
                    $('#btnGuardarLoc').prop('disabled', true);
                    $('#action2').val('');//input para diferenciar de editar
                    action_url = "{{ route('localidades.store') }}";
                }
        
                $.ajax({
                    type: 'post',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: action_url,
                    data:$(this).serialize(),
                    dataType: 'json',
                    success: function(data) {
                        var html = '';
                        if(data.success){
                            var tipo = data.success;
                            Swal.fire({
                                    position: 'Center',
                                    icon: 'success',
                                    title: 'La Localidad ha sido ' +tipo +' correctamente',
                                    showConfirmButton: false,
                                    timer: 1500
                            })
                            $('#btnGuardarLoc').val('Guardar');
                            $('#btnGuardarLoc').prop('disabled', false);
                            $('#form_loc')[0].reset();
                            $("#Id_Reg2").change();
                            $('#ModalAgregarLoc').modal('hide');
                            //==========================
                            $.ajax({
                                url :"localidades/datos/"+contenido+"/",
                                dataType:"json",
                                success:function(data)
                                {
                                     var nombre = data.result.Nom_Loc;
                                     var id = data.result.id;
                                     $('#Id_Loc1').append('<option value="' + id + '">' + nombre + '</option>');
                                     $('#Id_Loc1').val(id).change();
                                },
                                error: function(data) {
                                    var errors = data.responseJSON;
                                    console.log(errors);
                                    Swal.fire({
                                      icon: 'error',
                                      title: 'algo salio mal...',
                                      text: 'no se pudo enviar la localidad en la seleccion, actualiza la pagina o comunicate con el admnistrador!'
                                    })
                                }
                            })
                            //==========================
                        }
                        $('#form_result2').html(html);//coloca en caso de vacio input
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                        Swal.fire({
                          icon: 'error',
                          title: 'algo salio mal...',
                          text: 'actualiza la pagina o comunicate con el administrador!'
                        })
                        $('#btnGuardarLoc').val('Guardar');
                        $('#btnGuardarLoc').prop('disabled', false);
                    }
                });
        });
//============================================
/*verificar localidad seleccionado y obtener region*/
$("#Id_Loc1").change(function(){
        var cod = document.getElementById("Id_Loc1").value;
        if(cod==0){
            console.log('guardó un registro y el cambio es igual a 0');
        }else{
            $.ajax({
                url :"solicitudes/VerRegion/"+cod+"/",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType:"json",
                success:function(data)
                {
                    $('#Nom_Reg1').val(data.result.regiones.Nom_Reg);
                    $('#Id_Reg1').val(data.result.Id_Reg);
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    console.log(errors);
                }
            });
        }
});
//=====================================
/*funcion de ejecutar registro y actualizacion de beneficiarios*/
        $('#form_Ben').on('submit', function(event){
            event.preventDefault(); 
            var action_url = '';
                if($('#action').val() == 'Add'){
                    $('#btnGuardarBen').prop('disabled', true);
                    $('#btnGuardarBen').val('Guardando');//cambia valor de boton guardar
                    $('#action').val('');//input para diferenciar de editar
                    action_url = "{{ route('beneficiarios.store') }}";
                }
        
                if($('#action').val() == 'Edit'){
                    $('#action').val('');//input para diferencia de guardar
                    $('#btnGuardarBen').prop('disabled', true);
                    $('#btnGuardarBen').val('Actualizando');//cambia el texto de boton editar
                    action_url = "{{ route('beneficiarios.update') }}";
                }

            var file_data = $('#form_Ben').prop('Documentos')[0];
            var form = $("#form_Ben")[0];
            var form_data = new FormData(form);
                form_data.append('Id_Val', $('#Id_Val_Form').val());
                form_data.append('Id_Sol', $('#Id_Sol_Form').val());
                form_data.append('Id_Pro', $('#Id_Pro').val());
                form_data.append('Nom_Ben', $('#Nom_Ben').val());
                form_data.append('Pat_Ben', $('#Pat_Ben').val());
                form_data.append('Mat_Ben', $('#Mat_Ben').val());
                form_data.append('Sexo', $('#Sexo').val());
                form_data.append('Clave_El', $('#Clave_El').val());
                form_data.append('Curp', $('#Curp').val());
                form_data.append('Id_Loc', $('#Id_Loc1').val());
                form_data.append('Id_Reg', $('#Id_Reg1').val());
                form_data.append('Estatus', $('#Estatus').val());
                form_data.append('Documentos', file_data);
                form_data.append('Comentario', $('#Comentario').val());
               //para agregar parametros extras al formData
                    $.ajax({
                             url: action_url,
                             dataType: 'JSON',//cambia tu respuesta a la que uses
                             cache: false,
                             contentType: false,
                             processData: false,
                             data: form_data,
                             type: 'POST',
                             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                success: function(data) {
                                        var html = '';
                                        if(data.success){
                                            var tipo = data.success;
                                            Swal.fire({
                                                    icon: 'success',
                                                    title: 'El Beneficiario ha sido ' +tipo +' correctamente',
                                                    showConfirmButton: false,
                                                    timer: 1500
                                            })
                                            $('#btnGuardarBen').val('Guardar');
                                            $('#btnGuardarBen').prop('disabled', false);
                                            $('#form_Ben')[0].reset();
                                            $("#Sexo").change();
                                            $("#Id_Loc1").change();
                                            $("#Estatus").change();
                                            $("#Id_Reg1").val('');
                                            $("#Nom_Reg1").removeAttr("readonly");
                                            $("#Nom_Reg1").val('');
                                            $("#Nom_Reg1").attr("readonly","readonly");
                                            $('#ModalAgregar').modal('hide');
                                            $('.beneficiarios').DataTable().ajax.reload();
                                            var registrados = document.getElementById("Ben_Reg").value;
                                            var validados = document.getElementById("Cant_Validado").value;
                                            var suma=parseFloat(registrados)+(1);
                                            $("#Ben_Reg").val(suma);

                                            if(suma==validados){
                                                //========================
                                                    var id_val = document.getElementById("Id_Val").value;
                                                    $.ajax({
                                                        url :"validaciones/mod_ben_val/"+id_val+"/",
                                                        dataType:"json",
                                                        success:function(data)
                                                        {
                                                           location.reload();  
                                                        },
                                                        error: function(data) {
                                                            var errors = data.responseJSON;
                                                            console.log(errors);
                                                        }
                                                    })
                                                //========================
                                                
                                            }else{
                                                console.log('sigue siendo menos usuarios registrado al validado');
                                            }

                                        }
                                        $('#form_result').html(html);//coloca en caso de vacio input
                                    },
                                    error: function(data) {
                                        var errors = data.responseJSON;
                                        console.log(errors);
                                        Swal.fire({
                                          icon: 'error',
                                          title: 'algo salio mal...',
                                          text: 'Actualiza la Pagina o contacta al administrador!'
                                        })
                                        $('#btnGuardarBen').val('Guardar');
                                        $('#btnGuardarBen').prop('disabled', false);
                                    }
                    })
            });
//=========================================
/*modal de abrir vista y cargar datos a editar*/
$(document).on('click', '.edit', function(event){
        event.preventDefault(); 
        var id = $(this).attr('id'); //alert(id);
        $('#hidden_id').val(id);
        $('#action').val('Edit');//colocar que sera un edit
        $('#form_result').html('');//donde aparece el error de vacio
        $('#ModalAgregar').modal('show');
        $('.modal-title').text('¡¡Estas Editando los datos de un Beneficiario');
        $('#btnGuardarBen').val('Actualizar');
        $("#Id_Loc1").select2({
            dropdownParent: $('#ModalAgregar'),
            width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
        });

        $.ajax({
            url :"beneficiarios/edit/"+id+"/",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"json",
            success:function(data)
            {
                console.log('success: '+data);
                $('#Id_Val_Form').val(data.result.Id_Val);
                $('#Id_Sol_Form').val(data.result.Id_Sol);
                $('#Id_Pro').val(data.result.Id_Pro).change();                
                $('#Nom_Ben').val(data.result.Nom_Ben);
                $('#Pat_Ben').val(data.result.Pat_Ben);
                $('#Mat_Ben').val(data.result.Mat_Ben);
                $('#Sexo').val(data.result.Sexo).change();
                $('#Clave_El').val(data.result.Clave_El);
                $('#Curp').val(data.result.Curp);
                $('#Id_Loc1').val(data.result.Id_Loc).change();
                $('#Id_Reg1').val(data.result.Id_Reg).change();
                $('#Estatus').val(data.result.Estatus);
                document.getElementById('labelnombre').innerHTML
                = 'Doc. Beneficiario: '+data.result.Documentos;
                $('#Archivo_Viejo').val(data.result.Documentos);
                $('#Curp_Viejo').val(data.result.Curp);
                $('#Comentario').val(data.result.Comentario);           
            },
            error: function(data) {
                Swal.fire({
                    icon: 'error',
                    title: 'algo salio mal al traer los datos...',
                    text: 'Actualiza la Pagina o contacta al administrador!'
                })
                var errors = data.responseJSON;
                console.log(errors);
            }
        });
});
//===========================================
/* peticion de abrir modal de eliminar un registro */
    var Ben_Id;
    var Nombre_Ben;
    var Curp;
    $(document).on('click', '.El_Ben', function(){
        Ben_Id = $(this).attr('id');
        Nombre_Ben = $(this).attr('Nom_Ben');
        Pat_Ben = $(this).attr('Pat_Ben');
        Mat_Ben = $(this).attr('Mat_Ben');

        Curp = $(this).attr('Curp');
        $('#ModalEliminar').modal('show');
        $('.modal-title').text('¿Desea Eliminar este Beneficiario?');
        document.getElementById('Ben_El').innerHTML ="NOMBRE: "+Nombre_Ben+" "+Pat_Ben+" "+Mat_Ben ;
        document.getElementById('Curp_El').innerHTML ="CURP: "+Curp;
    });
//==========================================
/*PETICION DE ELIMINAR UN REGISTRO*/
    $('#btnElBen').click(function(){
        $.ajax({
            url:"beneficiarios/destroy/"+Ben_Id,
            beforeSend:function(){
                $('#btnElBen').text('Eliminando');
                $('#btnElBen').prop('disabled', true);
            },
            success:function(data)
            {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'El Beneficiario ha sido Borrado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                })
                $('#btnElBen').text('Eliminar');
                $('#btnElBen').prop('disabled', false);
                $('#ModalEliminar').modal('hide');
                $('.beneficiarios').DataTable().ajax.reload();
            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
                Swal.fire({
                    icon: 'error',
                     title: 'algo salio mal...',
                     text: 'Actualiza la Pagina o contacta al administrador!'
                })               
                $('#btnElBen').text('Eliminar');
                $('#btnElBen').prop('disabled', false);
            }
        })
    });
//========================================
    /* abrir modal de visualizar pdf*/
        $(document).on('click', '.ver_pdf_ben', function(){
            $('#ModalPdf').modal('show');
            var id = $(this).attr('id');
            $.ajax({
                url :"beneficiarios/pdf/"+id+"/",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType:"json",
                success:function(data)
                {
                    var val=data.result.Id_Val;
                    var curp=data.result.Curp;
                    var documento=data.result.Documentos;
                    var ruta='storage/Doc_Ben/'+val+'/'+curp+'/'+documento;
                    document.getElementById('modalVerBody').innerHTML = "<div><iframe width='100%' height='400px' src={!! asset('"+ruta+"')!!}></iframe></div>";
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    console.log(errors);
                }
            }); 
        });
//=========================================
    /* accion de cuando no hay pdf*/
        $(document).on('click', '.sin_pdf', function(){
            Swal.fire({
                icon: 'error',
                title: '!!SIN ARCHIVO!!',
                text: '!VE A LA OPCION DE EDITAR Y CARGA EL DOCUMENTO!'
            })
        });
//=========================================
});
</script>   
@stop