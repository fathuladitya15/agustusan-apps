@extends('layouts.master')
@section('MenuName'){{ $households->head_name }}@endsection
@section('MenuNameDetail')
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Detail penagihan {{ $households->head_name }}</li>
    </ol>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                {{-- <h3 class="card-title">Detail Penagihan agustusan {{ $data->tahun_acara }}</h3> --}}
                <a class="btn btn-danger btn-sm" href="{{ route('detail.event',['id' => $event_id]) }}"><i class="fa fa-arrow-left"></i></a>
            </div>
            <div class="card-body">
                <table id="table" class="table table-bordered table-hover table-responsive" style="width:100%">
                    <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Kepala Keluarga</th>
                          <th>Minggu ke -</th>
                          <th>Tanggal Pembayaran</th>
                          <th>Pembayaran</th>
                          <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="5" style="text-align: right;" id="totalAllPayments"></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-update">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Penagihan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form id="updateTagihan">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event_id }}">
                    <input type="hidden" name="detail_event_id" id="detail_event_id" value="">
                    <div class="modal-body">

                        <div class="form-group">
                          <label for="recipient-name" class="col-form-label">Nama Kepala keluarga:</label>
                          <input type="text" readonly value="" id="nama_kepala_keluarga" class="form-control" >
                        </div>
                        <div class="form-group">
                          <label for="message-text" class="col-form-label">Minggu Ke -:</label>
                          <input name="minggu_ke" type="text" class="form-control" id="minggu_ke_edit">
                        </div>
                        <div class="form-group">
                            <label for="" class="col-form-label">Nominal</label>
                            <input type="number" class="form-control" id="nominal_edit" name="nominal">
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" id="update" class="btn btn-primary">Perbarui</button>
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
        var url = "{{ route('edit.detail.event.id',['event_id' => $event_id,'name' => $households->head_name,'user_id'=> $households->id]) }}";
        var table = $("#table").DataTable({
                processing: true,
                serverSide: true,
                ajax: url,
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'nama_kk', name: 'nama_kk' },
                    { data: 'minggu_ke', name : 'minggu_ke'},
                    { data: 'created_at', name : 'created_at'},
                    { data: 'jumlah_bayar', name : 'jumlah_bayar'},
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                responsive : true,
                autoWidth: false,
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();

                    // Menampilkan total semua jumlah bayar di footer
                    $('#totalAllPayments').html(
                        'Total dana terkumpul : ' + '{{ $formatAllPayments }}'
                    );
            }
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

        var edit = $(document).on('click','.edit-event', function() {
            var id_detail_event = $(this).data('id');
            var url_edit = "{{ route('get.detail.event.id',['id' => ':id']) }}".replace(':id', id_detail_event);
            var btn = document.getElementById('btn_edit'+id_detail_event);
            $.ajax({
                url : url_edit,
                type : "GET",
                beforeSend: function() {
                    btn.innerHTML = loading;
                },
                success: function(res){
                    $("#modal-update").modal('show');
                    $("#detail_event_id").val(res.data.id);
                    $("#nama_kepala_keluarga").val(res.data.nama_kk);
                    $("#nominal_edit").val(res.data.nominal);
                    $("#minggu_ke_edit").val(res.data.minggu_ke);
                },
                error : function(xhr) {
                    var err = xhr.responseJSON;
                    $.each(err.errors, function(key, value) {
                        iziToast.error({
                            title: 'Error',
                            message: value,
                            position: 'topRight'
                        });
                    });
                },
                complete : function() {
                    btn.innerHTML = '<i class="nav-icon fas fa-solid fa-pen"></i>';
                }
            })

        });

        var updated = $("#updateTagihan").submit(function(e) {
            e.preventDefault();
            var btn = document.getElementById('update');
            $.ajax({
                url : "{{ route('update.detail.event.id') }}",
                data : $(this).serialize(),
                type: "POST",
                beforeSend : function() {
                    btn.innerHTML = loading
                },success: function(s) {
                    iziToast.success({
                        title: 'Berhasil.',
                        message: s.pesan,
                        position: 'topRight'
                    });
                    $("#modal-update").modal("hide");
                    table.ajax.reload();
                },
                error: function(xhr) {
                    var err = xhr.responseJSON;
                    $.each(err.errors, function(key, value) {
                        iziToast.error({
                            title: 'Error',
                            message: value,
                            position: 'topRight'
                        });
                    });
                },complete:function() {
                    btn.innerHTML = 'Perbarui';
                }

            })
        })

        var deleted = $(document).on('click','.delete-event',function() {
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
