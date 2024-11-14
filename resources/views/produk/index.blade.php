@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Produk</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Produk</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('produk/create') }}"><button class="btn btn-success">Tambah Data</button></a>
                    <a href="" target="_blank"><button class="btn btn-success float-end">Export
                            PDF</button></a>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Barcode</th>
                                <th>Internal Ref</th>
                                <th>Nama Produk</th>
                                <th>Harga Jual</th>
                                <th>Biaya Produk</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $produk)
                                <tr>
                                    <td>{{ $produk->barcode }}</td>
                                    <td>{{ $produk->internal_reference }}</td>
                                    <td>{{ $produk->nama_produk }}</td>
                                    <td>Rp{{ number_format($produk->harga_jual, 2) }}</td>
                                    <td>Rp{{ number_format($produk->biaya_produk, 2) }}</td>
                                    <td>
                                        @if (!$produk->foto)
                                            <img src="{{ asset('images/img-not-found.png') }}" width="100px">
                                        @else
                                            <img src="{{ url('storage/foto-produk/' . $produk->foto) }}" width="100px">
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('produk.edit', $produk->id) }}"
                                            class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                                            data-original-title="Edit Produk">
                                            <button class="btn btn-primary" type="button">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </a>
                                        <a href="{{ route('produk.show', $produk->id) }}"
                                            class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                                            data-original-title="Detail Produk">
                                            <button class="btn btn-primary" type="button">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </button>
                                        </a>
                                        <a href="{{ route('produk.destroy', $produk->id) }}"
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
