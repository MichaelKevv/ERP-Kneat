@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>BOM Structure & Cost</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('bom.index') }}">Data Bill Of Material</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail BOM Structure & Cost</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('bom.print', $bom->id) }}" target="_blank" class="btn btn-success float-end">
                        <i class="bi bi-printer"></i> Print PDF
                    </a>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Nama Produk:</strong> {{ $bom->tb_produk->nama_produk }}</p>
                            <p><strong>Reference:</strong> {{ $bom->reference }}</p>
                            <p><strong>Kuantitas:</strong> {{ $bom->kuantitas }}</p>
                        </div>
                    </div>

                    <hr>

                    <h5>BoM Structure & Cost</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>BoM</th>
                                <th>Kuantitas</th>
                                <th>Satuan</th>
                                <th>Product Cost</th>
                                <th>BoM Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Main product row -->
                            <tr>
                                <td>[{{ $bom->tb_produk->barcode }}] {{ $bom->tb_produk->nama_produk }}</td>
                                <td>[{{ $bom->reference }}] {{ $bom->tb_produk->nama_produk }}</td>
                                <td>{{ number_format($bom->kuantitas, 2) }}</td>
                                <td>{{ $bom->tb_produk->satuan }}</td>
                                <td>Rp{{ number_format($bom->tb_produk->biaya_produk, 2) }}</td>
                                <td>Rp{{ number_format(
                                    $bom->kuantitas *
                                        $bom->tb_bom_details->sum(function ($detail) {
                                            return ($detail->tb_bahanbaku->satuan == 'gram'
                                                ? $detail->kuantitas_bahan / 1000
                                                : ($detail->tb_bahanbaku->satuan == 'mililiter'
                                                    ? $detail->kuantitas_bahan / 1000
                                                    : $detail->kuantitas_bahan)) * $detail->tb_bahanbaku->harga_beli;
                                        }),
                                    2,
                                ) }}
                                </td>
                            </tr>

                            @php
                                $totalCost = 0;
                            @endphp
                            @foreach ($bom->tb_bom_details as $detail)
                                @php
                                    $materialCost =
                                        ($detail->tb_bahanbaku->satuan == 'gram'
                                            ? $detail->kuantitas_bahan / 1000
                                            : ($detail->tb_bahanbaku->satuan == 'mililiter'
                                                ? $detail->kuantitas_bahan / 1000
                                                : $detail->kuantitas_bahan)) * $detail->tb_bahanbaku->harga_beli;
                                    $totalCost += $materialCost;
                                @endphp
                                <tr>
                                    <td style="padding-left: 30px;">[{{ $detail->tb_bahanbaku->barcode }}]
                                        {{ $detail->tb_bahanbaku->nama }}</td>
                                    <td></td>
                                    <td>{{ number_format($detail->kuantitas_bahan, 2) }}</td>
                                    <td>{{ $detail->tb_bahanbaku->satuan }}</td>
                                    <td>Rp{{ number_format($detail->tb_bahanbaku->harga_beli, 2) }}</td>
                                    <td>Rp{{ number_format($materialCost, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"></td>
                                <td><strong>Unit Cost</strong></td>
                                <td></td>
                                <td><strong>Rp{{ number_format($bom->tb_produk->biaya_produk, 2) }}</strong></td>
                                <td><strong>Rp{{ number_format($totalCost, 2) }}</strong></td>
                            </tr>
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
