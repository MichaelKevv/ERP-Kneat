@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Data Bahan Baku</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('bahan_baku') }}">Data Bahan Baku</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Data Bahan Baku</li>
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
                            <div class="card-body">
                                <form class="form form-vertical" action="{{ route('bahan_baku.update', $bahan_baku->id) }}"
                                    method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="file-upload" class="form-label">Foto
                                                    </label>
                                                    <div id="drop-area" class="drop-area">
                                                        <p>Drag & Drop your file here or click to select</p>
                                                        <input type="file" id="file-upload" name="foto"
                                                            class="form-control-file" style="display:none;">
                                                    </div>
                                                    <img src="{{ asset('storage/foto-bahan-baku/' . $bahan_baku->foto) }}"
                                                        alt="{{ $bahan_baku->nama }}"
                                                        style="max-width: 200px; margin-top: 10px;">
                                                    <div id="preview-container" class="preview-container"
                                                        style="display:none;">
                                                        <p>Preview:</p>
                                                        <img id="file-preview" src="#" alt="Preview Image"
                                                            class="img-fluid" style="max-width: 200px; margin-top: 10px;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="nama">Nama</label>
                                                    <input type="text" id="nama" class="form-control"
                                                        placeholder="Masukkan Nama" name="nama"
                                                        value="{{ $bahan_baku->nama }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="internal_reference">Internal Reference</label>
                                                            <input type="text" id="internal_reference"
                                                                class="form-control"
                                                                value="{{ $bahan_baku->internal_reference }}"
                                                                placeholder="Masukkan Internal Reference"
                                                                name="internal_reference">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="barcode">Barcode</label>
                                                            <input type="text" id="barcode" class="form-control"
                                                                value="{{ $bahan_baku->barcode }}"
                                                                placeholder="Masukkan Barcode" name="barcode" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="harga_jual">Harga Beli</label>
                                                            <input type="text" id="harga_jual" class="form-control"
                                                                value="{{ $bahan_baku->harga_beli }}"
                                                                placeholder="Masukkan Harga Beli" name="harga_beli"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="satuan">Satuan</label>
                                                            <select id="satuan" class="form-control" name="satuan">
                                                                <option value="" disabled
                                                                    {{ !$bahan_baku->satuan ? 'selected' : '' }}>Pilih Satuan
                                                                </option>
                                                                <option value="gram"
                                                                    {{ $bahan_baku->satuan == 'gram' ? 'selected' : '' }}>Gram
                                                                </option>
                                                                <option value="kilogram"
                                                                    {{ $bahan_baku->satuan == 'kilogram' ? 'selected' : '' }}>
                                                                    Kilogram</option>
                                                                <option value="liter"
                                                                    {{ $bahan_baku->satuan == 'liter' ? 'selected' : '' }}>
                                                                    Liter</option>
                                                                <option value="mililiter"
                                                                    {{ $bahan_baku->satuan == 'mililiter' ? 'selected' : '' }}>
                                                                    Mililiter</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="notes">Notes</label>
                                                    <textarea name="note" id="txtarea" cols="30" rows="10">{{ $bahan_baku->note }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
