@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Detail Bill Of Material</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('bom.index') }}">Data Bill Of Materials</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Bill Of Material</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6">
                                <h4>Bill Of Material: {{ $bom->tb_produk->nama_produk }}</h4>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('bom.print', $bom->id) }}" target="_blank"
                                    class="btn btn-success float-end">
                                    <i class="bi bi-printer"></i> Print PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama Produk:</strong> {{ $bom->tb_produk->nama_produk }}</p>
                            <p><strong>Reference:</strong> {{ $bom->reference }}</p>
                            <p><strong>Kuantitas:</strong> {{ $bom->kuantitas }}</p>
                        </div>
                    </div>

                    <hr>

                    <h5>Bahan Baku</h5>
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Nama Bahan Baku</th>
                                <th>Kuantitas</th>
                                <th>Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bom->tb_bom_details as $detail)
                                <tr>
                                    <td>{{ $detail->tb_bahanbaku->nama }}</td>
                                    <td>{{ $detail->kuantitas_bahan }}</td>
                                    <td>{{ $detail->tb_bahanbaku->satuan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('bom.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
