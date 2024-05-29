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
                          <th>Biaya per KK</th>
                          <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Masukan Biaya Tagihan per KK</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form id="updateEvent">
                    @csrf
                    <input type="hidden" name="event_id" value="" id="event_id">
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Rp</span>
                            <input type="text" id="biaya_perkk" name="biaya_perkk" value="" class="form-control" placeholder="100000" aria-label="" aria-describedby="basic-addon1">
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
                ajax: "{{ route('get.event') }}",
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'total_kepala_keluarga', name: 'total_kepala_keluarga' },
                    { data: 'tahun_acara', name : 'tahun_acara'},
                    { data: 'total_pendapatan', name : 'total_pendapatan'},
                    { data: 'biaya_perkk', name : 'biaya_perkk'},
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

        var addBiaya = $(document).on('click','.add_biaya', function() {
            var id = $(this).data("id");
            var value = $(this).data("value");
            var input = document.getElementById('biaya_perkk');
            let result = Math.floor(value);

            $("#modal-default").modal("show");
            document.getElementById('event_id').value = id;
            if(value != null ) {
                input.value = result    ;
            }else {
                input.value = 0;
            }
        });

        var submitBiaya = $("#updateEvent").on('submit', function(e) {
            e.preventDefault()
            var btn  = document.getElementById("simpan");
            $.ajax({
                url     : '{{ route("update.event") }}',
                type    : "POST",
                data    : $(this).serialize(),
                beforeSend : function() {
                    btn.innerHTML = loading;
                },success: function(s) {
                    iziToast.success({
                        title: 'Berhasil.',
                        message: s.pesan,
                        position: 'topRight'
                    });
                    $("#modal-default").modal("hide");
                    table.ajax.reload();
                }, error: function(xhr) {
                    var err = xhr.responseJSON;
                    $.each(err.errors, function(key, value) {
                        iziToast.error({
                            title: 'Error',
                            message: value,
                            position: 'topRight'
                        });
                    });
                },complete: function() {
                    btn.innerHTML = "Simpan";
                }

            });
        });


    });
    console.log('{{ session("errors") }}');
</script>
@endpush
