@extends('template')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3 id="judul">Tambah Manufacturing Order</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('manufacturing_orders') }}">Data MO</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah MO</li>
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
                                <form action="{{ route('manufacturing_orders.store') }}" method="POST">
                                    @csrf
                                    <div class="col-12 mb-2">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="id_produk">Produk</label>
                                                    <select name="id_produk" id="id_produk" class="form-control" required
                                                        onchange="loadBOM()">
                                                        <option value="" disabled selected>Pilih Produk</option>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}"
                                                                data-bom="{{ json_encode($product->tb_boms) }}">
                                                                {{ $product->nama_produk }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="quantity_to_produce">Kuantitas Produk yang
                                                        Dihasilkan</label>
                                                    <input type="number" name="kuantitas_produk" id="quantity_to_produce"
                                                        class="form-control" min="1" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="id_bom">Nama BoM</label>
                                                    <select name="id_bom" id="id_bom" class="form-control" required
                                                        onchange="loadBOMDetails()">
                                                        <option value="" disabled selected>Pilih BoM</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="tanggal_produksi">Tanggal MO</label>
                                                    <input type="date" name="tanggal_produksi" id="tanggal_produksi"
                                                        class="form-control" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tanggal_deadline">Tanggal Deadline</label>
                                                    <input type="date" name="tanggal_deadline" id="tanggal_deadline"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h3>Bill of Materials (BoM)</h3>
                                    <table class="table" id="bom-table">
                                        <thead>
                                            <tr>
                                                <th>Nama Bahan Baku</th>
                                                <th>Satuan</th>
                                                <th>To Consume</th>
                                                <th>Reserved</th>
                                                <th>Consumed</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>

                                    <button type="submit" class="btn btn-success mt-3 float-end">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        function loadBOM() {
            const productSelect = document.getElementById('id_produk');
            const selectedProduct = productSelect.options[productSelect.selectedIndex];
            const bomsData = JSON.parse(selectedProduct.getAttribute('data-bom') || '[]');
            const bomSelect = document.getElementById('id_bom');

            bomSelect.innerHTML = '<option value="" disabled selected>Pilih BoM</option>';

            bomsData.forEach(bom => {
                const option = document.createElement('option');
                option.value = bom.id;
                option.textContent = bom.reference;
                option.setAttribute('data-details', JSON.stringify(bom.tb_bom_details));
                bomSelect.appendChild(option);
            });
        }

        function loadBOMDetails() {
            const bomSelect = document.getElementById('id_bom');
            const selectedBOM = bomSelect.options[bomSelect.selectedIndex];
            const bomDetails = JSON.parse(selectedBOM.getAttribute('data-details') || '[]');
            const bomTableBody = document.getElementById('bom-table').getElementsByTagName('tbody')[0];
            const quantityProduce = document.getElementById('quantity_to_produce').value;


            bomTableBody.innerHTML = ''; // Clear existing rows

            // Populate BoM table with details
            bomDetails.forEach(material => {
                const row = document.createElement('tr');

                const nameCell = document.createElement('td');
                nameCell.textContent = '[' + material.tb_bahanbaku.barcode + '] ' + material.tb_bahanbaku.nama;
                row.appendChild(nameCell);

                const satuanCell = document.createElement('td');
                satuanCell.textContent = material.tb_bahanbaku.satuan;
                row.appendChild(satuanCell);

                const toConsumeCell = document.createElement('td');
                toConsumeCell.textContent = (material.kuantitas_bahan * Number(quantityProduce)).toFixed(
                    2);
                row.appendChild(toConsumeCell);

                const reservedCell = document.createElement('td');
                reservedCell.textContent = '0';
                row.appendChild(reservedCell);

                const consumedCell = document.createElement('td');
                consumedCell.textContent = '0';
                row.appendChild(consumedCell);

                bomTableBody.appendChild(row);
            });
        }
    </script>
@endsection
