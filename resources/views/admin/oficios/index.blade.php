@extends('adminlte::page')

@section('title', 'Oficios')

@section('content_header')
    <div class="form-row">
        <div class="form-group col-md-2">
            <button type="button" name="Abrir_Form" id="Abrir_Form" class="btn btn-info"> <i class="fas fa-fw fa-plus"></i>Nuevo Oficio</button>
        </div>
        <div class="form-group col-md-2">
          @can('admin.users')
                <a class="btn btn-info">Exportar Oficios</a>
           @endcan  
        </div>
        <div class="form-group col-md-8">
            <h1 class="text-center">Lista de Oficios Registrados</h1>
        </div>
    </div>
@stop

@section('content')
<table class="table table-striped table-bordered oficios"> 
    <thead>
        <tr>
            <th>id</th>
            <th>Estatus</th>
            <th>Oficio</th>
            <th>Numero de Oficio</th>
            <th>Fecha de Oficio</th>
            <th>Area</th>
            <th>Recibido Por</th>
            <th>Enviado Por</th>
            <th>Creado</th>
            <th>Actualizado</th>
            <!--<th>Actualizado</th>-->
            <th width="150px">Opciones</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<!--==========inicia modal de guardar los datos=========-->
<div id="ModalAgregar" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="Formulario_Ofi" name="Formulario_Ofi" class="form-horizontal" method="post" enctype="multipart/form-data" >
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fas fa-fw fa-times-circle"></i>
                    </button>
                </div>
                <div class="modal-body">

                <div class="form-row" >
                    <div class="form-group col-md-12" style="display:none;">
                        <label>Apartado de Seccion de Verificacion de Tipo de Proceso</label>
                        <input class="form-control" type="text" name="action" id="action" value="Add" />
                        <input class="form-control" type="text" name="hidden_id" id="hidden_id"/>
                    </div>
                </div> 

                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Estatus:</label>
                        <select class="form-control" id="Estatus" name="Estatus" required>
                            <option value="">Selecciona</option>
                            <option value="Enviado">Enviado</option>
                            <option value="Recibido">Recibido</option>
                            <option value="Cancelado">Cancelado</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3" id="num1" style="display:none;">
                        <label>Numero:</label>
                        <input type="text" class="form-control" id="Oficio" name="Oficio" placeholder="Escribe Solo Numero">
                    </div>
                    <div class="form-group col-md-3" id="num2" style="display:none">
                        <label>Numero de Oficio:</label>
                        <input type="text" class="form-control" placeholder="Escribe Num. Oficio Completo" id="Oficio2" name="Oficio2" >
                    </div>
                    <div class="form-group col-md-2">
                        <label>Fecha Oficio:</label>
                        <input class="form-control" type="date" name="Fecha" id="Fecha"/>
                    </div>
                    <div class="form-group col-md-4">
                        <label id="destinoarea">Areas:</label><br>
                        <select class="form-control" id="Id_Area" name="Id_Area" required>
                            <option value="" selected>Selecciona un Area:</option>
                            @foreach($mis_areas as $area)
                                <option value="{{$area->id}}">{{$area->Area}}</option>
                            @endforeach  
                        </select>
                    </div>  
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Descripcion:</label>
                        <textarea class="form-control" name="Descripcion" id="Descripcion"></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label name="labelnombre" id="labelnombre">Archivo:</label>
                        <input type="text" class="form-control" id="Archivo_Viejo" value="" name="Archivo_Viejo" style="display:none;">
                        <input class="form-control" type="file" id="Ubicacion_Archivo" name="Ubicacion_Archivo" accept=".pdf">
                    </div>
                    <div class="form-group col-md-4">
                        <label id="recibidode">¿Quien Envia el Oficio?</label>
                        <input type="text" class="form-control" id="Recibido_Por" name="Recibido_Por">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Enviado Por:</label><br>
                        <select class="form-control" id="Enviado_Por" name="Enviado_Por">
                            <option value="0" selected>Seleccione:</option>
                             @foreach($personas as $persona)
                                <option value="{{$persona->id}}">{{$persona->Nombre}}</option>
                            @endforeach  
                        </select>
                    </div> 
                </div>


                <span id="form_result"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <input type="submit" class="btn btn-primary" name="btnGuardarOfi" id="btnGuardarOfi" value="Guardar" />
                </div>
            </form>  
        </div>
    </div>
</div>
<!--modal eliminar un registro de datos empieza-->
<div class="modal fade" id="ModalEliminar" tabindex="-1" aria-labelledby="ModalLabel" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="Formulario_eliminar" class="form-horizontal">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 id="id_ofi" name="id_ofi"></h4>
                    <h4 id="descripcion" name="descrpcion"></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <button type="button" name="btnElOfi" id="btnElOfi" class="btn btn-primary"><i class="fas fa-fw fa-check"></i>Eliminar</button>
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
                    <h3 class="modal-title" id="exampleModalLabel">Visualizacion de Oficio</h3>
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
    $('#Enviado_Por').prop('disabled', true);
    $('#Recibido_Por').prop('disabled', true);
//================================================
    const table = $('.oficios').DataTable({
            processing: true,
            serverSide:true,
            responsive: true,
            autoWidth: false,
            ajax: "{{ route('oficios.datatable') }}",
            columns: [
                    {data: 'id', name:'id'},
                    {data: 'Estatus', name:'Estatus'},
                    {data: 'Oficio', name:'Oficio'},
                    {data: 'Num_Oficio', name:'Num_Oficio'},
                    {data: 'Fecha', name:'Fecha'},
                    {data: 'areas.Area', name:'areas.Area'},
                    {data: 'Recibido_Por', name:'Recibido_Por'},
                    {data: 'personas.Nombre', name:'personas.Nombre'},
                    {data: 'created_at',name:'created_at'},
                    {data: 'updated_at', name:'updated_at'},
                    {data: 'action_Ofi',name:'action_Ofi'}
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
$.fn.dataTable.ext.errMode = 'throw';
//=====================================
/* abrir modal de nuevo registro*/
        $('#Abrir_Form').click(function(){
            $('#action').val('Add');//input para diferenciar con editar
            $('#form_result').html('');//el span donde aparece error
            $('#ModalAgregar').modal('show');
            $('.modal-title').text('Registrando Nuevo Oficio');
            $('#Formulario_Ofi')[0].reset();
            $('#Oficio').val("");
            $('#Oficio2').val("");
            document.getElementById('labelnombre').innerHTML
                = 'selecciona un archivo';
            $('#Id_Area').select2({
                dropdownParent: $('#ModalAgregar'),
                width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input
            });
            $('#Enviado_Por').select2({
                dropdownParent: $('#ModalAgregar'),
                width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input
            });
        });
//=====================================
$("#Estatus").change(function(){
    var cod = document.getElementById("Estatus");
    var selected = cod.options[cod.selectedIndex].text;
    if(selected=='Enviado'){
        $("#num1").show();
        $("#num2").hide();
        document.getElementById('destinoarea').innerHTML
                = 'Enviado al Area de:';
        $('#Recibido_Por').prop('disabled', true);
        $('#Recibido_Por').val("");
        $('#Enviado_Por').prop('disabled', false);
    }
    if(selected=='Cancelado'){
        $("#num1").show();
        $("#num2").hide();
        document.getElementById('destinoarea').innerHTML
                = 'Cancelado al Enviar al Area de:';
        $('#Enviado_Por').prop('disabled', false);
        $('#Recibido_Por').val("");
        $('#Recibido_Por').prop('disabled', true);
    }
    if(selected=='Recibido'){
        $("#num2").show();
        $("#num1").hide();
        document.getElementById('destinoarea').innerHTML
                = 'Recibido del Area de:';
        $('#Enviado_Por').prop('disabled', true);
        $('#Oficio2').val("");
        $('#Enviado_Por').val("0").change();
        $('#Recibido_Por').prop('disabled', false);
    }
    if(selected=='Selecciona'){
        $("#num1").hide();
        $("#num2").hide();
        document.getElementById('destinoarea').innerHTML
                = 'Area de:';
        $('#Recibido_Por').val("");
        $('#Enviado_Por').val("0").change();
        $('#Enviado_Por').prop('disabled', true);
        $('#Recibido_Por').prop('disabled', true);
    }

});
//=====================================
/*funcion de ejecutar registro y actualizacion de registro de oficios*/
        $('#Formulario_Ofi').on('submit', function(event){
            event.preventDefault(); 
            var action_url = '';
                if($('#action').val() == 'Add'){
                    $('#btnGuardarOfi').prop('disabled', true);
                    $('#btnGuardarOfi').val('Guardando');//cambia valor de boton guardar
                    $('#action').val('');//input para diferenciar de editar
                    action_url = "{{ route('oficios.store') }}";
                }
        
                if($('#action').val() == 'Edit'){
                    $('#action').val('');//input para diferencia de guardar
                    $('#btnGuardarOfi').prop('disabled', true);
                    $('#btnGuardarOfi').val('Actualizando');//cambia el texto de boton editar
                    action_url = "{{ route('oficios.update') }}";
                }

            var file_data = $('#Formulario_Ofi').prop('Ubicacion_Archivo')[0];
            var form = $("#Formulario_Ofi")[0];
            var form_data = new FormData(form);
                form_data.append('Estatus', $('#Estatus').val());
                //============================================
                var cod = document.getElementById("Estatus");
                var selected = cod.options[cod.selectedIndex].text;
                if(selected=='Enviado' || selected=='Cancelado'){
                    form_data.append('Num_Oficio', $('#Oficio').val());
                    form_data.append('Oficio', $('#Oficio').val());
                }
                if(selected=='Recibido'){
                    form_data.append('Num_Oficio', $('#Oficio2').val());
                    form_data.append('Oficio', '');   
                }
                //============================================
                form_data.append('Fecha', $('#Fecha').val());
                form_data.append('Descripcion', $('#Descripcion').val());
                form_data.append('Id_Area', $('#Id_Area').val());
                form_data.append('Ubicacion_Archivo', file_data);
                form_data.append('Recibido_Por', $('#Recibido_Por').val());
                form_data.append('Enviado_Por', $('#Enviado_Por').val());
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
                                    title: 'El Oficio ha sido ' +tipo +' correctamente',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                                $('#btnGuardarOfi').val('Guardar');
                                $('#btnGuardarOfi').prop('disabled', false);
                                $('#Formulario_Ofi')[0].reset();
                                $("#Estatus").change();
                                $("#Id_Area").change();
                                $("#Recibido_Por").change();
                                $("#Enviado_Por").change();
                                $('#ModalAgregar').modal('hide');
                                $('.oficios').DataTable().ajax.reload();
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
                            $('#btnGuardarOfi').val('Guardar');
                            $('#btnGuardarOfi').prop('disabled', false);
                        }
                })
            });
//========================================
/* peticion de abrir modal de eliminar un registro */
    var id_ofi;
    var Des_Ofi;
    $(document).on('click', '.El_Ofi', function(){
        id_ofi = $(this).attr('id');
        Des_Ofi = $(this).attr('descripcion');
        $('#ModalEliminar').modal('show');
        $('.modal-title').text('¿Desea Eliminar esta Oficio?');
        document.getElementById('id_ofi').innerHTML = "Identificador: "+id_ofi;
        document.getElementById('descripcion').innerHTML = "Descripcion: "+Des_Ofi;
    });
//==========================================
/*PETICION DE ELIMINAR UN REGISTRO*/
    $('#btnElOfi').click(function(){
        $.ajax({
            url:"oficios/destroy/"+id_ofi,
            beforeSend:function(){
                $('#btnElOfi').text('Eliminando');
                $('#btnElOfi').prop('disabled', true);
            },
            success:function(data)
            {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Este Oficio ha sido Borrado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                })
                $('#btnElOfi').text('Eliminar');
                $('#btnElOfi').prop('disabled', false);
                $('#ModalEliminar').modal('hide');
                $('.oficios').DataTable().ajax.reload();
            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
                Swal.fire({
                    icon: 'error',
                     title: 'algo salio mal...',
                     text: 'Actualiza la Pagina o contacta al administrador!'
                })               
                $('#btnElOfi').text('Eliminar');
                $('#btnElOfi').prop('disabled', false);
            }
        })
    });
//========================================
/*modal de abrir vista y cargar datos a editar*/
$(document).on('click', '.edit', function(event){
        event.preventDefault(); 
        var id = $(this).attr('id'); //alert(id);
        $('#hidden_id').val(id);
        $('#action').val('Edit');//colocar que sera un edit
        $('#form_result').html('');//donde aparece el error de vacio
        $('#ModalAgregar').modal('show');
        $('.modal-title').text('¡¡Estas Editando los datos de un Oficio');
        $('#btnGuardarOfi').val('Actualizar');
        $('#Id_Area').select2({
            dropdownParent: $('#ModalAgregar'),
            width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input
        });
        $('#Enviado_Por').select2({
            dropdownParent: $('#ModalAgregar'),
            width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input
        });

        $.ajax({
            url :"oficios/edit/"+id+"/",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"json",
            success:function(data)
            {
                console.log('success: '+data);
                $('#Estatus').val(data.result.Estatus).change();
                //========================================
                if(data.result.Estatus=='Enviado' || data.result.Estatus=='Cancelado'){
                    $('#Oficio').val(data.result.Num_Oficio);
                    $("#num1").show();
                    $("#num2").hide();
                }
                if(data.result.Estatus=='Recibido'){
                    $('#Oficio2').val(data.result.Oficio);
                    $("#num1").hide();
                    $("#num2").show();
                }
                //========================================
                $('#Fecha').val(data.result.Fecha);
                $('#Descripcion').val(data.result.Descripcion);
                $('#Id_Area').val(data.result.Id_Area).change();
                document.getElementById('labelnombre').innerHTML
                = data.result.Ubicacion_Archivo;
                $('#Archivo_Viejo').val(data.result.Ubicacion_Archivo);
                $('#Recibido_Por').val(data.result.Recibido_Por);
                $('#Enviado_Por').val(data.result.Enviado_Por).change();
               /* $('#Id_Sol_Form').val(data.result.Id_Sol);
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
                $('#Curp_Viejo').val(data.result.Curp);*/           
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
//========================================
    /* abrir modal de visualizar pdf*/
        $(document).on('click', '.ver_pdf_ofi', function(){
            $('#ModalPdf').modal('show');
            var id = $(this).attr('id');
            $.ajax({
                url :"oficios/pdf/"+id+"/",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                dataType:"json",
                success:function(data)
                {
                    var documento=data.result.Ubicacion_Archivo;
                    var ruta='storage/Documentos_Oficios/'+id+'/'+documento;
                    document.getElementById('modalVerBody').innerHTML = "<div><iframe width='100%' height='400px' src={!! asset('"+ruta+"')!!}></iframe></div>";
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    Swal.fire({
                        icon: 'error',
                        title: 'algo salio mal al traer los datos...',
                        text: 'Actualiza la Pagina o contacta al administrador!'
                    })

                }
            }); 
        });
//========================================
    /* accion de cuando no hay pdf*/
        $(document).on('click', '.no_pdf', function(){
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