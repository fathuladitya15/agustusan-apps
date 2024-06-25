@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Penagihan Agustusan </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <label for="">Tahun Acara </label>
                        <div class="input-group ">
                            <select name="tahun" id="tahun" class="form-control">
                                <option value="">-- Pilih Tahun - --</option>
                                @foreach ($event as $item)
                                    <option value="{{ $item->id }}">{{ $item->tahun_acara }}</option>
                                @endforeach
                            </select>
                            <span class="input-group-append">
                              <button  type="button" class="btn btn-info btn-flat" id="search"><i class="fa-solid fa-magnifying-glass"></i>  Cari</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" id="resultTable" style="display: none;">
    <div class="col-12">
        <div class="callout callout-info">
            <h5><i class="fas fa-info"></i> Note:</h5>
            Data ini di ambil berdasar penagihan terbaru
        </div>


        <!-- Main content -->
        <div class="invoice p-3 mb-3">
            <!-- title row -->
            <div class="row">
                <div class="col-12">
                    <h4>
                    <i class="fas fa-globe"></i> Laporan Penagihan, .
                    <small class="float-right">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</small>
                    </h4>
                </div>
            <!-- /.col -->
            </div>
            <!-- Table row -->
            <div class="row ">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Minggu ke</th>
                            <th>Total Penagihan</th>
                        </tr>
                        </thead>
                        <tbody id="tableBody">

                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                    <p class="lead">Detail Informasi</p>
                    <p style="margin-top: 10px;">
                        Halaman ini menyediakan informasi rinci tentang penagihan untuk event atau acara yang telah Anda pilih.
                        Anda dapat mencari berdasarkan tahun event untuk melihat detail pendapatan mingguan dari acara tersebut. Setelah memilih tahun,
                        data penagihan akan ditampilkan dalam bentuk tabel, yang mencakup:
                    </p>
                    <ul>
                        <li>
                            <p><strong>Minggu ke</strong> Minggu keberapa dari event tersebut.</p>
                        </li>
                        <li>
                            <p><strong>Total Pendapatan</strong> Jumlah total pendapatan dalam format Rupiah untuk setiap minggu.</p>
                        </li>
                    </ul>
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <p class="lead">Detail Pendapatan</p>

                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td id="subtotal"></td>
                      </tr>
                      <tr>
                        <th>Biaya per Kepala Keluarga</th>
                        <td id="biaya_perkk"></td>
                      </tr>
                      <tr>
                        <th>Penagihan Terakhir </th>
                        <td id="lastDate"></td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>

            <!-- this row will not appear when printing -->
            {{-- <div class="row no-print">
                <div class="col-12">
                    <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                    <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                    Payment
                    </button>
                    <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                    <i class="fas fa-download"></i> Generate PDF
                    </button>
                </div>
            </div> --}}
        </div>
        <!-- /.invoice -->
    </div><!-- /.col -->
</div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            var search = $(document).on('click','#search',function() {
                var value = $("#tahun").val();
                var btn = document.getElementById('search');
                if (value == "" || value == null) {
                    iziToast.error({
                        title: 'Error.',
                        message: 'Anda belum memilih tahun acara',
                        position: 'topRight'
                    })
                }else {
                    var div_table = document.getElementById('resultTable');
                    $.ajax({
                        url : "{{ route('info.search') }}",
                        type: "GET",
                        data : {event_id : value},
                        beforeSend:function() {
                            btn.innerHTML = loading;
                            btn.disabled  = true;
                        },success: function(s) {
                            console.log(s);
                            $('#tableBody').empty();
                            document.getElementById('subtotal').innerHTML = "Rp." + s.subtotal;
                            document.getElementById('biaya_perkk').innerHTML = "Rp." + s.biaya_perkk;
                            document.getElementById('lastDate').innerHTML = s.lastDate;
                            s.data.forEach(item => {
                                const totalBayarFormatted = formatRupiah(item.total_bayar, 'Rp. ');
                                $('#tableBody').append(`<tr>
                                    <td>${item.minggu_ke}</td>
                                    <td>${totalBayarFormatted}</td>
                                </tr>`);
                            });
                            $("#resultTable").show(300);
                        },error : function(xhr) {
                            console.log(xhr);
                        },complete: function() {
                            btn.innerHTML = '<i class="fa-solid fa-magnifying-glass"></i>  Cari';
                            btn.disabled = false;
                        }
                    })
                }
            })

            function formatRupiah(angka, prefix) {
                let numberString = angka.toString(),
                    split = numberString.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix === undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
            }
        })
    </script>
@endpush
