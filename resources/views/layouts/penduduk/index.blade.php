@extends('layouts.master')
@section('MenuName'){{ 'Data Penduduk' }}@endsection
@section('MenuNameDetail')
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Data Penduduk</li>
    </ol>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">DataTable Penduduk</h3>

            </div>
            <div class="card-body">
                <div>
                    <button id="btn-create" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></button>
                    &nbsp;
                    <a href="#" class="btn btn-warning btn-sm"><i class="fa fa-file-import"></i></a>

                </div>
                <br>
                <table id="table" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                          <th width="10">No</th>
                          <th>Nama Kepala Keluarga</th>
                          <th>Jumlah KK</th>
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
                <h4 class="modal-title">Tambah data penduduk</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form id="form-create">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Nama Kepala keluarga:</label>
                            <input type="text" name="head_name" id="head_name" class="form-control" required placeholder="Masukan nama kepala keluarga">
                          </div>
                          <div class="form-group">
                            <label for="address" class="col-form-label">Alamat</label>
                            <input name="address" type="text" class="form-control" id="address" required placeholder="Masukan detail alamat">
                          </div>
                          <div class="form-group">
                              <label for="" class="col-form-label">Nomor HP</label>
                              <input type="text" class="form-control" id="phone" name="phone" required placeholder="Masukan nomer telepon yang aktif">
                          </div>
                          <div class="form-group">
                            <label for="" class="col-form-label">Total Anggota keluarga</label>
                            <input type="text" class="form-control" id="member_count" name="member_count" placeholder="masukan total jumlah keluarga" required >
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
<div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah data penduduk</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form id="form-update">
                    <input type="hidden" name="id" value="" id="id">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Nama Kepala keluarga:</label>
                            <input type="text" name="head_name" id="head_name_edit" class="form-control" required placeholder="Masukan nama kepala keluarga">
                          </div>
                          <div class="form-group">
                            <label for="address" class="col-form-label">Alamat</label>
                            <input name="address" type="text" class="form-control" id="address_edit" required placeholder="Masukan detail alamat">
                          </div>
                          <div class="form-group">
                              <label for="" class="col-form-label">Nomor HP</label>
                              <input type="text" class="form-control" id="phone_edit" name="phone" required placeholder="Masukan nomer telepon yang aktif">
                          </div>
                          <div class="form-group">
                            <label for="" class="col-form-label">Total Anggota keluarga</label>
                            <input type="text" class="form-control" id="member_count_edit" name="member_count" placeholder="masukan total jumlah keluarga" required >
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" id="update" class="btn btn-primary">Simpan</button>
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
                ajax: "{{ route('get.penduduk') }}",
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'head_name', name: 'head_name' },
                    { data: 'member_count', name : 'member_count'},
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
        });

        var deleted = $(document).on('click','.delete-household',function() {
            var id  = $(this).data('id');
            var url = $(this).data('url');
            var btn = document.getElementById('btn_'+id);
            $.ajax({
                url : url,
                type: "DELETE",
                beforeSend: function() {
                    btn.innerHTML = loading
                },success: function(res) {
                    iziToast.success({
                        title: 'Berhasil',
                        message : res.pesan,
                        position : 'topRight',
                    });
                    table.ajax.reload();

                },error: function(er) {
                    iziToast.error({
                        title: 'Error',
                        message : 'Terjadi kesalahan',
                        position: 'topRight'
                    });
                }
            })
        })

        var create = $(document).on('click','#btn-create', function() {
            $("#modal-create").modal('show');
        });

        var store = $("#form-create").submit(function(e) {
            e.preventDefault()
            var btn = document.getElementById('simpan');
            $.ajax({
                url : "{{ route('penduduk.store') }}",
                type: "POST",
                data : $(this).serialize(),
                beforeSend: function() {
                    btn.innerHTML = loading;
                    btn.setAttribute("disabled","true");
                },success: function(s) {
                    iziToast.success({
                        title: 'Berhasil.',
                        message: s.pesan,
                        position: 'topRight'
                    });
                    $("#modal-create").modal("hide");
                    table.ajax.reload();
                },error : function(xhr) {
                    var err = xhr.responseJSON
                    iziToast.error({
                            title: 'Error',
                            message: err.errors,
                            position: 'topRight'
                        });

                },complete: function(){
                    btn.innerHTML = "Simpan";
                    btn.removeAttribute("disabled");
                }
            })
        })

        var resetModalCreate = $('#modal-create').on('hidden.bs.modal', function (e) {
                            // Mereset formulir
                            document.getElementById("form-create").reset();
        });

        var edit = $(document).on('click','.edit-household', function() {
            var id = $(this).data('id');
            var btn = document.getElementById('btn_edit'+id);
            var url = '{{ route("penduduk.edit",["id" => ":id"]) }}'.replace(":id",id);
            $.ajax({
                url : url,
                type: "GET",
                beforeSend: function() {
                    btn.innerHTML = loading;
                    btn.setAttribute("disabled","true");
                },success: function(s) {
                    $("#modal-edit").modal("show");
                    $("#head_name_edit").val(s.data.head_name);
                    $("#address_edit").val(s.data.address);
                    $("#phone_edit").val(s.data.phone);
                    $("#member_count_edit").val(s.data.member_count);
                    $("#id").val(s.data.id);
                }, error: function(xhr) {
                    iziToast.error({
                            title: 'Error',
                            message: "Gagal mengambil data",
                            position: 'topRight'
                        });
                },complete: function() {
                    btn.innerHTML = '<i class="nav-icon fas fa-pen"></i>';
                    btn.removeAttribute("disabled");

                }
            });
        });

        var update = $("#form-update").submit(function(e) {
            e.preventDefault();
            var btn = document.getElementById('update');
            $.ajax({
                url : "{{ route('penduduk.update') }}",
                type:"POST",
                data:$(this).serialize(),
                beforeSend: function() {
                    btn.innerHTML = loading;
                    btn.setAttribute("disabled","true");
                },success: function(s) {
                    iziToast.success({
                        title: 'Berhasil.',
                        message: s.pesan,
                        position: 'topRight'
                    });
                    table.ajax.reload();
                    $("#modal-edit").modal("hide");
                },error: function(xhr) {
                    iziToast.error({
                            title: 'Error',
                            message: 'Gagal update data',
                            position: 'topRight'
                        });
                }, complete: function() {
                    btn.innerHTML = "Simpan";
                    btn.removeAttribute("disabled")

                }
            })
        })


    })
</script>
@endpush
