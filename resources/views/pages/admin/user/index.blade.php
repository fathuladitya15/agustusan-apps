@extends('layouts.master')
@section('MenuName'){{ 'Data Pengguna' }}@endsection
@section('MenuNameDetail')
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Data Pengguna</li>
    </ol>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Table Pengguna</h3>

            </div>
            <div class="card-body">
                <button class="btn btn-primary btn-sm" id="btn-create" style="float: right;"><i class="fa fa-plus"></i></button>
                <br>
                <br>
                <table id="table" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                          <th width="10">No</th>
                          <th>Nama</th>
                          <th>Sebagai</th>
                          <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-create">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Pengguna</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form id="form-create">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="username" class="col-form-label">Nama Pengguna:</label>
                            <input name="username" type="text" class="form-control" id="username" placeholder="username">
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-form-label">Nama Lengkap:</label>
                            <input name="name" type="text" class="form-control" id="name" placeholder="Nama Lengkap">
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-form-label">Email:</label>
                            <input name="email" type="text" class="form-control" id="email" placeholder="email_anda@mail.id">
                        </div>
                        <div class="form-group">
                          <label for="password" class="col-form-label">Password:</label>
                          <input type="password" name="password" class="form-control" placeholder="***">
                        </div>
                        <div class="form-group">
                            <label for="sebagai" class="col-form-label">Sebagai:</label>
                          <select name="sebagai" id="select-sebagai" class="form-control"></select>

                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" id="simpan" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            var table = $("#table").DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive:true,
                ajax: "{{ route('users.data') }}",
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'name', name: 'name' },
                    { data: 'roles',name: 'roles'},
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            var modalCreate  = $("#btn-create").click(function() {
                $("#modal-create").modal('show');
            });

            var url_select = "{{ route('users.get.roles') }}";
            $('#select-sebagai').select2({
            dropdownParent: $("#modal-create"),
            ajax: {
                url: url_select,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // term pencarian dari pengguna
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (role) {
                            return {
                                id: role.id,
                                text: role.name // tampilkan nama di dropdown
                            };
                        })
                    };
                },
                cache: true
            },
            placeholder: 'Pilih Tipe Login',
            minimumInputLength: 0 // minimal karakter yang harus dimasukkan sebelum pencarian dilakukan
        });
        });
    </script>
@endpush

