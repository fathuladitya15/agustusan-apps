@extends('layouts.master')
@section('MenuName'){{ 'Dashboard' }}@endsection
@section('MenuNameDetail')
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
    </ol>
</div>
@endsection

@section('content')

    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- Box total pelamar  -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $countHousehold }}</h3>

                    <p>Total Penduduk</p>
                </div>
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>
                <a href="{{ route('penduduk') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- Box Total lowongan pekerjaan  -->
            <div class="small-box bg-success">
                <div class="inner">
                    {{-- <h3>53<sup style="font-size: 20px">%</sup></h3> --}}
                    <h3>{{ $getPayment }}</h3>

                    <p>{{ $nameEvent }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-newspaper"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @can('manage_users')
        <div class="col-lg-3 col-6">
            <!-- box karyawan yang aktif -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $getCountUsers }}</h3>

                    <p>Pengguna</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user-check"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        @endcan
        <!-- ./col -->
        {{-- <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>65</h3>

                    <p>Karyawan tidak aktif</p>
                </div>
                <div class="icon">
                <i class="fa fa-user-minus"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div> --}}
        <!-- ./col -->
    </div>
    <!-- /.row -->
@endsection
