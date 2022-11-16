@extends('adminlte::page')

@section('title', 'Areas')
@section('content_header')
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#formModal">
        Nueva Area del H-Ayuntamiento
    </button>
<p></p>
    <h1 class="text-center">Lista de areas del Ayuntamiento</h1>
@stop
@section('content')
<!--TABLA DE VISTA DE CONSULTA DE AREAS REGISTRADAS-->
<table class="table table-striped table-bordered areas"> 
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre Area</th>
            <th>Coordinador</th>
            <th>creado el</th>
            <th>actualizado el</th>
            <th width="180px">Action</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!--modal eliminar un area empieza-->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="ModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="delete_form" class="form-horizontal">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel">Eliminando Registro de Area</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 style="margin:0;">Esta Seguro de Eliminar este Dato</h4>
                    <p></p>
                    <h4 id="nom_editar" name="nom_editar"></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">Eliminar</button>
                </div>
            </form>  
        </div>
    </div>
</div>

<!--modal agregar inicio-->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="ModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="sample_form" name="sample_form" class="form-horizontal">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregando Nueva Area</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group ocultar">
                        <label>Apartado de Seccion de Verificacion de Tipo de Proceso</label>
                        <input type="text" name="action" id="action" value="Add" readonly />
                        <input type="text" name="hidden_id" id="hidden_id" readonly />
                    </div>
                    <div class="form-group">
                        <label>Nombre del Area : </label>
                        <input type="text" name="Area" id="Area" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label>Responsable : </label>
                        <input type="text" name="Responsable" id="Responsable" class="form-control" />
                    </div>
                    <span id="form_result"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" name="action_button" id="action_button" value="Add" />
                </div>
            </form>  
        </div>
    </div>
</div>



@stop
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('js')
<script src="../vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="../vendor/datatables-plugins/responsive/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function(){
        var table = $('.areas').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: "{{ route('areas.index') }}",
            columns: [
                {data: 'id'},
                {data: 'Area'},
                {data: 'Responsable'},
                {data: 'created_at'},
                {data: 'updated_at'},
                {data: 'action'}
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
        $('#create_record').click(function(){
            $('#action').val('Add');
            $('#form_result').html('');
            $('#formModal').modal('show');
        });

        /* peticion de guardar o editar el registro */
        $('#sample_form').on('submit', function(event){
                event.preventDefault(); 
                var action_url = '';
                if($('#action').val() == 'Add'){
                    $('#action').val('');
                    action_url = "{{ route('areas.store') }}";
                }
        
                if($('#action').val() == 'Edit'){
                    $('#action').val('');
                    $('.modal-title').text('Nueva Area del Ayuntamiento');
                    $('#action_button').val('Add');
                    action_url = "{{ route('areas.update') }}";
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
                                    title: 'El area ha sido guardado correctamente',
                                    showConfirmButton: false,
                                    timer: 1500
                            })
                            
                            $('#sample_form')[0].reset();
                            $('#formModal').modal('hide');
                            $('.areas').DataTable().ajax.reload();
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
    var area_id;
    $(document).on('click', '.delete', function(){
        $('.modal-title').text('Eliminar Area del Ayuntamiento');
        area_id = $(this).attr('id');
        nombrearea = $(this).attr('area');
        $('#confirmModal').modal('show');

        document.getElementById('nom_editar').innerHTML = nombrearea;
    });

    /*PETICION DE ELIMINAR UN REGISTRO*/
    $('#ok_button').click(function(){
        $.ajax({
            url:"areas/destroy/"+area_id,
            beforeSend:function(){
                $('#ok_button').text('Eliminando...');
            },
            success:function(data)
            {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'El area ha sido Borrado correctamente',
                    showConfirmButton: false,
                    timer: 1500
                })
                $('#ok_button').text('Eliminar');
                $('#confirmModal').modal('hide');
                $('.areas').DataTable().ajax.reload();
            }
        })
    });

    /*peticion de abrir vista y cargar los datos*/
     $(document).on('click', '.edit', function(event){
        event.preventDefault(); 
        var id = $(this).attr('id'); //alert(id);
        $('#hidden_id').val(id);
        $('#form_result').html('');
        $('#formModal').modal('show');
        $.ajax({
            url :"areas/edit/"+id+"/",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"json",
            success:function(data)
            {
                console.log('success: '+data);
                $('#Area').val(data.result.Area);
                $('#Responsable').val(data.result.Responsable);
                $('#hidden_id').val(id);
                $('.modal-title').text('Editar Area del Ayuntamiento');
                $('#action_button').val('Update');
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
