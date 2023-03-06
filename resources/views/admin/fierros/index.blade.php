@extends('adminlte::page')

@section('title', 'Fierro Marcador')

@section('content_header')
<div class="form-row">
   <div class="form-group col-md-2">
      <button type="button" name="abrir_form_Fierro" id="abrir_form_Fierro" class="btn btn-info"> <i class="fas fa-fw fa-plus"></i>Nueva Registro</button>
   </div>
   <div class="form-group col-md-2">

   </div>
   <div class="form-group col-md-8">
      <h1>Registros de Fierro Marcador, Coordinacion Agropecuaria</h1>
   </div>
</div>
@stop

@section('content')
<!--=======inicia tabla de fierro marcador=======-->
<table class="table table-striped table-bordered fierros"> 
    <thead>
        <tr>
            <th>Id</th>
            <th>Folio</th>
            <th>Fecha Tramite</th>
            <th>Localidad</th>
            <th>Region</th>
            <th>Nombre</th>
            <th>Paterno</th>
            <th>Materno</th>
            <th>Curp</th>
            <th>Tipo de Tramite</th>
            <th width="150px">Opciones</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<!--==========modal agregar Nuevo Registro========-->
<div id="ModalAgregar" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="Formulario_Fierro" name="Formulario_Fierro" class="form-horizontal" method="post">
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
                        <div class="form-group col-md-2">
                           <label>Fecha Visita:</label>
                           <input type="date" class="form-control" id="Fecha_Tramite" name="Fecha_Tramite">
                        </div>
                        <div class="form-group col-md-4">
                           <label>Elaborado Por:</label>
                           <select class="form-control" id="Elaborado_Por" name="Elaborado_Por" required>
                              <option value="" selected>Selecciona una Localidad:</option>
                              <option value="OVIDIO HERNANDEZ JIMENEZ">OVIDIO HERNANDEZ JIMENEZ</option>
                              <option value="OMAR ANTONIO NAVARRO ALCAZAR">OMAR ANTONIO NAVARRO ALCAZAR</option>
                              <option value="MANUELA GOMEZ SANCHEZ">MANUELA GOMEZ SANCHEZ</option>
                           </select>
                        </div>
                        <div class="form-group col-md-4">
                           <label>Localidad:</label><br>
                           <select class="form-control" id="Id_Loc" name="Id_Loc" required>
                              <option value="" selected>Selecciona una Localidad:</option>
                              @foreach($localidades as $localidad)
                              <option value="{{$localidad->id}}">{{$localidad->Nom_Loc}}</option>
                              @endforeach  
                           </select>
                        </div>
                        <div class="form-group col-md-2">
                           <label>Region:</label>
                           <input type="text" class="form-control" id="Id_Reg" name="Id_Reg"  style="display:none;">
                           <input type="text" class="form-control" id="Nom_Reg" name="Nom_Reg" readonly>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-2">
                           <label>Nombre</label>
                           <input type="text" name="Nombre" id="Nombre" class="form-control" pattern="[a-zA-ZÑÁÉÍÓÚáéíóú][a-zA-Zñáéíóú ]{1,}" required>
                        </div>
                        <div class="form-group col-md-2">
                           <label>Ap. Paterno:</label>
                           <input type="text" class="form-control" id="Paterno" name="Paterno" required>
                        </div>
                        <div class="form-group col-md-2">
                           <label>Ap. Materno:</label>
                           <input type="text" class="form-control" id="Materno" name="Materno" required>
                        </div>
                        <div class="form-group col-md-3">
                           <label>Curp:</label>
                           <input type="text" class="form-control" id="Curp" name="Curp" required="true" 
                              placeholder="Ingrese su CURP" 
                              pattern="([A-Z]{4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM](AS|BC|BS|CC|CL|CM|CS|CH|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[A-Z]{3}[0-9A-Z]\d)" required>
                        </div>
                        <div class="form-group col-md-2">
                           <label>Rfc:</label>
                           <input type="text" class="form-control" id="Rfc" name="Rfc" required>
                        </div>
                        <div class="form-group col-md-1">
                           <label>Edad:</label>
                           <input class="form-control" type="number" name="Edad" id="Edad"/>
                        </div>
                    </div>

                    <div class="form-row">
                       <div class="form-group col-md-2">
                           <label>Sexo:</label>
                           <select class="form-control" id="Sexo" name="Sexo" required>
                              <option value="" selected>Seleccione</option>
                              <option value="Hombre">Hombre</option>
                              <option value="Mujer">Mujer</option>
                           </select>
                        </div>
                        <div class="form-group col-md-3">
                           <label>Tramite:</label>
                           <select class="form-control" id="Tipo_Tramite" name="Tipo_Tramite" required>
                              <option value="0" selected>Seleccione</option>
                              <option value="Actualizacion">Actualizacion</option>
                              <option value="Nuevo Registro">Nuevo Registro</option>
                           </select>
                        </div>
                        <div class="form-group col-md-2">
                           <label>Clave Upp:</label>
                           <input type="text" class="form-control" id="Upp" name="Upp" required>
                        </div>
                        <div class="form-group col-md-2">
                           <label>Folio Pago:</label>
                           <input type="text" class="form-control" id="Folio_Pago" name="Folio_Pago" required>
                        </div>
                        <div class="form-group col-md-1">
                           <label>Total:</label>
                           <input class="form-control" type="number" name="Total" id="Total"/>
                        </div>
                        <div class="form-group col-md-2">
                           <label>Estatus:</label>
                           <select class="form-control" id="Estatus" name="Estatus" required>
                              <option value="0" selected>Seleccione</option>
                              <option value="Cancelado">Cancelado</option>
                              <option value="Duplicado">Duplicado</option>
                           </select>
                        </div>
                    </div>
                    
                    <span id="form_result"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <input type="submit" class="btn btn-primary" name="btnGuardarFierro" id="btnGuardarFierro" value="Guardar" />
                </div>
            </form>  
        </div>
    </div>
</div>
<!--=============modal eliminar un Registro=============-->
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
                    <h4 id="Id_Fierro" name="Id_Fierro"></h4>
                    <h4 id="Nombre_Ben" name="Nombre_Ben"></h4>
                    <h4 id="Curp_Ben" name="Curp_Ben"></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <button type="button" name="btnElFierro" id="btnElFierro" class="btn btn-primary"><i class="fas fa-fw fa-check"></i>Eliminar</button>
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
    var table = $('.fierros').DataTable({
            processing: true,
            serverSide:true,
            responsive: true,
            autoWidth: false,
            stateSave: true,
            select: true,
            ajax: "{{ route('fierros.index') }}",
            columns: [
                {data: 'id'},
                {data: 'Folio_Pago'},
                {data: 'Fecha_Tramite'},
                {data: 'localidades.Nom_Loc'},
                {data: 'regiones.Nom_Reg'},
                {data: 'Nombre'},
                {data: 'Paterno'},
                {data: 'Materno'},
                {data: 'Curp'},
                {data: 'Tipo_Tramite'},
                {data: 'action_Fierro'}
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
/* ABRIR MODAL DE NUEVO REGISTRO DE INFORMACION*/
        $('#abrir_form_Fierro').click(function(){
            $('#action').val('Add');//input para diferenciar con editar
            $('#form_result').html('');//el span donde aparece error
            $('#ModalAgregar').modal('show');
            $('.modal-title').text('Nuevo Registro de Fierro Marcador');
            $('#Formulario_Fierro')[0].reset();
            $("#Elaborado_Por").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });
            $("#Id_Loc").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });
        });
//======================================
/*verificar localidad seleccionado y obtener region*/
$("#Id_Loc").change(function(){
        var cod = document.getElementById("Id_Loc").value;
        if(cod==0){
            console.log('guardó un registro y el cambio es igual a 0');
        }else{
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
                     Swal.fire({
                        icon: 'error',
                        title: 'algo salio mal...',
                        text: 'Actualiza la Pagina o contacta al administrador!'
                     })
                }
            });
        }
});
//=====================================
/* peticion de guardar o editar el registro*/
        $('#Formulario_Fierro').on('submit', function(event){
                event.preventDefault(); 
                var action_url = '';
                if($('#action').val() == 'Add'){
                    $('#btnGuardarFierro').val('Guardando');//cambia valor de boton guardar
                    $('#btnGuardarFierro').prop('disabled', true);
                    $('#action').val('');//input para diferenciar de editar
                    action_url = "{{ route('fierros.store') }}";
                }
        
                if($('#action').val() == 'Edit'){
                    $('#action').val('');//input para diferencia de guardar
                    $('#btnGuardarFierro').val('Actualizando');//cambia el texto de boton editar
                    $('#btnGuardarFierro').prop('disabled', true);
                    action_url = "{{ route('fierros.update') }}";
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
                                    title: 'El registro de Fierro Marcador ha sido ' +tipo +' correctamente',
                                    showConfirmButton: false,
                                    timer: 1500
                            })
                            $('#btnGuardarFierro').val('Guardar');
                            $('#btnGuardarFierro').prop('disabled', false);
                            $('#Formulario_Fierro')[0].reset();
                            $('#ModalAgregar').modal('hide');
                            $('.fierros').DataTable().ajax.reload();
                        }
                        $('#form_result').html(html);//coloca en caso de vacio input
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                        Swal.fire({
                          icon: 'error',
                          title: 'algo salio mal...',
                          text: 'actualiza la pagina o comunicate con el administrador!'
                        })
                        $('#btnGuardarFierro').val('Guardar');
                        $('#btnGuardarFierro').prop('disabled', false);
                    }
                });
        });
//===========================================
/* peticion de abrir modal de eliminar un registro */
    var Fierro_Id;
    var Nombre_Fierro;
    var Curp;
    $(document).on('click', '.el_fierro', function(){
        Fierro_Id = $(this).attr('id');
        Nombre_Fierro = $(this).attr('nombre');
        Paterno_Fierro = $(this).attr('paterno');
        Materno_Fierro = $(this).attr('materno');
        Curp = $(this).attr('curp');

        $('#ModalEliminar').modal('show');
        $('.modal-title').text('¿Desea Eliminar Este Registro de Fierro Marcador?');
        document.getElementById('Nombre_Ben').innerHTML ="NOMBRE: "+Nombre_Fierro+" "+Paterno_Fierro+" "+Materno_Fierro;
        document.getElementById('Curp_Ben').innerHTML ="Curp: "+Curp;
    });
//==========================================
/*PETICION DE ELIMINAR UN REGISTRO*/
    $('#btnElFierro').click(function(){
        $.ajax({
            url:"fierros/destroy/"+Fierro_Id,
            beforeSend:function(){
                $('#btnElFierro').text('Eliminando');
                $('#btnElFierro').prop('disabled', true);
            },
            success:function(data)
            {
                Swal.fire({
                    icon: 'success',
                    title: 'Este Registro ha sido Borrado correctamente',
                    showConfirmButton: false,
                    timer: 2000
                })
                $('#btnElFierro').text('Eliminar');
                $('#btnElFierro').prop('disabled', false);
                $('#ModalEliminar').modal('hide');
                $('.fierros').DataTable().ajax.reload();
            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
                Swal.fire({
                    icon: 'error',
                     title: 'algo salio mal...',
                     text: 'Actualiza la Pagina o contacta al administrador!'
                })               
                $('#btnElFierro').text('Eliminar');
                $('#btnElFierro').prop('disabled', false);
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
        $('.modal-title').text('¡¡Estas Editando los datos de un Registo de Fierro Marcador');
        $('#btnGuardarFierro').val('Actualizar');
        $("#Elaborado_Por").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });
            $("#Id_Loc").select2({
               dropdownParent: $('#ModalAgregar'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });

        $.ajax({
            url :"fierros/edit/"+id+"/",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:"json",
            success:function(data)
            {
                console.log('success: '+data);
                $('#Fecha_Tramite').val(data.result.Fecha_Tramite);
                $('#Elaborado_Por').val(data.result.Elaborado_Por).change();
                $('#Id_Loc').val(data.result.Id_Loc).change();
                $('#Nombre').val(data.result.Nombre);
                $('#Paterno').val(data.result.Paterno);
                $('#Materno').val(data.result.Materno);
                $('#Curp').val(data.result.Curp);
                $('#Rfc').val(data.result.Rfc);
                $('#Edad').val(data.result.Edad);
                $('#Sexo').val(data.result.Sexo).change();
                $('#Tipo_Tramite').val(data.result.Tipo_Tramite).change();
                $('#Upp').val(data.result.Upp);
                $('#Folio_Pago').val(data.result.Folio_Pago);
                $('#Total').val(data.result.Total);
                $('#Estatus').val(data.result.Estatus).change();   
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
//===========================================
});
</script> 
@stop