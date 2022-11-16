@extends('adminlte::page')

@section('title', 'Cargos')

@section('content_header')
<button type="button" name="abrir_form" id="abrir_form" class="btn btn-success"> <i class="fas fa-fw fa-user"></i> Nuevo Cargo</button>
<p></p>
    <h1 class="text-center">Lista de Cargos de Solicitudes</h1>
@stop

@section('content')
<!--TABLA DE VISTA DE CONSULTA DE AREAS REGISTRADAS-->
<table class="table table-striped table-bordered cargos"> 
    <thead>
        <tr>
            <th>Id</th>
            <th>Tipo Cargo</th>
            <th>Fecha Registro</th>
            <th>Fecha Actualizacion</th>
            <th width="180px">Action</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<!--modal eliminar un area empieza-->
<div class="modal fade" id="ModalEliminar" tabindex="-1" aria-labelledby="ModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="form_eliminar" class="form-horizontal">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Eliminando Registro de Cargo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 style="margin:0;">Esta Seguro de Eliminar este Cargo</h4>
                    <p></p>
                    <h4 id="cargo_editar" name="cargo_editar"></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <button type="button" name="btnElCargo" id="btnElCargo" class="btn btn-danger"><i class="fas fa-fw fa-check"></i>Eliminar</button>
                </div>
            </form>  
        </div>
    </div>
</div>

<!--modal agregar inicio-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="ModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="form_cargo" name="form_cargo" class="form-horizontal">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group" style="display:none;" id="oculto">
                        <label>Apartado de Seccion de Verificacion de Tipo de Proceso</label>
                        <input type="text" name="action" id="action" value="Add" readonly />
                        <input type="text" name="hidden_id" id="hidden_id" readonly />
                    </div>
                    <div class="form-group">
                        <label>Nombre del Cargo : </label>
                        <input type="text" name="Nombre_Cargo" id="Nombre_Cargo" class="form-control" />
                    </div>
                    <span id="form_result"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <input type="submit" class="btn btn-primary" name="GuardarCargo" id="GuardarCargo" value="Nuevo" />
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
        div = document.getElementById('oculto');
        div.style.display = 'none';

        var table = $('.cargos').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: "{{ route('cargos.index') }}",
            columns: [
                {data: 'id'},
                {data: 'Nombre_Cargo'},
                {data: 'created_at'},
                {data: 'updated_at'},
                {data: 'action_cargo'}
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

        /* abrir modal de nuevo registro*/
        $('#abrir_form').click(function(){
            $('#action').val('Add');
            $('#form_result').html('');
            $('#ModalAgregar').modal('show');
            $('.modal-title').text('Nueva Cargo a Registrar');
        });

        /* peticion de guardar o editar el registro */
        $('#form_cargo').on('submit', function(event){
                event.preventDefault(); 
                var action_url = '';
                if($('#action').val() == 'Add'){
                    $('#GuardarCargo').val('Guardando');
                    $('#action').val('');
                    action_url = "{{ route('cargos.store') }}";
                }
        
                if($('#action').val() == 'Edit'){
                    $('#action').val('');
                    $('#GuardarCargo').val('Actualizando');
                    action_url = "{{ route('cargos.update') }}";
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
                            Swal.fire({
                                    position: 'Center',
                                    icon: 'success',
                                    title: 'El Cargo ha sido guardado correctamente',
                                    showConfirmButton: false,
                                    timer: 1500
                            })
                            $('#GuardarCargo').val('Nuevo');
                            $('#form_cargo')[0].reset();
                            $('#ModalAgregar').modal('hide');
                            $('.cargos').DataTable().ajax.reload();
                        }
                        $('#form_result').html(html);
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                });
        });
/* peticion de abrir modal de eliminar un registro */
    var cargo_id;
    var nombre_cargo;
    $(document).on('click', '.el_cargo', function(){
        $('.modal-title').text('Eliminar Cargo de Solicitudes');
        cargo_id = $(this).attr('id');
        nombre_cargo = $(this).attr('cargo');
        $('#ModalEliminar').modal('show');
        document.getElementById('cargo_editar').innerHTML = nombre_cargo;
    });

    /*PETICION DE ELIMINAR UN REGISTRO*/
    $('#btnElCargo').click(function(){
        $.ajax({
            url:"cargos/destroy/"+cargo_id,
            beforeSend:function(){
                $('#btnElCargo').text('Eliminando...');
            },
            success:function(data)
            {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'El Cargo ha sido Borrado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                })
                $('#btnElCargo').text('Eliminar');
                $('#ModalEliminar').modal('hide');
                $('.cargos').DataTable().ajax.reload();
            }
        })
    });
/*peticion de abrir vista editar y cargar los datos*/
     $(document).on('click', '.edit', function(event){
        event.preventDefault(); 
        var id = $(this).attr('id'); //alert(id);
        $('#hidden_id').val(id);
        $('#form_result').html('');
        $('#ModalAgregar').modal('show');
        $('.modal-title').text('Editar Cargo de Solicitudes');
        $('#GuardarCargo').val('Actualizar');
        $.ajax({
            url :"cargos/edit/"+id+"/",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"json",
            success:function(data)
            {
                console.log('success: '+data);
                $('#Nombre_Cargo').val(data.result.Nombre_Cargo);
                $('#hidden_id').val(id);
                $('#action').val('Edit');
            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
            }
        })
    });
/**/

});
</script>
@stop