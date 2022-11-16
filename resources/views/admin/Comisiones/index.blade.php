@extends('adminlte::page')

@section('title', 'Comisiones')

@section('content_header')
    <div class="form-row">
        <div class="form-group col-md-2">
            <button type="button" name="OpFormCom" id="OpFormCom" class="btn btn-info"> <i class="fas fa-fw fa-plus"></i>Nueva Comision</button>
        </div>
        <div class="form-group col-md-2">
            <button type="button" name="Excel" id="Excel" class="btn btn-success"><i class="fas fa-file-excel"></i> Excel</button>
        </div>
        <div class="form-group col-md-8">
            <h1 class="text-center">Lista de Comisiones</h1>
        </div>
    </div>
@stop

@section('content')
<!--============ESTRUCTURA DE LA TABLA DE COMISIONES===========-->
<table class="table table-striped table-bordered comisiones"> 
    <thead>
        <tr>
            <th>id</th>
            <th>Comisionado</th>
            <th>Fecha Comision</th>
            <th>Destino Comision</th>
            <!--<th>Actualizado</th>-->
            <th width="150px">Opciones</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<!--================INICIO DE LA ESTRUCTURA DE NUEVAS COMISIONES====================-->
<div id="ModalAgregar" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="form_Com" name="form_Com" class="form-horizontal" method="post">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fas fa-fw fa-times-circle"></i>
                    </button>
                </div>
                <div class="modal-body" >

                    <div class="form-row" style="display:none;">
                        <div class="form-group col-md-12">
                            <label>Apartado de Seccion de Verificacion de Tipo de Proceso</label>
                            <input type="text" name="action" id="action" value="Add" />
                            <input type="text" name="hidden_id" id="hidden_id"  />
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label>Fecha Emision:</label>
                            <input class="form-control" type="date" name="Fecha_Emision" id="Fecha_Emision"/>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Localidad Destino</label>
                            <input class="form-control" type="text" name="Loc_Destino" id="Loc_Destino"/>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Fechas de Comision</label>
                            <input class="form-control" type="text" name="Fecha_Comision" id="Fecha_Comision"/>
                        </div>
                        <div class="form-group col-md-1">
                            <label>Unidad:</label>
                            <select class="form-control" id="Placas" name="Placas">
                                <option value="DA1189A">FORD</option>
                                <option value="DA1198A">CHEVROLET</option>
                                <option value="---------------">PARTICULAR</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Estatus:</label>
                            <select class="form-control" id="Estatus" name="Estatus">
                                <option value="1">VIATICABLE</option>
                                <option value="0">NO VIATICABLE</option>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                            <label>NUM. DIAS</label>
                            <input class="form-control" type="text" name="Dias" id="Dias"/>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Actividad:</label>
                            <textarea class="form-control" name="Actividad" id="Actividad" maxlength="480" minlength="100"></textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-10">
                            <label>Comisionados:</label>
                            <select class="form-control" id="Id_Comisionado" name="Id_Comisionado[]" multiple="multiple">
                            @foreach($Personas as $Comisionado)
                                <option value="{{$Comisionado->id}}">{{$Comisionado->Nombre}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Comentario</label>
                            <input class="form-control" type="text" name="Comentario" id="Comentario"/>
                        </div>
                    </div>

                    <span id="form_result"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <input type="submit" class="btn btn-primary" name="btnGuardarCom" id="btnGuardarCom" value="Guardar" />
                </div>
            </form>  
        </div>
    </div>
</div>
<!--==============inicio de formulario de eliminar una comision===============-->
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
                    <h4 id="Com_Eli" name="Com_Eli"></h4>
                    <h4 id="Com_Fecha" name="Com_Fecha"></h4>
                    <h4 id="Com_Destino" name="Com_Destino"></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <button type="button" name="btnElCom" id="btnElCom" class="btn btn-danger"><i class="fas fa-fw fa-check"></i>Eliminar</button>
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
<script src="../vendor/datatables-plugins/select/js/dataTables.select.min.js"></script>

<!--<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>-->
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script>
$(document).ready(function(){
    //==================================================
    /*PETICION DE word UN REGISTRO*/
    $(document).on('click', '.ExpExis', function(){
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: '!!YA EXISTE UN ARCHIVO, DIRIGETE A LA CARPETA DE COMISIONES!!',
            showConfirmButton: false,
            timer: 2000
        })
    });
    //==================================================
    /*PETICION DE word UN REGISTRO*/
    $(document).on('click', '.GenExp', function(){
        var id_exp = $(this).attr('id');
        $.ajax({
            url:"comisiones/word/"+id_exp,
            beforeSend:function(){
                $('.GenExp').prop('disabled', true);
            },
            success:function(data)
            {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Archivos de Comision Creado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                })
                $('.comisiones').DataTable().ajax.reload();
            }
        })
    });
//==================================================
    /*PETICION DE REPORTE EXCEL UN REGISTRO*/
    $('#Excel').click(function(){
        $.ajax({
            url:"comisiones/excel",
            beforeSend:function(){
                $('#Excel').prop('disabled', true);
            },
            success:function(data)
            {
                $('#Excel').prop('disabled', false);
                Swal.fire({
                    position: 'Center',
                    icon: 'info',
                    title: 'Reporte Generado !!VE A LA CARPETA DE RESPALDO!!',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        })
    });
//=============================================
    //LISTA DE LAS SOLICITUDES VALIDADOS
     var table = $('.comisiones').DataTable({
            processing: true,
            responsive: true,
            autoWidth: false,
            serverSide: true,
            stateSave:true,
            ajax: "{{ route('comisiones.index') }}",
            columns: [
                {data: 'id'},
                {data: 'personas.Nombre'},
                {data: 'Fecha_Comision'},
                {data: 'Loc_Destino'},
                {data: 'action_Com'}
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
        $('#OpFormCom').click(function(){
            $('#action').val('Add');//input para diferenciar con editar
            $('#form_result').html('');//el span donde aparece error
            $('#ModalAgregar').modal('show');
            $('.modal-title').text('Registrando Nueva Comision');
            $('#form_Com')[0].reset();
            $('#Id_Comisionado').select2({
                dropdownParent: $('#ModalAgregar'),
                width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input
            });
        });
    
    //==================================================
    /* peticion de guardar o editar el registro */
        $('#form_Com').on('submit', function(event){
                event.preventDefault(); 
                var action_url = '';
                if($('#action').val() == 'Add'){
                    $('#btnGuardarCom').val('Guardando');//cambia valor de boton guardar
                    $('#action').val('');//input para diferenciar de editar
                    action_url = "{{ route('comisiones.store') }}";
                }
        
                if($('#action').val() == 'Edit'){
                    $('#action').val('');//input para diferencia de guardar
                    $('#btnGuardarCom').val('Actualizando');//cambia el texto de boton editar
                    action_url = "{{ route('comisiones.update') }}";
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
                                title: 'La Comision ha sido ' +tipo +' correctamente',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            $('#btnGuardarCom').val('Guardar');
                            $('#form_Com')[0].reset();
                            $('#ModalAgregar').modal('hide');
                            $('.comisiones').DataTable().ajax.reload();
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
    var com_id;
    var nombre_com;
    var fecha_com;
    var destino_com;
    $(document).on('click', '.elicom', function(){
        com_id = $(this).attr('id');
        nombre_com = $(this).attr('nombre');
        fecha_com=$(this).attr('fecha');
        destino_com=$(this).attr('destino');
        $('#ModalEliminar').modal('show');
        $('.modal-title').text('¿Desea Eliminar esta Comision?');
        document.getElementById('Com_Eli').innerHTML = nombre_com;
        document.getElementById('Com_Fecha').innerHTML=fecha_com;
        document.getElementById('Com_Destino').innerHTML=destino_com;
    });
    //==================================================
    /*PETICION DE ELIMINAR UN REGISTRO*/
    $('#btnElCom').click(function(){
        $.ajax({
            url:"comisiones/destroy/"+com_id,
            beforeSend:function(){
                $('#btnElCom').text('Eliminando');
            },
            success:function(data)
            {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'La Comision ha sido Borrado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                })
                $('#btnElCom').text('Eliminar');
                $('#ModalEliminar').modal('hide');
                $('.comisiones').DataTable().ajax.reload();
            }
        })
    });
    //=============================================
    $(document).on('click', '.edit', function(event){
        event.preventDefault(); 
        var com_id = $(this).attr('id'); //alert(id);
        $('#Id_Comisionado').select2({
                dropdownParent: $('#ModalAgregar'),
                width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input
            });
        $('#hidden_id').val(com_id);
        $('#form_result').html('');//donde aparece el error de vacio
        $('#ModalAgregar').modal('show');
        $('.modal-title').text('¡¡Estas Editando una Comision');
        $('#btnGuardarCom').val('Actualizar');
        $.ajax({
            url :"comisiones/edit/"+com_id+"/",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"json",
            success:function(data)
            {
                console.log('success: '+data);
                $('#hidden_id').val(com_id);//colocar valor del id oculto
                $('#action').val('Edit');//colocar que sera un edit
                $('#Fecha_Emision').val(data.result.Fecha_Emision);
                $('#Id_Comisionado').val(data.result.Id_Comisionado).change();
                $('#Loc_Destino').val(data.result.Loc_Destino);
                $('#Fecha_Comision').val(data.result.Fecha_Comision);
                $('#Actividad').val(data.result.Actividad);
                $('#Placas').val(data.result.Placas).change();
                $('#Comentario').val(data.result.Comentario);
                $('#Estatus').val(data.result.Estatus);
                $('#Dias').val(data.result.Dias);
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
