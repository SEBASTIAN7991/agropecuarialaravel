@extends('adminlte::page')

@section('title', 'Solicitudes')

@section('content_header')
<div class="form-row">
    <div class="form-group col-md-2">
        <button type="button" name="FormSol" id="FormSol" class="btn btn-info"> <i class="fas fa-fw fa-plus"></i>Nueva Solicitud</button>
    </div>
    <div class="form-group col-md-2">
        @can('admin.users')
        <a class="btn btn-info" href="{{ route('solicitudes.excel') }}">Exportar Solicitud</a>
        @endcan  
    </div>
    <div class="form-group col-md-8">
        <h1 class="text-center">Lista de Solicitudes Recepcionados H. Ayuntamiento Ocosingo, Chiapas.</h1>
    </div>
</div>
@stop

@section('content')
<!--TABLA DE VISTA DE CONSULTA DE AREAS REGISTRADAS-->
<table class="table table-striped table-bordered solicitudes"> 
    <thead>
        <tr>
            <th>id</th>
            <th>Fecha Solicitud</th>
            <th>Localidad</th>
            <th>Tipo Proyecto</th>
            <th>Entregado por</th>
            <th>Organizacion</th>
            <th>Fecha Actualizacion</th>
            <th width="150px">Opciones</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<!--modal eliminar una solicitud empieza-->
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
                    <h4 id="Sol_El" name="Sol_El"></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <button type="button" name="btnElSol" id="btnElSol" class="btn btn-primary"><i class="fas fa-fw fa-check"></i>Eliminar</button>
                </div>
            </form>  
        </div>
    </div>
</div>

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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <input type="submit" class="btn btn-primary" name="btnGuardarLoc" id="btnGuardarLoc" value="Guardar" />
                </div>
            </form>
            </div>
        </div>
    </div>

<!--modal agregar localidades-->
<div id="ModalAgregar" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="form_Sol" name="form_Sol" class="form-horizontal" method="post" enctype="multipart/form-data" >
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fas fa-fw fa-times-circle"></i>
                    </button>
                </div>
                <div class="modal-body" >

                    <div class="form-row" style="display:none;">
                        <div class="form-group col-md-6">
                            <label>Apartado de Seccion de Verificacion de Tipo de Proceso</label>
                            <input type="text" name="action" id="action" value="Add" />
                            <input type="text" name="hidden_id" id="hidden_id"  />
                        </div>
                    </div>

                    <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Nombre de la Organizacion:</label><br>
                                <select class="form-control" id="Id_Org" name="Id_Org">
                                    <option value="0" selected>Selecciona una Organizacion</option>
                                    @foreach($organizaciones as $organizacion)
                                        <option value="{{$organizacion->id}}">{{$organizacion->Nom_Org}}</option>
                                    @endforeach  
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Cargo del Solicitante:</label><br>
                                <select class="form-control" id="Id_Cargo" name="Id_Cargo">
                                    <option value="0" selected>Selecciona un Cargo</option>
                                    @foreach($cargos as $cargo)
                                    <option value="{{$cargo->id}}">{{$cargo->Nombre_Cargo}}</option>
                                    @endforeach  
                                </select>      
                            </div>
                    </div>

                    <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Fecha Solicitud:</label>
                                <input type="date" class="form-control" id="Fecha_Sol" name="Fecha_Sol" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Localidad de la Solicitud:</label><br>
                                <select class="form-control" id="Id_Loc" name="Id_Loc">
                                    <option value="0" selected>Selecciona una Localidad:</option>
                                    @foreach($localidades as $localidad)
                                        <option value="{{$localidad->id}}">{{$localidad->Nom_Loc}}</option>
                                    @endforeach  
                                </select>      
                            </div>
                            <div class="form-group col-md-4">
                                <label>Region de la Solicitud:</label>
                                <input type="text" class="form-control" id="Id_Reg" name="Id_Reg"  style="display:none;">
                                <input type="text" class="form-control" id="Nom_Reg" name="Nom_Reg" readonly>
                            </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Proyecto Solicitado:</label><br>
                            <select class="form-control" id="Id_Pro" name="Id_Pro">
                                <option value="0" selected>Selecciona Tipo de Proyecto</option>
                                @foreach($proyectos as $proyecto)
                                <option value="{{$proyecto->id}}">{{$proyecto->Nom_Pro}}</option>
                                @endforeach  
                            </select>      
                        </div>
                        <div class="form-group col-md-8">
                                <label>Entregado Por:</label>
                                <input type="text" class="form-control" id="Subrepresentante" name="Subrepresentante">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label>Telefono</label>
                            <input type="text" name="Telefono" id="Telefono" class="form-control" required>
                        </div>
                        <div class="form-group col-md-2">
                                <label>Cant. Solicitud:</label>
                                <input type="text" class="form-control" id="Cant_Sol" name="Cant_Sol">
                        </div>
                        <div class="form-group col-md-2">
                                <label>Ben H:</label>
                                <input type="text" class="form-control" id="Ben_H" name="Ben_H">
                        </div>
                        <div class="form-group col-md-2">
                                <label>Ben M:</label>
                                <input type="text" class="form-control" id="Ben_M" name="Ben_M">
                        </div>
                        <div class="form-group col-md-4">
                                <label id="labelnombre" name="labelnombre">Doc. Solicitud:</label>
                                <input type="text" class="form-control" id="ArchivoExis" value="" name="ArchivoExis" style="display:none;">
                                <input type="text" class="form-control" id="FolioExis" value="" name="FolioExis" style="display:none;">  
                                <input class="form-control" type="file" id="Ubicacion_Archivo" name="Ubicacion_Archivo" accept=".pdf">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Convenio:</label>
                            <select class="form-control" id="Convenio" name="Convenio">
                                <option value="0">Selecciona una Opcion</option>
                                <option value="SI">SI</option>
                                <option value="NO">NO</option>
                            </select>
                        </div>
                        <div class="form-group col-md-8">
                            <label>Tipo de Convenio</label>
                            <select class="form-control" id="Tipo_Convenio" name="Tipo_Convenio">
                                <option value="0">Sin Asignacion</option>
                                <option value="EMILIO TOVILLA">EMILIO TOVILLA</option>
                                <option value="PDE. MPAL. GILBERTO RODRIGUEZ DE LOS SANTOS">PDE. MPAL. GILBERTO RODRIGUEZ DE LOS SANTOS</option>
                                <option value="SINDICO MARIA DE JESUS CRUZ UTRILLA">SINDICO MARIA DE JESUS CRUZ UTRILLA</option>
                                <option value="1ER. REG. JOSE PEREZ GOMEZ">1ER. REG. JOSE PEREZ GOMEZ</option>
                                <option value="2DO. REG. MARIA LUISA PEREZ JIMENEZ">2DO. REG. MARIA LUISA PEREZ JIMENEZ</option>
                                <option value="3ER. REG. ISRAEL RUIZ DIAZ">3ER. REG. ISRAEL RUIZ DIAZ</option>
                                <option value="4TO. REG. MARIANA JIMENEZ GARCIA">4TO. REG. MARIANA JIMENEZ GARCIA</option>
                                <option value="5TO. REG. ALONSO PEREZ SANCHEZ">5TO. REG. ALONSO PEREZ SANCHEZ</option>
                                <option value="6TO. REG. CLAUDIA ITZEL NAJERA SELVAS">6TO. REG. CLAUDIA ITZEL NAJERA SELVAS</option>
                                <option value="REG. HERLINA CLEOBETH DOMINGUEZ GONZALES">REG. HERLINA CLEOBETH DOMINGUEZ GONZALES</option>
                                <option value="REG. VICTOR MANUEL ORDONEZ PENAGOS">REG. VICTOR MANUEL ORDONEZ PENAGOS</option>
                                <option value="PRESIDENTE MUNICIPAL">PRESIDENTE MUNICIPAL</option>
                                <option value="MATIAZ MORALES HERNANDEZ">MATIAZ MORALES HERNANDEZ</option>
                                <option value="LIC. ESTHER GUTIERREZ GARCIA">LIC. ESTHER GUTIERREZ GARCIA</option>
                                <option value="LIC. JOSUE ARCOS SANTIZ">LIC. JOSUE ARCOS SANTIZ</option>
                                <option value="ARTURO MUÑOZ ALFONZO">ARTURO MUÑOZ ALFONZO</option>
                                <option value="EUSEBIO RODAS VAZQUEZ">EUSEBIO RODAS VAZQUEZ</option>
                                <option value="FRANCISCO LOPEZ">FRANCISCO LOPEZ</option>
                                <option value="ROBERTO GOMEZ SANTIZ">ROBERTO GOMEZ SANTIZ</option>
                                <option value="SOLICITUD_SIMPLE">SOLICITUD_SIMPLE</option>
                                <option value="PADRON">PADRON</option>
                                <option value="NOMBRAMIENTO">NOMBRAMIENTO</option>
                                <option value="NOMBRAMIENTO_AMADEO">NOMBRAMIENTO_AMADEO</option>
                                <option value="NOMBRAMIENTO_PADRON">NOMBRAMIENTO_PADRON</option>
                                <option value="FIRMADO_PRESIDENTE">FIRMADO_PRESIDENTE</option>
                                <option value="AUTORIZADO_PRESIDENTE">AUTORIZADO_PRESIDENTE</option>
                                <option value="AUTORIZADO_PRESIDENTE_SECRETARIO_MPAL">AUTORIZADO_PRESIDENTE_SECRETARIO_MPAL</option>
                                <option value="AUTORIZADO_PRESIDENTE_2023">AUTORIZADO_PRESIDENTE_2023</option>
                                <option value="VICTORIO RUIZ LOPEZ">VICTORIO RUIZ LOPEZ</option>
                                <option value="SECCION CARMEN">SECCION CARMEN</option>
                                <option value="FIRMADO_REGIDORES">FIRMADO_REGIDORES</option>
                                <option value="FIRMADO_COORDINADOR">FIRMADO_COORDINADOR</option>
                                <option value="JOSELUISMUÑOZ">JOSELUISMUÑOZ</option>
                                <option value="MORENA">MORENA</option>
                                <option value="RECHAZADO">RECHAZADO</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Comentario:</label>
                            <textarea class="form-control" name="Comentario" id="Comentario"></textarea>
                        </div>
                    </div>  

                    <span id="form_result"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <input type="submit" class="btn btn-primary" name="btnGuardarSol" id="btnGuardarSol" value="Guardar" />
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

<script>

    jQuery.fn.DataTable.ext.type.search.string = function ( data ) {
    return ! data ?
        '' :
        typeof data === 'string' ?
            data
                .replace( /έ/g, 'ε')
                .replace( /ύ/g, 'υ')
                .replace( /ό/g, 'ο')
                .replace( /ώ/g, 'ω')
                .replace( /ά/g, 'α')
                .replace( /ί/g, 'ι')
                .replace( /ή/g, 'η')
                .replace( /\n/g, ' ' )
                .replace( /[áÁ]/g, 'a' )
                .replace( /[éÉ]/g, 'e' )
                .replace( /[íÍ]/g, 'i' )
                .replace( /[óÓ]/g, 'o' )
                .replace( /[úÚ]/g, 'u' )
                .replace( /ê/g, 'e' )
                .replace( /î/g, 'i' )
                .replace( /ô/g, 'o' )
                .replace( /è/g, 'e' )
                .replace( /ï/g, 'i' )
                .replace( /ü/g, 'u' )
                .replace( /ã/g, 'a' )
                .replace( /õ/g, 'o' )
                .replace( /ç/g, 'c' )
                .replace( /ì/g, 'i' ) :
            data;
};
$(document).ready(function(){
    //========================================
    var table = $('.solicitudes').DataTable({
            processing: true,
            serverSide:true,
            responsive: true,
            autoWidth: false,
            stateSave: true,
            ajax: "{{ route('solicitudes.index') }}",
            columns: [
                {data: 'id'},
                {data: 'Fecha_Sol'},
                {data: 'localidades.Nom_Loc'},
                {data: 'proyectos.Nom_Pro'},
                {data: 'Subrepresentante'},
                {data: 'organizaciones.Nom_Org'},
                {data: 'updated_at'},
                {data: 'action_Sol'}
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
            },
    });
$.fn.dataTable.ext.errMode = 'throw';
    //======================================
/* abrir modal de nuevo registro*/
        $('#FormSol').click(function(){
            $('#action').val('Add');//input para diferenciar con editar
            $('#form_result').html('');//el span donde aparece error
            $('#ModalAgregar').modal('show');
            $('.modal-title').text('Registrando Nueva Solicitud');
            $('#form_Sol')[0].reset();
            document.getElementById('labelnombre').innerHTML
                = 'Doc. Solicitud: ';
            $('#Nom_Reg').val('')
            $('#Id_Org').select2({
                dropdownParent: $('#ModalAgregar'),
                width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input
            });
            $("#Id_Cargo").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });
            $("#Id_Loc").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });
            $("#Id_Pro").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });
            $("#Tipo_Convenio").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });

        });
    //=====================================
//peticion de escuchar cambio de dato localidad
$("#Id_Loc").change(function(){
        var cod = document.getElementById("Id_Loc").value;
        $.ajax({
            url :"solicitudes/VerRegion/"+cod+"/",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"json",
            success:function(data)
            {
                $('#Nom_Reg').val(data.result.regiones.Nom_Reg);
                $('#Id_Reg').val(data.result.Id_Reg);
            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
            }
        });
});
//=============================================
/* peticion de guardar o editar el registro */
        $('#form_Sol').on('submit', function(event){
            event.preventDefault(); 
            var contenido = document.getElementById("action").value;
            var action_url = '';
                if($('#action').val() == 'Add'){
                    $('#btnGuardarSol').val('Guardando');//cambia valor de boton guardar
                    $('#action').val('');//input para diferenciar de editar
                    action_url = "{{ route('solicitudes.store') }}";
                }
        
                if($('#action').val() == 'Edit'){
                    $('#action').val('');//input para diferencia de guardar
                    $('#btnGuardarSol').val('Actualizando');//cambia el texto de boton editar
                    action_url = "{{ route('solicitudes.update') }}";
                }

            var file_data = $('#form_Sol').prop('Ubicacion_Archivo')[0];
            var form = $("#form_Sol")[0];
            var form_data = new FormData(form);
                form_data.append('Ubicacion_Archivo', file_data);
                form_data.append('Id_Org', $('#Id_Org').val());
                form_data.append('Id_Cargo', $('#Id_Cargo').val());
                form_data.append('Fecha_Sol', $('#Fecha_Sol').val());
                form_data.append('Id_Loc', $('#Id_Loc').val());
                form_data.append('Id_Reg', $('#Id_Reg').val());
                form_data.append('Id_Pro', $('#Id_Pro').val());
                form_data.append('Subrepresentante', $('#Subrepresentante').val());
                form_data.append('Telefono', $('#Telefono').val());
                form_data.append('Cant_Sol', $('#Cant_Sol').val());
                form_data.append('Ben_H', $('#Ben_H').val());
                form_data.append('Ben_M', $('#Ben_M').val());
                form_data.append('Convenio', $('#Convenio').val());
                form_data.append('Tipo_Convenio', $('#Tipo_Convenio').val());
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
                                        if(data.errors){
                                            
                                            alert(contenido);
                                            if(contenido == 'Add'){
                                                $('#btnGuardarSol').val('Guardar');
                                            }
                                    
                                            if(contenido == 'Edit'){
                                                $('#btnGuardarSol').val('Actualizar');
                                            }

                                            Swal.fire({
                                                icon: 'error',
                                                title: 'FALTO LLENAR ALGUN CAMPO',
                                                text: 'LLENA TODOS LOS CAMPOS!',
                                            })
                                            html = '<div class="alert alert-danger">';
                                            for(var count = 0; count < data.errors.length; count++){
                                                html += '<p>' + data.errors[count] + '</p>';
                                            }
                                            html += '</div>';
                                        }
                                        if(data.success){
                                            var tipo = data.success;
                                            Swal.fire({
                                                    icon: 'success',
                                                    title: 'La Solicitud ha sido ' +tipo +' correctamente',
                                                    showConfirmButton: false,
                                                    timer: 1500
                                            })
                                            $('#btnGuardarSol').val('Guardar');
                                            $('#form_Sol')[0].reset();
                                            $("#Id_Org").change();
                                            $("#Id_Cargo").change();
                                            //$("#Id_Loc").change(); marca erro al hacer cambio
                                            $("#Id_Pro").change();
                                            $("#Id_Reg").val('');
                                            $("#Nom_Reg").removeAttr("readonly");
                                            $("#Nom_Reg").val('');
                                            $("#Nom_Reg").attr("readonly","readonly");
                                            $("#Convenio").change();
                                            $("#Tipo_Convenio").change();
                                            $("#Comentario").val('');
                                            $('#ModalAgregar').modal('hide');
                                            $('.solicitudes').DataTable().ajax.reload();
                                        }
                                        $('#form_result').html(html);//coloca en caso de vacio input
                                    },
                                    error: function(data) {
                                        var errors = data.responseJSON;
                                        console.log(errors);
                                    }
                    })
            });
//========================================
/* peticion de abrir modal de eliminar un registro */
    var sol_id;
    var nombre_sol;
    $(document).on('click', '.elisol', function(){
        sol_id = $(this).attr('id');
        nombre_sol = $(this).attr('solicitud');
        $('#ModalEliminar').modal('show');
        $('.modal-title').text('¿Desea Eliminar esta Solicitud?');
        document.getElementById('Sol_El').innerHTML = nombre_sol;
    });

/*PETICION DE ELIMINAR UN REGISTRO*/
    $('#btnElSol').click(function(){
        $.ajax({
            url:"solicitudes/destroy/"+sol_id,
            beforeSend:function(){
                $('#btnElSol').text('Eliminando');
            },
            success:function(data)
            {
                
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'La Solicitud ha sido Borrado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                })
                $('#btnElSol').text('Eliminar');
                $('#ModalEliminar').modal('hide');
                $('.solicitudes').DataTable().ajax.reload();
            }
        })
    });
/*peticion de abrir vista editar y cargar los datos*/
     $(document).on('click', '.edit', function(event){
        event.preventDefault(); 
        var id = $(this).attr('id'); //alert(id);
        $('#hidden_id').val(id);
        $('#form_result').html('');//donde aparece el error de vacio
        $('#ModalAgregar').modal('show');
        $('.modal-title').text('¡¡Estas Editando una Solicitud¡¡');
        $('#btnGuardarSol').val('Actualizar');
        $('#Id_Org').select2({
                dropdownParent: $('#ModalAgregar'),
                width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input
            });
            $("#Id_Cargo").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });
            $("#Id_Loc").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });
            $("#Id_Pro").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });
            $("#Tipo_Convenio").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });

        $.ajax({
            url :"solicitudes/edit/"+id+"/",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"json",
            success:function(data)
            {
                console.log('success: '+data);
                $('#Id_Org').val(data.result.Id_Org).change();
                $('#Id_Cargo').val(data.result.Id_Cargo).change();
                $('#Fecha_Sol').val(data.result.Fecha_Sol);
                $('#Id_Loc').val(data.result.Id_Loc).change();
                $('#Id_Reg').val(data.result.Id_Reg).change();
                $('#Id_Pro').val(data.result.Id_Pro).change();
                $('#Subrepresentante').val(data.result.Subrepresentante);
                $('#Telefono').val(data.result.Telefono);
                $('#Cant_Sol').val(data.result.Cant_Sol);
                $('#Ben_H').val(data.result.Ben_H);
                $('#Ben_M').val(data.result.Ben_M);
                document.getElementById('labelnombre').innerHTML
                = 'Doc. Solicitud: '+data.result.Ubicacion_Archivo;
                $('#ArchivoExis').val(data.result.Ubicacion_Archivo);
                $('#FolioExis').val(data.result.Folio);
                $('#Convenio').val(data.result.Convenio).change();
                $('#Tipo_Convenio').val(data.result.Tipo_Convenio).change();
                $('#Comentario').val(data.result.Comentario);
                $('#hidden_id').val(id);//colocar valor del id oculto
                $('#action').val('Edit');//colocar que sera un edit
            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
                Swal.fire({
                    icon: 'error',
                    title: '!!NO EDITABLE!!',
                    text: '!OCURRIO UN ERROR AL LLENAR LOS DATOS Y NO ES EDITABLE!'
                })
            }
        });
    });
//========================================
    /* abrir modal de visualizar pdf*/
        $(document).on('click', '.ver_pdf_sol', function(){
            $('#ModalPdf').modal('show');
            var folio = $(this).attr('folio');
            var archivo = $(this).attr('archivo');
            var ruta='storage/Doc_Sol/'+folio+'/'+archivo;
                    document.getElementById('modalVerBody').innerHTML = "<div><iframe width='100%' height='400px' src={!! asset('"+ruta+"')!!}></iframe></div>"; 
        });
//=========================================
//========================================
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