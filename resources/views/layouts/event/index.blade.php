@extends('layouts.master')
@section('MenuName'){{ 'Data Pengihan sumbangan 17 Agustus' }}@endsection
@section('MenuNameDetail')
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Data Pengihan sumbangan 17 Agustus</li>
    </ol>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data event 17 Agustus per tahun</h3>

            </div>
            <div class="card-body">
                <table id="table" class="table table-bordered table-hover" style="width: 100%">
                    <thead>
                        <tr>
                          <th width="10">No</th>
                          <th>Total KK</th>
                          <th>Tahun Acara</th>
                          <th>Pendapatan</th>
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
                ajax: "{{ route('get.event') }}",
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'total_kepala_keluarga', name: 'total_kepala_keluarga' },
                    { data: 'tahun_acara', name : 'tahun_acara'},
                    { data: 'total_pendapatan', name : 'total_pendapatan'},
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
