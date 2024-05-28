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
                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
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


    })
</script>
@endpush
