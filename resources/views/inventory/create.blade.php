@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3 id="judul">Tambah Vendor</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('vendors') }}">Data Vendor</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Data Vendor</li>
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
                                <form class="form form-vertical" action="{{ route('vendors.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
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
                                                    <label for="nama">Tipe Vendor</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="tipe_vendor"
                                                            id="perusahaan" value="Perusahaan">
                                                        <label class="form-check-label" for="perusahaan">
                                                            Perusahaan
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="tipe_vendor"
                                                            id="perorangan" value="Perorangan">
                                                        <label class="form-check-label" for="perorangan">
                                                            Perorangan
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="nama">Nama Vendor</label>
                                                            <input type="text" id="nama" class="form-control"
                                                                placeholder="Masukkan Nama Vendor" name="nama" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="alamat">Alamat</label>
                                                            <input type="text" id="alamat" class="form-control"
                                                                placeholder="Masukkan Alamat" name="alamat" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12" id="company_npwp">
                                                        <div class="form-group">
                                                            <label for="npwp">NPWP</label>
                                                            <input type="text" id="npwp" class="form-control"
                                                                placeholder="Masukkan NPWP" name="npwp">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="no_hp">Nomor HP</label>
                                                            <input type="text" id="no_hp" class="form-control"
                                                                placeholder="Masukkan Nomor HP" name="no_hp" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input type="text" id="email" class="form-control"
                                                                placeholder="Masukkan Email" name="email" required>
                                                        </div>
                                                    </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const companyNpwp = document.getElementById('company_npwp');
            const radioButtons = document.querySelectorAll('input[name="tipe_vendor"]');

            radioButtons.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.value === 'Perusahaan') {
                        companyNpwp.style.display = 'block';
                    } else {
                        companyNpwp.style.display = 'none';
                    }
                });
            });

            if (document.querySelector('input[name="tipe_vendor"]:checked')?.value === 'Perusahaan') {
                companyNpwp.style.display = 'block';
            } else {
                companyNpwp.style.display = 'none';
            }
        });
    </script>
@endsection
