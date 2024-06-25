@extends('layouts.master')
@section('MenuName'){{ 'Detail Event' }}@endsection
@section('MenuNameDetail')
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Detail Event Agustusan tahun {{ $data->tahun_acara }}</li>
    </ol>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Penagihan agustusan {{ $data->tahun_acara }}</h3>

            </div>
            <div class="card-body">
                <div>
                    <a href="{{ route('event') }}" class="btn btn-danger btn-sm"><i class="fa-solid fa-arrow-left"></i></a>
                    &nbsp;
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-default">
                        <i class="fa fa-plus"></i>
                    </button>

                </div>
                <br>
                <h4>Hitung tagihan mingguan</h4>
                <hr>
                <form action="#">
                    <div class="row">
                        <div class="col-6">
                            <label for="">Minggu ke ? </label>
                            <div class="input-group ">
                                <select name="minggu" id="minggu" class="form-control">
                                    <option value="">-- Pilih minggu ke - --</option>
                                    @foreach ($events as $item)
                                        <option value="{{ $item->minggu_ke }}">Minggu ke - {{ $item->minggu_ke }}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-append">
                                  <button  type="button" class="btn btn-info btn-flat" id="count"><i class="fa-solid fa-calculator"></i>  Hitung</button>
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="">Hasil</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">
                                   Rp
                                  </span>
                                </div>
                                <input type="text" class="form-control" id="jumlah" readonly>
                              </div>
                        </div>
                    </div>
                </form>
                <br><br>
                <h4>Data Tagihan</h4>
                <hr>
                <table id="table" class="table table-bordered table-hover table-responsive" style="width:100%">
                    <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Kepala Keluarga</th>
                          <th>Minggu ke -</th>
                          <th>Tanggal Pembayaran</th>
                          <th>Pembayaran</th>
                          <th>Status</th>
                          <th>Total Pembayaran</th>
                          <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="7" style="text-align: right;" id="totalAllPayments"></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Input Penagihan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form id="createTagihan">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $data->id }}">
                    <div class="modal-body">

                        <div class="form-group">
                          <label for="recipient-name" class="col-form-label">Nama Kepala keluarga:</label>
                          <select name="household_id" id="select-household" class="form-control"></select>
                        </div>
                        <div class="form-group">
                          <label for="message-text" class="col-form-label">Minggu Ke -:</label>
                          <input name="minggu_ke" type="text" class="form-control" id="message-text">
                        </div>
                        <div class="form-group">
                            <label for="" class="col-form-label">Nominal</label>
                            <input type="number" class="form-control" id="nominal" name="nominal">
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
                    <input type="hidden" name="event_id" value="{{ $data->id }}">
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
        var url = "{{ route('get.detail.event',['id' => $data->id]) }}";
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
                    { data: 'status', name : 'status'},
                    { data: 'terkumpul', name : 'terkumpul'},
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

        var url_select = "{{ route('get.house.hold',['id' => $data->id]) }}";

        $('#select-household').select2({
            dropdownParent: $("#modal-default"),
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
                        results: $.map(data, function (household) {
                            return {
                                id: household.id,
                                text: household.head_name // tampilkan nama di dropdown
                            };
                        })
                    };
                },
                cache: true
            },
            placeholder: 'Pilih household',
            minimumInputLength: 0 // minimal karakter yang harus dimasukkan sebelum pencarian dilakukan
        });

        var saved = $("#createTagihan").submit(function(e) {
            e.preventDefault();
            var btn = document.getElementById('simpan');

            $.ajax({
                url : "{{ route('crete.detail.event') }}",
                data  : $(this).serialize(),
                type: "POST",
                beforeSend: function() {
                    btn.innerHTML = loading
                },success: function(s) {
                    var status = s.status;

                    if(status == true) {
                        iziToast.success({
                            title: 'Berhasil.',
                            message: s.pesan,
                            position: 'topRight'
                        });
                        $("#modal-default").modal("hide");
                        table.ajax.reload();

                    }else {
                       iziToast.error({
                            title: 'Error',
                            message: s.pesan,
                            position: 'topRight'
                        });
                    }
                    console.log(s);
                },error: function(xhr) {
                    var err = xhr.responseJSON;
                    $.each(err.errors, function(key, value) {
                        iziToast.error({
                            title: 'Error',
                            message: value,
                            position: 'topRight'
                        });
                    });
                },
                complete: function() {
                    btn.innerHTML = "Simpan";
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


        var count = $(document).on('click','#count',function () {
            var value = $("#minggu").val();
            var event_id = "{{ $events_id }}";
            var url_weeks = "{{ route('count.detail.event.weeks') }}";
            if (value == "" || value == null) {
                iziToast.error({
                    title: 'Error',
                    message : 'Anda belum memilih minggu',
                    position : 'topRight',
                });
            }else {
                $.ajax({
                    url : url_weeks,
                    data : {minggu_ke : value, event_id : event_id},
                    type:'GET',
                    beforeSend: function() {

                    },success: function(s){
                        var total = s.jumlah;
                        document.getElementById('jumlah').value = total;
                        console.log(total);

                    },error: function(xhr) {
                        iziToast.error({
                            title: 'Error',
                            message : 'Terjadi kesalahan, hubungi tim IT',
                            position : 'topRight',
                        });
                    }
                })
            }
        })

    })
</script>
@endpush
