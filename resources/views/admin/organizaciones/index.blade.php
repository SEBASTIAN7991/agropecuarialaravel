
@extends('adminlte::page')

@section('title', 'Organizaciones')

@section('content_header')
    <button type="button" name="abrir_form" id="abrir_form" class="btn btn-success"> <i class="fas fa-fw fa-plus"></i>Nueva Organizacion</button>
    <p></p>
    <h1 class="text-center">Lista de Organizaciones Registradas</h1>
@stop

@section('content')
<!--TABLA DE VISTA DE CONSULTA DE AREAS REGISTRADAS-->
<table class="table table-striped table-bordered organizaciones"> 
    <thead>
        <tr>
            <th>Id</th>
            <th>Representante</th>
            <th>Organizacion</th>
            <th>Fecha Registro</th>
            <th>Fecha Actualizacion</th>
            <th width="180px">Opciones</th>
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
                    <h5 class="modal-title" id="ModalLabel">¿Desea Eliminar el Registro de Organizacion?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 id="org_editar" name="org_editar"></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <button type="button" name="btnElOrg" id="btnElOrg" class="btn btn-danger"><i class="fas fa-fw fa-check"></i>Eliminar</button>
                </div>
            </form>  
        </div>
    </div>
</div>
<!--modal agregar organizacion-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="ModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="form_org" name="form_org" class="form-horizontal">
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
                        <label>Representante : </label>
                        <input type="text" name="Representante" id="Representante" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Nombre Organizacion : </label>
                        <input type="text" name="Nom_Org" id="Nom_Org" class="form-control" />
                    </div>
                    <span id="form_result"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <input type="submit" class="btn btn-primary" name="btnGuardarOrg" id="btnGuardarOrg" value="Guardar" />
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
    var table = $('.organizaciones').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: "{{ route('organizaciones.index') }}",
            columns: [
                {data: 'id'},
                {data: 'Representante'},
                {data: 'Nom_Org'},
                {data: 'created_at'},
                {data: 'updated_at'},
                {data: 'action_org'}
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
            $('#action').val('Add');//input para diferenciar con editar
            $('#form_result').html('');//el span donde aparece error
            $('#ModalAgregar').modal('show');
            $('.modal-title').text('Registrando Nueva Organizacion');
        });
/* peticion de guardar o editar el registro */
        $('#form_org').on('submit', function(event){
                event.preventDefault(); 
                var action_url = '';
                if($('#action').val() == 'Add'){
                    $('#btnGuardarOrg').val('Guardando');//cambia valor de boton guardar
                    $('#action').val('');//input para diferenciar de editar
                    action_url = "{{ route('organizaciones.store') }}";
                }
        
                if($('#action').val() == 'Edit'){
                    $('#action').val('');//input para diferencia de guardar
                    $('#btnGuardarOrg').val('Actualizando');//cambia el texto de boton editar
                    action_url = "{{ route('organizaciones.update') }}";
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
                                    title: 'La Organizacion ha sido guardado correctamente',
                                    showConfirmButton: false,
                                    timer: 1500
                            })
                            $('#btnGuardarOrg').val('Guardar');
                            $('#form_org')[0].reset();
                            $('#ModalAgregar').modal('hide');
                            $('.organizaciones').DataTable().ajax.reload();
                        }
                        $('#form_result').html(html);//coloca en caso de vacio input
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                });
        });
/* peticion de abrir modal de eliminar un registro */
    var org_id;
    var nombre_org;
    $(document).on('click', '.el_org', function(){
        org_id = $(this).attr('id');
        nombre_cargo = $(this).attr('org');
        $('#ModalEliminar').modal('show');
        $('.modal-title').text('¿Desea Eliminar el Registro de Organizacion?');
        document.getElementById('org_editar').innerHTML = nombre_cargo;
    });
/*PETICION DE ELIMINAR UN REGISTRO*/
    $('#btnElOrg').click(function(){
        $.ajax({
            url:"organizaciones/destroy/"+org_id,
            beforeSend:function(){
                $('#btnElOrg').text('Eliminando');
            },
            success:function(data)
            {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'La Organizacion ha sido Borrado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                })
                $('#btnElOrg').text('Eliminar');
                $('#ModalEliminar').modal('hide');
                $('.organizaciones').DataTable().ajax.reload();
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
        $('.modal-title').text('¡¡Estas Editando una Organizacion¡¡');
        $('#btnGuardarOrg').val('Actualizar');
        $.ajax({
            url :"organizaciones/edit/"+id+"/",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"json",
            success:function(data)
            {
                console.log('success: '+data);
                $('#Representante').val(data.result.Representante);
                $('#Nom_Org').val(data.result.Nom_Org);
                $('#hidden_id').val(id);//colocar valor del id oculto
                $('#action').val('Edit');
            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
            }
        })
    });


});
</script>
@stop
