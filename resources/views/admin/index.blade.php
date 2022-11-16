@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <p>Sistema Administrativo, Coordinacion Agropecuaria<br>Elaborado por Sebastian Gomez Sanchez<br>Ing. en Tecnologias de la Informacion y Comunicacion</p>
@stop

@section('content')
@can('admin.beneficiarios.index')
<div class="form-row">
    <div class="form-group col-md-12">
        <h1 style="text-align: center;">Informacion de Avances de Captura</h1>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-4"></div>
    <div class="form-group col-md-4">
        <table class="table table-striped table-bordered avances"> 
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Captura Total</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="form-group col-md-4"></div>
</div>
@endcan

@stop

@section('css')
@stop

@section('js')
<script src="vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="vendor/datatables-plugins/responsive/js/dataTables.responsive.min.js"></script>
<script src="vendor/datatables-plugins/select/js/dataTables.select.min.js"></script>

<!--<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>-->
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script>
$(document).ready(function(){
    //================================================
    const table = $('.avances').DataTable({
            processing: true,
            serverSide:true,
            responsive: true,
            autoWidth: false,
            //dom: 'Bfrtip',
            ajax: "{{ route('admin.index') }}",
            columns: [
                {data: 'created_at'},
                {data: 'COUNT(Curp)'}
            ],
            columns: [
                    {data: 'created_at', name:'created_at'},
                    {data: 'COUNT(Curp)', name:'COUNT(Curp)'},
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
//=======================================================================
});
</script>
@stop