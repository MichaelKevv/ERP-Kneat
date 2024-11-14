@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Detail Produk</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('bahan_baku') }}">Data Produk</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Produk</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section id="basic-vertical-layouts">
            <div class="row match-height">
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>{{ $bahan_baku->nama }}</h5>
                                        </div>
                                        <div class="col-6">
                                            <h5 class="float-end">Rp{{ number_format($bahan_baku->harga_beli, 2) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-12">
                                            @if (!$bahan_baku->foto)
                                                <img src="{{ asset('images/img-not-found.png') }}" width="100px">
                                            @else
                                                <img src="{{ url('storage/foto-bahan_baku/' . $bahan_baku->foto) }}"
                                                    width="100px">
                                            @endif

                                            <div class="float-end">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#barcodeModal">
                                                    <button class="btn btn-success">Lihat Barcode</button>
                                                </a>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#qrCodeModal">
                                                    <button class="btn btn-success">Lihat QrCode</button>
                                                </a>
                                            </div>
                                        </div>

                                        <h5 class="mt-3">Detail :</h5>

                                        <div class="col-md-6">
                                            <div class="row">
                                                <p>Internal Reference :
                                                    {{ $bahan_baku->internal_reference }}
                                                </p>
                                                <p>Barcode :
                                                    {{ $bahan_baku->barcode }}
                                                </p>
                                                <p>Satuan :
                                                    {{ $bahan_baku->satuan }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <p> Notes :</p>
                                            <p>{!! $bahan_baku->notes !!}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Barcode -->
    <div class="modal fade" id="barcodeModal" tabindex="-1" aria-labelledby="barcodeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="barcodeModalLabel">Barcode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    {!! $barcode !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal QR Code -->
    <div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrCodeModalLabel">QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    {!! QrCode::size(200)->generate($bahan_baku->barcode) !!}
                </div>
            </div>
        </div>
    </div>
@endsection