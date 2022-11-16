
@extends('adminlte::page')

@section('title', 'Regiones')

@section('content_header')
    <button type="button" name="FormReg" id="FormReg" class="btn btn-success"> <i class="fas fa-fw fa-plus"></i>Nueva Region</button>
    <p></p>
    <h1 class="text-center">Lista de Regiones Registradas</h1>
@stop

@section('content')
<!--TABLA DE VISTA DE CONSULTA DE AREAS REGISTRADAS-->
<table class="table table-striped table-bordered regiones"> 
    <thead>
        <tr>
            <th>Id</th>
            <th>Nombre Region</th>
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
                    <h4 id="reg_editar" name="reg_editar"></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <button type="button" name="btnElReg" id="btnElReg" class="btn btn-danger"><i class="fas fa-fw fa-check"></i>Eliminar</button>
                </div>
            </form>  
        </div>
    </div>
</div>
<!--modal agregar regiones-->
<div class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="ModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="form_reg" name="form_reg" class="form-horizontal">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group" style="display:none;">
                        <label>Apartado de Seccion de Verificacion de Tipo de Proceso</label>
                        <input type="text" name="action" id="action" value="Add" />
                        <input type="text" name="hidden_id" id="hidden_id"  />
                    </div>
                    <div class="form-group">
                        <label>Nombre Region : </label>
                        <input type="text" name="Nom_Reg" id="Nom_Reg" class="form-control" />
                    </div>
                    <span id="form_result"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <input type="submit" class="btn btn-primary" name="btnGuardarReg" id="btnGuardarReg" value="Guardar" />
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
    var table = $('.regiones').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: "{{ route('regiones.index') }}",
            columns: [
                {data: 'id'},
                {data: 'Nom_Reg'},
                {data: 'created_at'},
                {data: 'updated_at'},
                {data: 'action_reg'}
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
        $('#FormReg').click(function(){
            $('#action').val('Add');//input para diferenciar con editar
            $('#form_result').html('');//el span donde aparece error
            $('#ModalAgregar').modal('show');
            $('.modal-title').text('Registrando Nueva Region');
        });
/* peticion de guardar o editar el registro */
        $('#form_reg').on('submit', function(event){
                event.preventDefault(); 
                var action_url = '';
                if($('#action').val() == 'Add'){
                    $('#btnGuardarReg').val('Guardando');//cambia valor de boton guardar
                    $('#action').val('');//input para diferenciar de editar
                    action_url = "{{ route('regiones.store') }}";
                }
        
                if($('#action').val() == 'Edit'){
                    $('#action').val('');//input para diferencia de guardar
                    $('#btnGuardarReg').val('Actualizando');//cambia el texto de boton editar
                    action_url = "{{ route('regiones.update') }}";
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
                                    title: 'La Region ha sido ' +tipo +' correctamente',
                                    showConfirmButton: false,
                                    timer: 1500
                            })
                            $('#btnGuardarReg').val('Guardar');
                            $('#form_reg')[0].reset();
                            $('#ModalAgregar').modal('hide');
                            $('.regiones').DataTable().ajax.reload();
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
    var reg_id;
    var nombre_org;
    $(document).on('click', '.el_reg', function(){
        reg_id = $(this).attr('id');
        nombre_region = $(this).attr('region');
        $('#ModalEliminar').modal('show');
        $('.modal-title').text('¿Desea Eliminar esta Region?');
        document.getElementById('reg_editar').innerHTML = nombre_region;
    });
/*PETICION DE ELIMINAR UN REGISTRO*/
    $('#btnElReg').click(function(){
        $.ajax({
            url:"regiones/destroy/"+reg_id,
            beforeSend:function(){
                $('#btnElReg').text('Eliminando');
            },
            success:function(data)
            {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'La Region ha sido Borrado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                })
                $('#btnElReg').text('Eliminar');
                $('#ModalEliminar').modal('hide');
                $('.regiones').DataTable().ajax.reload();
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
        $('.modal-title').text('¡¡Estas Editando el Nombre de la Region¡¡');
        $('#btnGuardarReg').val('Actualizar');
        $.ajax({
            url :"regiones/edit/"+id+"/",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"json",
            success:function(data)
            {
                console.log('success: '+data);
                $('#Nom_Reg').val(data.result.Nom_Reg);
                $('#hidden_id').val(id);//colocar valor del id oculto
                $('#action').val('Edit');//colocar que sera un edit
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
