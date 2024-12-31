@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Data Bill of Material</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('bom') }}">Data Bill of Material</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Data Bill of Material</li>
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
                                <form action="{{ route('bom.update', $bom->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <!-- Select Produk -->
                                    <div class="form-group">
                                        <label for="id_produk">Produk</label>
                                        <select name="id_produk" id="id_produk" class="form-control">
                                            <option disabled>Pilih Produk</option>
                                            @foreach ($produk as $p)
                                                <option value="{{ $p->id }}"
                                                    {{ old('id_produk', $bom->id_produk) == $p->id ? 'selected' : '' }}>
                                                    {{ $p->nama_produk }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Reference -->
                                    <div class="form-group">
                                        <label for="reference">Reference</label>
                                        <input type="text" name="reference" id="reference" class="form-control"
                                            value="{{ old('reference', $bom->reference) }}"
                                            placeholder="Masukkan Reference">
                                    </div>

                                    <!-- Tabel Edit Bill of Material -->
                                    <h4>Bill of Material</h4>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Bill of Material</th>
                                                <th>Kuantitas</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bahan-baku-table">
                                            @foreach ($bom->tb_bom_details as $detail)
                                                <tr>
                                                    <td>
                                                        <select name="bahan_baku[{{ $detail->id }}][id_bahanbaku]"
                                                            class="form-control">
                                                            @foreach ($bahanBaku as $bb)
                                                                <option value="{{ $bb->id }}"
                                                                    {{ old("bahan_baku.{$detail->id}.id_bahanbaku", $detail->id_bahanbaku) == $bb->id ? 'selected' : '' }}>
                                                                    {{ $bb->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            name="bahan_baku[{{ $detail->id }}][kuantitas_bahan]"
                                                            class="form-control" step="0.01"
                                                            value="{{ old("bahan_baku.{$detail->id}.kuantitas_bahan", $detail->kuantitas_bahan) }}">
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-danger remove-row">Hapus</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <button type="button" class="btn btn-secondary" id="add-bahan-baku">Tambah Bahan
                                        Baku</button>

                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary float-end">Update BOM</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        const bahanBakuOptions = `
            @foreach ($bahanBaku as $bb)
                <option value="{{ $bb->id }}">{{ $bb->nama }}</option>
            @endforeach
        `;

        document.getElementById('add-bahan-baku').addEventListener('click', function() {
            const newIndex = document.querySelectorAll('#new-bahan-baku .form-group').length / 2;
            const table = document.getElementById('bahan-baku-table');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td>
                    <select name="new_bahan_baku[${newIndex}][id_bahanbaku]" class="form-control">
                        ${bahanBakuOptions}
                    </select>
                </td>
                <td>
                    <input type="number" name="new_bahan_baku[${newIndex}][kuantitas_bahan]" class="form-control" value="1">
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-row">Hapus</button>
                </td>
            `;
            table.appendChild(newRow);
        });

        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
@endsection
