@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Data RFQ</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('rfq') }}">Data RFQ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Data RFQ</li>
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
                                <form action="{{ route('rfq.update', $rfq->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label for="id_vendor">Vendor</label>
                                        <select name="id_vendor" id="id_vendor" class="form-control">
                                            <option disabled>Pilih Vendor</option>
                                            @foreach ($vendor as $p)
                                                <option value="{{ $p->id }}"
                                                    {{ old('id_vendor', $rfq->id_vendor) == $p->id ? 'selected' : '' }}>
                                                    {{ $p->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <h4>Bahan Baku</h4>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Bahan Baku</th>
                                                <th>Kuantitas</th>
                                                <th>Total Harga</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bahan-baku-table">
                                            @foreach ($rfq->tb_rfq_details as $detail)
                                                <tr>
                                                    <td>
                                                        <select name="bahan_baku[{{ $detail->id }}][id_bahanbaku]"
                                                            class="form-control bahan-baku-select">
                                                            @foreach ($bahanBaku as $bb)
                                                                <option value="{{ $bb->id }}"
                                                                    {{ old("bahan_baku.{$detail->id}.id_bahanbaku", $detail->id_bahanbaku) == $bb->id ? 'selected' : '' }}
                                                                    data-harga="{{ $bb->harga_beli }}">
                                                                    {{ $bb->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            name="bahan_baku[{{ $detail->id }}][kuantitas_bahan]"
                                                            class="form-control kuantitas-bahan" step="0.01"
                                                            value="{{ old("bahan_baku.{$detail->id}.kuantitas", $detail->kuantitas) }}">
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            name="bahan_baku[{{ $detail->id }}][total_harga]"
                                                            class="form-control total-harga"
                                                            value="{{ old("bahan_baku.{$detail->id}.total", $detail->total) }}"
                                                            readonly>
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-danger remove-row">Hapus</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2" class="text-end"><strong>Total
                                                        Keseluruhan:</strong></td>
                                                <td id="total-keseluruhan">0</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    <button type="button" class="btn btn-secondary" id="add-bahan-baku">Tambah Bahan
                                        Baku</button>

                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn-primary float-end">Update RFQ</button>
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
                <option value="{{ $bb->id }}" data-harga="{{ $bb->harga_beli }}">{{ $bb->nama }}</option>
            @endforeach
        `;

        document.addEventListener('DOMContentLoaded', function() {
            // Function to update total price when quantity is changed
            function updateTotalHarga(input) {
                const row = input.closest('tr');
                const kuantitas = parseFloat(input.value);
                const select = row.querySelector('.bahan-baku-select');
                const harga = parseFloat(select.selectedOptions[0].getAttribute('data-harga'));
                const totalHarga = kuantitas * harga;

                // Update the total harga for this row
                row.querySelector('.total-harga').value = totalHarga.toFixed(2);
                updateTotalKeseluruhan(); // Update the overall total
            }

            // Function to update total keseluruhan
            function updateTotalKeseluruhan() {
                let totalKeseluruhan = 0;
                const rows = document.querySelectorAll('#bahan-baku-table tr');
                rows.forEach(row => {
                    const totalHarga = parseFloat(row.querySelector('.total-harga').value) || 0;
                    totalKeseluruhan += totalHarga;
                });
                document.getElementById('total-keseluruhan').textContent = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(totalKeseluruhan); // Format as currency
            }

            // Event listener for when quantity is changed
            document.addEventListener('input', function(e) {
                if (e.target && e.target.classList.contains('kuantitas-bahan')) {
                    updateTotalHarga(e.target);
                }
            });

            // Update total harga for existing bahan baku when the page loads
            const existingRows = document.querySelectorAll('#bahan-baku-table tr');
            existingRows.forEach(row => {
                const kuantitasInput = row.querySelector('.kuantitas-bahan');
                if (kuantitasInput) {
                    updateTotalHarga(
                        kuantitasInput); // Update the price immediately based on the initial quantity
                }
            });

            // Add new row for bahan baku
            document.getElementById('add-bahan-baku').addEventListener('click', function() {
                const newIndex = document.querySelectorAll('#bahan-baku-table .form-group').length / 2;
                const table = document.getElementById('bahan-baku-table');
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
            <td>
                <select name="new_bahan_baku[${newIndex}][id_bahanbaku]" class="form-control bahan-baku-select">
                    ${bahanBakuOptions}
                </select>
            </td>
            <td>
                <input type="number" name="new_bahan_baku[${newIndex}][kuantitas_bahan]" class="form-control kuantitas-bahan" step="0.01" value="1">
            </td>
            <td>
                <input type="number" name="new_bahan_baku[${newIndex}][total_harga]" class="form-control total-harga" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-danger remove-row">Hapus</button>
            </td>
        `;
                table.appendChild(newRow);
                updateTotalHarga(newRow.querySelector(
                    '.kuantitas-bahan')); // Update total for the new row immediately
                updateTotalKeseluruhan(); // Update the total when a new row is added
            });

            // Remove row
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-row')) {
                    e.target.closest('tr').remove();
                    updateTotalKeseluruhan(); // Update total when row is removed
                }
            });

            // Initial call to update total when the page loads
            updateTotalKeseluruhan();
        });
    </script>
@endsection
