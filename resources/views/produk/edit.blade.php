@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Data Produk</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('produk') }}">Data Produk</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Data Produk</li>
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
                                <form class="form form-vertical" action="{{ route('produk.update', $produk->id) }}"
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
                                                    <img src="{{ asset('storage/foto-produk/' . $produk->foto) }}"
                                                        alt="{{ $produk->nama }}"
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
                                                        placeholder="Masukkan Nama" name="nama_produk"
                                                        value="{{ $produk->nama_produk }}" required>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="email-id-vertical">Kategori</label>
                                                    <select class="form-control" name="id_kategori" id="kategori" required>
                                                        <option value="" selected>Pilih Kategori</option>
                                                        @foreach ($kategori as $k)
                                                            <option value="{{ $k->id }}"
                                                                {{ $k->id == $produk->id_kategori ? 'selected' : '' }}>
                                                                {{ $k->nama_kategori }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="internal_reference">Internal Reference</label>
                                                            <input type="text" id="internal_reference"
                                                                class="form-control"
                                                                value="{{ $produk->internal_reference }}"
                                                                placeholder="Masukkan Internal Reference"
                                                                name="internal_reference">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="barcode">Barcode</label>
                                                            <input type="text" id="barcode" class="form-control"
                                                                value="{{ $produk->barcode }}"
                                                                placeholder="Masukkan Barcode" name="barcode" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="exp">Expired</label>
                                                            <input type="text" id="exp" class="form-control"
                                                                value="{{ $produk->exp }}" placeholder="Masukkan Expired"
                                                                name="exp" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="satuan">Satuan</label>
                                                            <input type="text" id="satuan" value="{{ $produk->satuan }}" class="form-control"
                                                                placeholder="Masukkan Satuan" name="satuan">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="harga_jual">Harga Jual Produk</label>
                                                            <input type="text" id="harga_jual" class="form-control"
                                                                value="{{ $produk->harga_jual }}"
                                                                placeholder="Masukkan Harga Jual" name="harga_jual"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="pajak">Pajak</label>
                                                            <input type="text" id="pajak" class="form-control"
                                                                value="{{ $produk->pajak }}" placeholder="Masukkan Pajak"
                                                                name="pajak">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="biaya_produk">Biaya Produk</label>
                                                            <input type="text" id="biaya_produk" class="form-control"
                                                                value="{{ $produk->biaya_produk }}"
                                                                placeholder="Masukkan Biaya Produk" name="biaya_produk"
                                                                required>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="notes">Notes</label>
                                                    <textarea name="note" id="txtarea" cols="30" rows="10">{{ $produk->note }}</textarea>
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
