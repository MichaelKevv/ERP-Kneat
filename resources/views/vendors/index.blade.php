@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Vendor</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Vendor</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('vendors/create') }}"><button class="btn btn-success">Tambah Data</button></a>
                    <a href="" target="_blank"><button class="btn btn-success float-end">Export
                            PDF</button></a>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Tipe Vendor</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Nomor HP</th>
                                <th>Email</th>
                                <th>NPWP</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $vendor)
                                <tr>
                                    <td>{{ Str::title($vendor->tipe_vendor) }}</td>
                                    <td>{{ $vendor->nama }}</td>
                                    <td>{{ $vendor->alamat }}</td>
                                    <td>{{ $vendor->no_hp }}</td>
                                    <td>{{ $vendor->email }}</td>
                                    <td>{{ $vendor->npwp ?? '-' }}</td>
                                    <td>
                                        @if (!$vendor->foto)
                                            <img src="{{ asset('images/img-not-found.png') }}"
                                                width="100px">
                                        @else
                                            <img src="{{ url('storage/foto-vendor/' . $vendor->foto) }}"
                                                width="100px">
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('vendors.edit', $vendor->id) }}"
                                            class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                                            data-original-title="Edit Vendor">
                                            <button class="btn btn-primary" type="button">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </a>
                                        <a href="{{ route('vendors.destroy', $vendor->id) }}"
                                            class="btn btn-danger font-weight-bold text-xs" data-confirm-delete="true">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
@endsection
