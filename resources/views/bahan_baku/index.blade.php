@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Bahan Baku</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Bahan Baku</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('bahan_baku/create') }}"><button class="btn btn-success">Tambah Data</button></a>
                    <a href="" target="_blank"><button class="btn btn-success float-end">Export
                            PDF</button></a>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Barcode</th>
                                <th>Internal Ref</th>
                                <th>Nama Bahan Baku</th>
                                <th>Harga Beli</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $bahan_baku)
                                <tr>
                                    <td>{{ $bahan_baku->barcode }}</td>
                                    <td>{{ $bahan_baku->internal_reference }}</td>
                                    <td>{{ $bahan_baku->nama }}</td>
                                    <td>Rp{{ number_format($bahan_baku->harga_beli, 2) }}</td>
                                    <td>
                                        @if (!$bahan_baku->foto)
                                            <img src="{{ asset('images/img-not-found.png') }}"
                                                width="100px">
                                        @else
                                            <img src="{{ url('storage/foto-bahan_baku/' . $bahan_baku->foto) }}"
                                                width="100px">
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('bahan_baku.edit', $bahan_baku->id) }}"
                                            class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                                            data-original-title="Edit Bahan Baku">
                                            <button class="btn btn-primary" type="button">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </a>
                                        <a href="{{ route('bahan_baku.show', $bahan_baku->id) }}"
                                            class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                                            data-original-title="Detail Bahan Baku">
                                            <button class="btn btn-primary" type="button">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </button>
                                        </a>
                                        <a href="{{ route('bahan_baku.destroy', $bahan_baku->id) }}"
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
