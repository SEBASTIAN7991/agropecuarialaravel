@extends('adminlte::page')

@section('title', 'Inventario')

@section('content_header')
<div class="form-row">
    <div class="form-group col-md-2">
        <button type="button" name="FormPro" id="FormPro" class="btn btn-info"> <i class="fas fa-fw fa-plus"></i>Nuevo Concepto</button>
    </div>
    <div class="form-group col-md-2">
        <button type="button" name="FormNuevo" id="FormNuevo" class="btn btn-info"> <i class="fas fa-fw fa-plus"></i>Nuevos Productos</button>
    </div>
    <div class="form-group col-md-8">
        <h1 class="text-center">Lista de Inventario</h1>
    </div>
</div>
@stop

@section('content')
<table class="table table-striped table-bordered productos"> 
    <thead>
        <tr>
            <th width="20px">id</th>
            <th width="90px">Tipo</th>
            <th>Descripcion</th>
            <th width="60px">Existencia</th>
            <th width="60px">Salida</th>
            <th width="60px">Stock</th>
            <th width="150px">Opciones</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>
<!--================inicia formulario de agregar concepto================-->
<div id="ModalAgregar" class="modal fade" role="dialog" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="Formulario_Concepto" name="Formulario_Concepto" class="form-horizontal" method="post">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fas fa-fw fa-times-circle"></i>
                    </button>
                </div>
                <div class="modal-body" >

                    <div class="form-row" >
                        <div class="form-group col-md-12" style="display:none;">
                            <label>Apartado de Seccion de Verificacion de Tipo de Proceso</label>
                            <input class="form-control" type="text" name="action" id="action" value="Add" />
                            <input class="form-control" type="text" name="hidden_id" id="hidden_id"/>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Descripcion:</label>
                            <input type="text" class="form-control" name="Descripcion" id="Descripcion" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Asignacion:</label>
                            <select class="form-control" id="Tipo" name="Tipo" required>
                                <option value="">Selecciona</option>
                                <option value="CONSUMIBLE">CONSUMIBLE</option>
                                <option value="FIJO">FIJO</option>
                                <option value="MOBILIARIO">MOBILIARIO</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Existencia:</label>
                            <input type="number" class="form-control" name="Existencia" id="Existencia" required>
                        </div>
                    </div>

                    <span id="form_result"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <input type="submit" class="btn btn-primary" name="btnGuardarConcepto" id="btnGuardarConcepto" value="Guardar" />
                </div>
            </form>  
        </div>
    </div>
</div>
<!--================inicia formulario de agregar Producto================-->
<div id="ModalAgregarProducto" class="modal fade" role="dialog" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="Formulario_Producto" name="Formulario_Producto" class="form-horizontal" method="post">
                @csrf
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fas fa-fw fa-times-circle"></i>
                    </button>
                </div>
                <div class="modal-body" >

                    <div class="form-row" >
                        <div class="form-group col-md-12" style="display:none;">
                            <label>Apartado de Seccion de Verificacion de Tipo de Proceso</label>
                            <input class="form-control" type="text" name="actionPro" id="actionPro" value="Add" />
                            <input class="form-control" type="text" name="hidden_idPro" id="hidden_idPro"/>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Fecha Recepcion:</label>
                            <input class="form-control" type="date" name="Fecha_Recepcion" id="Fecha_Recepcion"/>
                        </div>
                        <div class="form-group col-md-4"></div>
                        <div class="form-group col-md-1">
                            <label>Mas</label>
                            <button type="button" name="masPro" id="masPro" class="form-control btn btn-info">+</button>
                        </div>
                        <div class="form-group col-md-1">
                            <label>Menos</label>
                            <button type="button" name="menosPro" id="menosPro" class="form-control btn btn-info">-</button>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-10">
                            <label>Selecciona Una Descripcion</label>
                            <select class="form-control" id="DescripcionPro" name="DescripcionPro" required>
                                <option value="">Selecciona una Opcion:</option>
                            </select> 
                        </div>
                        <div class="form-group col-md-1">
                            <label>Cantidad:</label>
                            <input type="number" class="form-control" name="ExistenciaPro" id="ExistenciaPro" required>
                        </div>
                        <div class="form-group col-md-1">
                            <label>lineas:</label>
                            <input type="number" class="form-control" name="NumLineas" id="NumLineas" value="1" required>
                        </div>
                    </div>

                    <div id="mas">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-fw fa-times-circle"></i>Cancelar</button>
                    <input type="submit" class="btn btn-primary" name="btnGuardarProducto" id="btnGuardarProducto" value="Guardar" />
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
    $('#masPro').click(function(){
           var num = $("#NumLineas").val();
            var total=parseInt(num)+1;
            document.getElementById('NumLineas').value = ''+total;
            var a=0;
            a++;
            var div = document.createElement('div');
            document.getElementById('mas').appendChild(div);
            div.innerHTML = 
                '<div class="form-row" id="fila'+total+'">'+
                    '<div class="form-group col-md-10">'+
                        '<label>Selecciona la Descripcion Num. '+total+'</label>'+
                        '<select class="form-control" id="Descripcion'+total+'" name="Descripcion'+total+'" required>'+
                        '<option value="" selected>Selecciona una Opcion</option>'+
                        '</select>'+
                    '</div>'+
                    '<div class="form-group col-md-2"">'+
                        '<label>Cant: '+total+'</label>'+
                        '<input class="form-control" name="Existencia'+total+'" id="Existencia'+total+'" type="text"/>'+
                    '</div>'+
                '</div>';
            //==========================

               $.ajax({
                    url :"productos/datos/",
                    dataType:"json",
                    beforeSend:function(){
                        $('#masPro').prop('disabled', true);
                    },
                    success:function(data)
                    {
                        $('#masPro').prop('disabled', false);
                        var tamaño=data.result.length;
                        let valores=[];
                        var hola='';
                        for(var i=0; i<=tamaño-1; i++){
                          var pro=data.result[i]['id'];
                          var des=data.result[i]['Descripcion'];
                          $('#Descripcion'+total).append('<option value="' + pro + '">' + des + '</option>');
                          $("#Descripcion"+total).select2({
                            dropdownParent: $('#ModalAgregarProducto'),
                            width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
                           });
                          //valores.push([pro,'=',total])
                        }
                         //$('#Descripcion'+total).append('<option value="' + data.result.id + '">' + data.result.Descripcion + '</option>');
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                        Swal.fire({
                          icon: 'error',
                          title: 'algo salio mal...',
                          text: 'no se pudo enviar la localidad en la seleccion, actualiza la pagina o comunicate con el admnistrador!'
                        })
                    }
                })
            //==========================

    });
    $('#menosPro').click(function(){
        var num = $("#NumLineas").val();
        if(num=='1'){
             Swal.fire({
                    position: 'center',
                    icon: 'info',
                    title: '!!NO HAY FILAS POR QUITAR!!',
                    showConfirmButton: false,
                    timer: 2000
                })
        }
    else{           
            var total=parseInt(num)-1;
            document.getElementById('NumLineas').value = ''+total;
            $('#fila'+num).remove();
}
    });
//=========================================================
    const table = $('.productos').DataTable({
            processing: true,
            serverSide:true,
            responsive: true,
            autoWidth: false,
            //dom: 'Bfrtip',
            ajax: "{{ route('productos.datatable') }}",
            columns: [
                {data: 'id', name:'id'},
                {data: 'Tipo',name:'Tipo'},
                {data: 'Descripcion',name:'Descripcion'},
                {data: 'Existencia',name:'Existencia'},
                {data: 'Salida',name:'Salida'},
                {data: 'Stock', name:'Stock'},
                {data: 'action_producto',name:'action_producto'},
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
/* abrir modal de nuevo Concepto*/
        $('#FormPro').click(function(){
            $('#action').val('Add');//input para diferenciar con editar
            $('#form_result').html('');//el span donde aparece error
            $('#ModalAgregar').modal('show');
            $('.modal-title').text('Registrando Nuevo Concepto');
            $('#Formulario_Concepto')[0].reset();
        });
//===================================
/* abrir modal de nuevo Producto*/
        $('#FormNuevo').click(function(){
            $('#actionPro').val('Add');//input para diferenciar con editar
            $('#form_resultPro').html('');//el span donde aparece error
            $('#ModalAgregarProducto').modal('show');
            $('.modal-title').text('Registrando Nuevo Producto');
            var num = $("#NumLineas").val();
            for (var i = 2; i <= num; i++) {
                $('#fila'+i).remove();
            }
            $('#Formulario_Producto')[0].reset();
            $("#DescripcionPro").select2({
               dropdownParent: $('#ModalAgregarProducto'),
               width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
            });
            //==========================
           $.ajax({
                url :"productos/datos/",
                dataType:"json",
                success:function(data)
                {
                    var tamaño1=data.result.length;
                    for(var i=0; i<=tamaño1-1; i++){
                      var pro1=data.result[i]['id'];
                      var des1=data.result[i]['Descripcion'];
                      $('#DescripcionPro').append('<option value="' + pro1 + '">' + des1 + '</option>');
                      $("#DescripcionPro").select2({
                        dropdownParent: $('#ModalAgregarProducto'),
                        width:'100%'//en caso de no funcionar, puedes agregar el tamaño directamente en el input 
                       });
                      //valores.push([pro,'=',total])
                    }
                     //$('#Descripcion'+total).append('<option value="' + data.result.id + '">' + data.result.Descripcion + '</option>');
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    console.log(errors);
                    Swal.fire({
                      icon: 'error',
                      title: 'algo salio mal...',
                      text: 'no se pudo enviar la localidad en la seleccion, actualiza la pagina o comunicate con el admnistrador!'
                    })
                }
            })
        //==========================
        });
//=====================================
/* peticion de guardar o editar el registro */
        $('#Formulario_Concepto').on('submit', function(event){
                event.preventDefault(); 
                var action_url = '';
                if($('#action').val() == 'Add'){
                    $('#btnGuardarConcepto').val('Guardando');//cambia valor de boton guardar
                    $('#action').val('');//input para diferenciar de editar
                    action_url = "{{ route('productos.store') }}";
                }
        
                if($('#action').val() == 'Edit'){
                    $('#action').val('');//input para diferencia de guardar
                    $('#btnGuardarConcepto').val('Actualizando');//cambia el texto de boton editar
                    action_url = "{{ route('productos.update') }}";
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
                                title: 'El Nuevo Concepto ha sido ' +tipo +' correctamente',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            $('#btnGuardarPro').val('Guardar');
                            $('#Formulario_Concepto')[0].reset();
                            $('#ModalAgregar').modal('hide');
                            $('.productos').DataTable().ajax.reload();
                        }
                        $('#form_result').html(html);//coloca en caso de vacio input
                    },
                    error: function(data) {
                        var errors = data.responseJSON;
                        console.log(errors);
                    }
                });
        });

//=====================================
/* peticion de guardar varias productos */
        $('#Formulario_Producto').on('submit', function(event){
                event.preventDefault(); 
                var action_url = '';
                if($('#actionPro').val() == 'Add'){
                    $('#btnGuardarProducto').val('Guardando');//cambia valor de boton guardar
                    $('#actionPro').val('');//input para diferenciar de editar
                    action_url = "{{ route('inventarios.store') }}";
                }
        
                if($('#actionPro').val() == 'Edit'){
                    $('#actionPro').val('');//input para diferencia de guardar
                    $('#btnGuardarProducto').val('Actualizando');//cambia el texto de boton editar
                }
                /*var num = $("#NumLineas").val();
                if(num>1){
                    for (var i = 0; i <= num; i++) {
                        alert($("#Existencia"+1).val())
                    }
                }*/
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
                                title: 'El Nuevo Concepto ha sido ' +tipo +' correctamente',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            $('#btnGuardarProducto').val('Guardar');
                            $('#Formulario_Producto')[0].reset();
                            $('#ModalAgregarProducto').modal('hide');
                            $('.productos').DataTable().ajax.reload();
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
});
</script>
@stop
