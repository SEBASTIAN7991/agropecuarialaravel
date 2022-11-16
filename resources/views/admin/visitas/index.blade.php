@extends('adminlte::page')

@section('title', 'Visitas')

@section('content_header')
<button type="button" name="FormVis" id="FormVis" class="btn btn-info"> <i class="fas fa-fw fa-plus"></i>Nueva Visita</button>
    <p></p>
<h1 class="text-center">Lista de Visitas Realizadas en la Coordinacion Agropecuaria</h1>
@stop

@section('content')
<!--TABLA DE VISTA DE CONSULTA DE AREAS REGISTRADAS-->
<table class="table table-striped table-bordered visitas"> 
    <thead>
        <tr>
            <th>Id</th>
            <th>Cargo</th>
            <th>Nombre</th>
            <th>Paterno</th>
            <th>Materno</th>
            <th>Localidad</th>
            <th>Fecha Visita</th>
            <th>Telefono</th>
            <th width="150px">Opciones</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!--modal agregar Nueva Visita-->
<div id="ModalAgregar" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="form_Vis" name="form_Vis" class="form-horizontal" method="post">
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
                        <div class="form-group col-md-4">
                            <label>Nombre</label>
                            <input type="text" name="Nombre" id="Nombre" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Apellido Paterno:</label>
                            <input type="text" name="Paterno" id="Paterno" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Apellido Materno:</label>
                            <input type="text" name="Materno" id="Materno" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">    
                            <label>Cargo del Visitante:</label><br>
                            <select class="form-control" id="Id_Cargo" name="Id_Cargo">
                                <option value="0" selected>Selecciona un Cargo</option>
                                @foreach($cargos as $cargo)
                                <option value="{{$cargo->id}}">{{$cargo->Nombre_Cargo}}</option>
                                @endforeach  
                            </select>
                        </div> 
                        <div class="form-group col-md-4">
                            <label>Localidad:</label><br>
                            <select class="form-control" id="Id_Loc" name="Id_Loc">
                                <option value="0" selected>Selecciona una Localidad:</option>
                                @foreach($localidades as $localidad)
                                <option value="{{$localidad->id}}">{{$localidad->Nom_Loc}}</option>
                                @endforeach  
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                                <label>Region:</label>
                                <input type="text" class="form-control" id="Id_Reg" name="Id_Reg"  style="display:none;">
                                <input type="text" class="form-control" id="Nom_Reg" name="Nom_Reg" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Nombre de Organizacion:</label>
                            <input type="text" name="Nom_Org" id="Nom_Org" class="form-control">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Numero de Telefono:</label>
                            <input type="text" name="Telefono" id="Telefono" class="form-control">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Fecha Visita:</label>
                            <input type="date" class="form-control" id="Fecha_Visita" name="Fecha_Visita">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Hora de Atencion:</label>
                            <input type="time" name="Hora_Ingreso" id="Hora_Ingreso" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Asunto:</label>
                            <textarea class="form-control" name="Asunto" id="Asunto"></textarea>
                        </div>
                    </div>    

                    <span id="form_result"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <input type="submit" class="btn btn-primary" name="btnGuardarVis" id="btnGuardarVis" value="Guardar" />
                </div>
            </form>  
        </div>
    </div>
</div>
<!--=============modal eliminar una visita=============-->
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
                    <h4 id="visita_Elimina" name="visita_Elimina"></h4>
                    <h4 id="visita_Asunto" name="visita_Asunto"></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <button type="button" name="btnElVis" id="btnElVis" class="btn btn-primary"><i class="fas fa-fw fa-check"></i>Eliminar</button>
                </div>
            </form>  
        </div>
    </div>
</div>
@stop

@section('css')
@stop

@section('js')
<script src="../vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="../vendor/datatables-plugins/responsive/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function(){
//======================================
    var table = $('.visitas').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
             stateSave: true,
            select: true,
            ajax: "{{ route('visitas.index') }}",
            columns: [
                {data: 'id'},
                {data: 'cargos.Nombre_Cargo'},
                {data: 'Nombre'},
                {data: 'Paterno'},
                {data: 'Materno'},
                {data: 'localidades.Nom_Loc'},
                {data: 'Fecha_Visita'},
                {data: 'Telefono'},
                {data: 'action_Vis'}
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
//=======================================
/* ABRIR MODAL DE REGISTRO DE NUEVA VISITA*/
        $('#FormVis').click(function(){
            $('#action').val('Add');//input para diferenciar con editar
            $('#form_result').html('');//el span donde aparece error
            $('#ModalAgregar').modal('show');
            $('.modal-title').text('Registrando Nueva Visita');
            $('#form_Vis')[0].reset();
            $("#Id_Cargo").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });
            $("#Id_Loc").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });
        });
//======================================
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
/* peticion de guardar o editar el registro*/
        $('#form_Vis').on('submit', function(event){
                event.preventDefault(); 
                var action_url = '';
                if($('#action').val() == 'Add'){
                    $('#btnGuardarVis').val('Guardando');//cambia valor de boton guardar
                    $('#action').val('');//input para diferenciar de editar
                    action_url = "{{ route('visitas.store') }}";
                }
        
                if($('#action').val() == 'Edit'){
                    $('#action').val('');//input para diferencia de guardar
                    $('#btnGuardarVis').val('Actualizando');//cambia el texto de boton editar
                    action_url = "{{ route('visitas.update') }}";
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
                                    title: 'La Visita ha sido ' +tipo +' correctamente',
                                    showConfirmButton: false,
                                    timer: 1500
                            })
                            $('#btnGuardarVis').val('Guardar');
                            $('#form_Vis')[0].reset();
                            $('#ModalAgregar').modal('hide');
                            $('.visitas').DataTable().ajax.reload();
                        }
                        $('#form_result').html(html);//coloca en caso de vacio input
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                });
        });
//===========================================
/* peticion de abrir modal de eliminar un registro */
    var Vis_Id;
    var Asunto_Vis;
    $(document).on('click', '.el_vis', function(){
        Vis_Id = $(this).attr('id');
        Asunto_Vis = $(this).attr('asunto');

        $('#ModalEliminar').modal('show');
        $('.modal-title').text('¿Desea Eliminar esta Visita?');
        document.getElementById('visita_Elimina').innerHTML ="ID: "+Vis_Id ;
        document.getElementById('visita_Asunto').innerHTML ="ASUNTO: "+Asunto_Vis;
    });
//==========================================
/*PETICION DE ELIMINAR UNA VISITA*/
    $('#btnElVis').click(function(){
        $.ajax({
            url:"visitas/destroy/"+Vis_Id,
            beforeSend:function(){
                $('#btnElVis').text('Eliminando');
                $('#btnElVis').prop('disabled', true);
            },
            success:function(data)
            {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Este Registro de Visita ha sido Borrado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                })
                $('#btnElVis').text('Eliminar');
                $('#btnElVis').prop('disabled', false);
                $('#ModalEliminar').modal('hide');
                $('.visitas').DataTable().ajax.reload();
            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
                Swal.fire({
                    icon: 'error',
                     title: 'algo salio mal...',
                     text: 'Actualiza la Pagina o contacta al administrador!'
                })               
                $('#btnElVis').text('Eliminar');
                $('#btnElVis').prop('disabled', false);
            }
        })
    });
//========================================
/*===========MODAL DE EDITAR VISITA Y CARGAR DATOS==============*/
$(document).on('click', '.edit', function(event){
        event.preventDefault(); 
        var id = $(this).attr('id'); //alert(id);
        $('#hidden_id').val(id);
        $('#action').val('Edit');//colocar que sera un edit
        $('#form_result').html('');//donde aparece el error de vacio
        $('#ModalAgregar').modal('show');
        $('.modal-title').text('¡¡Estas Editando los datos de una Visita');
        $('#btnGuardarVis').val('Actualizar');
            $("#Id_Cargo").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });
            $("#Id_Loc").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });

        $.ajax({
            url :"visitas/edit/"+id+"/",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"json",
            success:function(data)
            {
                console.log('success: '+data);
                $('#Nombre').val(data.result.Nombre);
                $('#Paterno').val(data.result.Paterno);
                $('#Materno').val(data.result.Materno);
                $('#Id_Cargo').val(data.result.Id_Cargo).change();
                $('#Id_Loc').val(data.result.Id_Loc).change();
                $('#Nom_Org').val(data.result.Nom_Org);
                $('#Fecha_Visita').val(data.result.Fecha_Visita);
                $('#Telefono').val(data.result.Telefono);
                $('#Hora_Ingreso').val(data.result.Hora_Ingreso);
                $('#Asunto').val(data.result.Asunto);          
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
//=============================================
});
</script>
@stop