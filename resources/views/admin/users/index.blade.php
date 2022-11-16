@extends('adminlte::page')

@section('title', 'Usuarios')
@section('content_header')
    <h1>Lista de Usuarios Agropecuaria</h1>
    
@stop
@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="usuarios">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                      <tr>
                          <td>{{$user->id}}</td>
                          <td>{{$user->name}}</td>
                          <td>{{$user->email}}</td>
                          <td width="10px">
                              <a class="btn btn-primary" href="{{route('admin.users.edit',$user)}}" >Editar</a>
                          </td>
                      </tr>  
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="../css/admin_custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="../vendor/datatables/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../vendor/datatables-plugins/responsive/css/responsive.bootstrap4.min.css">
@stop

@section('js')
<script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="../vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="../vendor/datatables-plugins/responsive/js/dataTables.responsive.min.js"></script>
<script src="../vendor/datatables-plugins/responsive/js/responsive.bootstrap4.min.js"></script>
<script>
     $('#usuarios').DataTable({
        responsive: true,
        autoWidth: false,
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
        }
     });
</script>
@stop
