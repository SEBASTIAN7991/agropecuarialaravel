@extends('adminlte::page')

@section('title', 'Validaciones')

@section('content_header')
    <div class="form-row">
        <div class="form-group col-md-2">
            <button type="button" name="FormVal" id="FormVal" class="btn btn-info"> <i class="fas fa-fw fa-plus"></i>Nueva Validacion</button>
        </div>
        <div class="form-group col-md-2">
              @can('admin.users')
                    <a class="btn btn-info" href="{{ route('validaciones.excel') }}">Exportar</a>
               @endcan  
        </div>
        <div class="form-group col-md-8">
            <h1 class="text-center">Lista de Solicitudes Validados</h1>
        </div>
    </div>
@stop

@section('content')
<!--TABLA DE VISTA DE CONSULTA DE AREAS REGISTRADAS-->
<table class="table table-striped table-bordered validaciones"> 
    <thead>
        <tr>
            <th>id</th>
            <th>Solicitud Validado</th>
            <th>Representante General</th>
            <th>Inicio de Validacion</th>
            <th>Total Validado</th>
            <th>H</th>
            <th>M</th>
            <th>Proyecto</th>
            <th>Fecha Actualizacion</th>
            <th width="130px">Opciones</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<!--modal agregar validaciones-->
<div id="ModalAgregar" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="form_Val" name="form_Val" class="form-horizontal" method="post">
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

                    <div class="form-row" style="background-color:   #1fc780">
                        <div class="form-group col-md-4">
                            <label>Solicitud Validado:</label>
                            <select class="form-control" id="Subrepresentante" name="Subrepresentante">
                                <option value="0" selected>Selecciona un Representante</option>
                                @foreach($mis_solicitudes as $solicitud)
                                <option value="{{$solicitud->id}}">{{$solicitud->Subrepresentante}}</option>
                                @endforeach  
                            </select>
                            <input type="text" class="form-control" id="Id_Sol" name="Id_Sol"  style="display:none;">      
                        </div>
                        <div class="form-group col-md-4">
                                <label>Nombre de la Organizacion:</label>
                                <input class="form-control" type="text" name="organizacion" id="organizacion" readonly />
                        </div>
                        <div class="form-group col-md-4">
                                <label>Nombre de la Localidad:</label>
                                <input class="form-control" type="text" name="localidad" id="localidad"  readonly />
                        </div>
                    </div>

                    <div class="form-row" style="background-color: #1fc780 ">
                        <div class="form-group col-md-6">
                                <label>Tipo Proyecto:</label>
                                <input class="form-control" type="text" name="proyecto" id="proyecto" readonly />
                        </div>
                        <div class="form-group col-md-2">
                                <label>Cant. Solicitado:</label>
                                <input class="form-control" type="text" name="Cant_Sol" id="Cant_Sol"  readonly />
                        </div>
                        <div class="form-group col-md-2">
                                <label>Ben. H:</label>
                                <input class="form-control" type="text" name="Ben_H" id="Ben_H"  readonly />
                        </div>
                        <div class="form-group col-md-2">
                                <label>Ben. M:</label>
                                <input class="form-control" type="text" name="Ben_M" id="Ben_M"  readonly />
                        </div>
                    </div>

                    <div class="form-row" style="background-color:  #bc8517 ">
                        <div class="form-group col-md-2">
                                <label>Inicio de Validacion:</label>
                                <input class="form-control" type="date" name="Fecha_Val_Inicio" id="Fecha_Val_Inicio"/>
                        </div>
                        <div class="form-group col-md-2">
                                <label>Finalizacion Validacion:</label>
                                <input class="form-control" type="date" name="Fecha_Val_Termino" id="Fecha_Val_Termino"/>
                        </div>
                        <div class="form-group col-md-2">
                                <label>Total Validado:</label>
                                <input class="form-control" type="text" name="Cant_Validado" id="Cant_Validado"/>
                        </div>
                        <div class="form-group col-md-1">
                                <label>Ben. H:</label>
                                <input class="form-control" type="text" name="Ben_H_Validado" id="Ben_H_Validado"/>
                        </div>
                        <div class="form-group col-md-1">
                                <label>Ben. M:</label>
                                <input class="form-control" type="text" name="Ben_M_Validado" id="Ben_M_Validado"/>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Estatus:</label>
                            <select class="form-control" id="Estatus" name="Estatus">
                                <option value="1">ACTIVO</option>
                                <option value="0">INACTIVO</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Proyecto:</label>
                            <select class="form-control" id="Id_Pro" name="Id_Pro" required>
                                <option value="" selected>Selecciona un Proyecto</option>
                                @foreach($proyectos as $proyecto)
                                <option value="{{$proyecto->id}}">{{$proyecto->Nom_Pro}}</option>
                                @endforeach  
                            </select>
                        </div>
                    </div>
                    <div class="form-row" style="background-color:  #bc8517 ">
                        <div class="form-group col-md-4">
                               <label>Validador:</label>
                               <input class="form-control" type="text" name="Resp_Valid" id="Resp_Valid"/>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Usuario que Registra los Beneficiarios:</label>
                            <select class="form-control" id="Id_Usuario" name="Id_Usuario">
                                <option value="0" selected>Selecciona un Usuario</option>
                                @foreach($mis_usuarios as $usuario)
                                <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                                @endforeach  
                            </select>   
                        </div>
                        <div class="form-group col-md-2">
                            <label>Expediente Verificado:</label>
                            <select class="form-control" id="Verificado" name="Verificado">
                                <option value="0" selected>Selecciona un Tipo</option>
                                <option value="1">Verificado</option>
                                <option value="2">Solo Lista</option>
                                <option value="3">Programado</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Tipo:</label>
                            <select class="form-control" id="Tipo_Asignacion" name="Tipo_Asignacion" required>
                                <option value="" selected>Selecciona</option>
                                <option value="CABILDO">CABILDO</option>
                                <option value="COMPROMISOS">COMPROMISOS</option>
                                <option value="SECCIONALES">SECCIONALES</option>
                                <option value="COORDINADORES">COORDINADORES</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row" style="background-color:  #bc8517 ">
                        <div class="form-group col-md-12">
                            <label>Observaciones:</label>
                            <textarea class="form-control" name="Comentario" id="Comentario"></textarea>
                        </div>
                    </div>

                    <span id="form_result"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <input type="submit" class="btn btn-primary" name="btnGuardarVal" id="btnGuardarVal" value="Guardar" />
                </div>
            </form>  
        </div>
    </div>
</div>
<!--modal eliminar una validacion empieza-->
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
                    <h4 id="val_editar" name="val_editar"></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <button type="button" name="btnElVal" id="btnElVal" class="btn btn-danger"><i class="fas fa-fw fa-check"></i>Eliminar</button>
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
$(document).ready(function(){
    //=============================================
    //LISTA DE LAS SOLICITUDES VALIDADOS
     var table = $('.validaciones').DataTable({
            processing: true,
            serverSide:true,
            responsive: true,
            stateSave:true,
            autoWidth: false,
            stateSave:true,
            ajax: "{{ route('validaciones.index') }}",
            columns: [
                {data: 'id'},
                {data: 'solicitudes.Subrepresentante'},
                {data: 'solicitudes.Tipo_Convenio'},
                {data: 'Fecha_Val_Inicio'},
                {data: 'Cant_Validado'},
                {data: 'Ben_H_Validado'},
                {data: 'Ben_M_Validado'},
                {data: 'proyectos.Nom_Pro'},
                {data: 'updated_at'},
                {data: 'action_Val'}
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
    //==========================================
    /* abrir modal de nuevo registro*/
        $('#FormVal').click(function(){
            $('#action').val('Add');//input para diferenciar con editar
            $('#form_result').html('');//el span donde aparece error
            $('#ModalAgregar').modal('show');
            $('.modal-title').text('Registrando Nueva Validacion');
            $('#form_Val')[0].reset();
            $('#Subrepresentante').select2({
                dropdownParent: $('#ModalAgregar'),
                width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input
            });
        });
    
    //==================================================
    //peticion de escuchar cambio de dato localidad
        $("#Subrepresentante").change(function(){
                var cod = document.getElementById("Subrepresentante").value;
                $.ajax({
                    url :"validaciones/VerDatos/"+cod+"/",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    dataType:"json",
                    success:function(data)
                    {
                        $('#organizacion').val(data.result.organizaciones.Nom_Org);
                        $('#localidad').val(data.result.localidades.Nom_Loc);
                        $('#proyecto').val(data.result.proyectos.Nom_Pro);
                        $('#Cant_Sol').val(data.result.Cant_Sol);
                        $('#Ben_H').val(data.result.Ben_H);
                        $('#Ben_M').val(data.result.Ben_M);
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                });
        });
    //================================================
    /* peticion de guardar o editar el registro */
        $('#form_Val').on('submit', function(event){
                event.preventDefault(); 
                var action_url = '';
                if($('#action').val() == 'Add'){
                    $('#btnGuardarSol').val('Guardando');//cambia valor de boton guardar
                    $('#action').val('');//input para diferenciar de editar
                    action_url = "{{ route('validaciones.store') }}";
                }
        
                if($('#action').val() == 'Edit'){
                    $('#action').val('');//input para diferencia de guardar
                    $('#btnGuardarSol').val('Actualizando');//cambia el texto de boton editar
                    action_url = "{{ route('validaciones.update') }}";
                }
        
                $.ajax({
                    type: 'post',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: action_url,
                    data:$(this).serialize(),
                    dataType: 'json',
                    success: function(data) {
                        var html = '';
                        if(data.errors){
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
                                position: 'Center',
                                icon: 'success',
                                title: 'La Validacion de Proyecto ha sido ' +tipo +' correctamente',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            $('#btnGuardarSol').val('Guardar');
                            $('#form_Val')[0].reset();
                            $('#ModalAgregar').modal('hide');
                            $('.validaciones').DataTable().ajax.reload();
                        }
                        $('#form_result').html(html);//coloca en caso de vacio input
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                });
        });

    //==================================================
    /* peticion de abrir modal de eliminar un registro */
    var val_id;
    var nombre_val;
    $(document).on('click', '.elival', function(){
        val_id = $(this).attr('id');
        nombre_val = $(this).attr('representante');
        $('#ModalEliminar').modal('show');
        $('.modal-title').text('¿Desea Eliminar esta Localidad?');
        document.getElementById('val_editar').innerHTML = nombre_val;
    });
    //==================================================
    /*PETICION DE ELIMINAR UN REGISTRO*/
    $('#btnElVal').click(function(){
        $.ajax({
            url:"validaciones/destroy/"+val_id,
            beforeSend:function(){
                $('#btnElVal').text('Eliminando');
            },
            success:function(data)
            {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'La Validacion ha sido Borrado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                })
                $('#btnElVal').text('Eliminar');
                $('#ModalEliminar').modal('hide');
                $('.validaciones').DataTable().ajax.reload();
            }
        })
    });
    //=============================================
    $(document).on('click', '.edit', function(event){
        event.preventDefault(); 
        var id = $(this).attr('id'); //alert(id);
        $('#Subrepresentante').select2({
                dropdownParent: $('#ModalAgregar'),
                width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input
            });
        $('#hidden_id').val(id);
        $('#form_result').html('');//donde aparece el error de vacio
        $('#ModalAgregar').modal('show');
        $('.modal-title').text('¡¡Estas Editando una Validacion');
        $('#btnGuardarVal').val('Actualizar');
        $.ajax({
            url :"validaciones/edit/"+id+"/",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"json",
            success:function(data)
            {
                console.log('success: '+data);
                $('#Subrepresentante').val(data.result.solicitudes.id).change();
                $('#Id_Pro').val(data.result.Id_Pro).change();
                $('#Nom_Loc').val(data.result.Nom_Loc);
                $('#Resp_Valid').val(data.result.Resp_Valid);            
                $('#Fecha_Val_Inicio').val(data.result.Fecha_Val_Inicio);
                $('#Fecha_Val_Termino').val(data.result.Fecha_Val_Termino);
                $('#Cant_Validado').val(data.result.Cant_Validado);
                $('#Ben_H_Validado').val(data.result.Ben_H_Validado);
                $('#Ben_M_Validado ').val(data.result.Ben_M_Validado);
                $('#Estatus ').val(data.result.Estatus);
                $('#Verificado').val(data.result.Verificado).change();
                $('#Id_Usuario').val(data.result.Id_Usuario).change();
                $('#Comentario').val(data.result.Comentario);
                $('#Tipo_Asignacion').val(data.result.Tipo_Asignacion).change();
                $('#hidden_id').val(id);//colocar valor del id oculto
                $('#action').val('Edit');//colocar que sera un edit
            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
            }
        })
    });
    //=============================================
});
</script>
@stop