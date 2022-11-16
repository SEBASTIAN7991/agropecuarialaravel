@extends('adminlte::page')

@section('title', 'Tipos Proyectos')

@section('content_header')
    <button type="button" name="FormPro" id="FormPro" class="btn btn-success"> <i class="fas fa-fw fa-plus"></i>Nuevo Tipo Proyecto</button>
    <p></p>
    <h1 class="text-center">Lista de los Tipos de Proyectos de la Coordinacion</h1>
@stop

@section('content')
<!--TABLA DE VISTA DE CONSULTA DE AREAS REGISTRADAS-->
<table class="table table-striped table-bordered proyectos"> 
    <thead>
        <tr>
            <th>Id</th>
            <th>Tipo de Proyecto</th>
            <th>Monto</th>
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
                    <h5 class="modal-title" id="ModalLabel">¿Desea Eliminar este Tipo de Proyecto?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 id="pro_editar" name="pro_editar"></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <button type="button" name="btnElPro" id="btnElPro" class="btn btn-danger"><i class="fas fa-fw fa-check"></i>Eliminar</button>
                </div>
            </form>  
        </div>
    </div>
</div>
<!--modal agregar tipos de proyectos-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="ModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="form_pro" name="form_pro" class="form-horizontal">
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
                        <label>Nombre del Proyecto : </label>
                        <input type="text" name="Nom_Pro" id="Nom_Pro" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Costo del proyecto : </label>
                        <input type="text" name="Monto_Pro" id="Monto_Pro" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Estado:</label>
                        <select class="form-control" id="Estatus" name="Estatus">
                            <option value="3">Selecciona</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                    <span id="form_result"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <input type="submit" class="btn btn-primary" name="btnGuardarPro" id="btnGuardarPro" value="Guardar" />
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
    var table = $('.proyectos').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: "{{ route('proyectos.index') }}",
            columns: [
                {data: 'id'},
                {data: 'Nom_Pro'},
                {data: 'Monto_Pro'},
                {data: 'created_at'},
                {data: 'updated_at'},
                {data: 'action_pro'}
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
        $('#FormPro').click(function(){
            $('#action').val('Add');//input para diferenciar con editar
            $('#form_result').html('');//el span donde aparece error
            $('#ModalAgregar').modal('show');
            $('.modal-title').text('Registrando Nuevo Tipo de Proyecto');
        });
/* peticion de guardar o editar el registro */
        $('#form_pro').on('submit', function(event){
                event.preventDefault(); 
                var action_url = '';
                if($('#action').val() == 'Add'){
                    $('#btnGuardarPro').val('Guardando');//cambia valor de boton guardar
                    $('#action').val('');//input para diferenciar de editar
                    action_url = "{{ route('proyectos.store') }}";
                }
        
                if($('#action').val() == 'Edit'){
                    $('#action').val('');//input para diferencia de guardar
                    $('#btnGuardarPro').val('Actualizando');//cambia el texto de boton editar
                    action_url = "{{ route('proyectos.update') }}";
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
                                    title: 'El Tipo de Proyecto ha sido ' +tipo +' correctamente',
                                    showConfirmButton: false,
                                    timer: 1500
                            })
                            $('#btnGuardarPro').val('Guardar');
                            $('#form_pro')[0].reset();
                            $('#ModalAgregar').modal('hide');
                            $('.proyectos').DataTable().ajax.reload();
                        }
                        $('#form_result').html(html);//coloca en caso de vacio input
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                });
        });
/*peticion de abrir vista editar y cargar los datos*/
     $(document).on('click', '.edit', function(event){
        event.preventDefault(); 
        var id = $(this).attr('id'); //alert(id);
        $('#hidden_id').val(id);//se coloca el id en el input oculto
        $('#form_result').html('');//donde aparece el error de vacio
        $('#ModalAgregar').modal('show');
        $('.modal-title').text('¡¡Estas Editando un Tipo de Proyecto¡¡');
        $('#btnGuardarPro').val('Actualizar');
        $.ajax({
            url :"proyectos/edit/"+id+"/",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"json",
            success:function(data)
            {
                console.log('success: '+data);
                $('#Nom_Pro').val(data.result.Nom_Pro);
                $('#Monto_Pro').val(data.result.Monto_Pro);
                $('#Estatus').val(data.result.Estatus);
                $('#hidden_id').val(id);//colocar valor del id oculto
                $('#action').val('Edit');//colocar que sera un edit
            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
            }
        })
    });

/* peticion de abrir modal de eliminar un registro */
    var pro_id;
    var nombre_pro;
    $(document).on('click', '.el_pro', function(){
        pro_id = $(this).attr('id');
        nombre_pro = $(this).attr('proyecto');
        $('#ModalEliminar').modal('show');
        $('.modal-title').text('¿Desea Eliminar este Tipo de Proyecto?');
        document.getElementById('pro_editar').innerHTML = nombre_pro;
    });
/*PETICION DE ELIMINAR UN REGISTRO*/
    $('#btnElPro').click(function(){
        $.ajax({
            url:"proyectos/destroy/"+pro_id,
            beforeSend:function(){
                $('#btnElPro').text('Eliminando');
            },
            success:function(data)
            {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'El Tipo de Proyecto ha sido Borrado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                })
                $('#btnElPro').text('Eliminar');
                $('#ModalEliminar').modal('hide');
                $('.proyectos').DataTable().ajax.reload();
            }
        })
    });


});
</script>
@stop