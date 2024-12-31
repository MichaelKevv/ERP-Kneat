@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3 id="judul">Tambah RFQ</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('rfq') }}">Data RFQ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Data RFQ</li>
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
                                <form class="form form-vertical" action="{{ route('rfq.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="nama">Vendor</label>
                                                            <select name="id_vendor" id="id_vendor" class="form-control">
                                                                <option disabled selected>Pilih Vendor</option>
                                                                @foreach ($vendor as $p)
                                                                    <option value="{{ $p->id }}">
                                                                        {{ $p->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-primary float-end my-2"
                                                        onclick="openAddBahanBakuModal()">
                                                        Tambah Bahan Baku
                                                    </button>

                                                    <table class="table mt-3">
                                                        <thead>
                                                            <tr>
                                                                <th>Bahan Baku</th>
                                                                <th>Kuantitas</th>
                                                                <th>Total Harga</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="bahanBakuTable">
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="2" class="text-end"><strong>Total
                                                                        Keseluruhan:</strong></td>
                                                                <td id="totalKeseluruhan">0</td>
                                                                <td></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <!-- Modal untuk menambahkan atau mengedit bahan baku -->
                                <div class="modal fade" id="bahanBakuModal" tabindex="-1" role="dialog"
                                    aria-labelledby="bahanBakuModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="bahanBakuModalLabel">Tambah/Edit Bahan Baku</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="id_bahanbaku">Pilih Bahan Baku</label>
                                                    <select id="id_bahanbaku" name="id_bahanbaku" class="form-control">
                                                        <option disabled selected>Pilih Bahan Baku</option>
                                                        @foreach ($bahan_baku as $bahan)
                                                            <option value="{{ $bahan->id }}"
                                                                data-nama="{{ $bahan->nama }}"
                                                                data-harga="{{ $bahan->harga_beli }}">
                                                                {{ $bahan->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="kuantitas_bahan">Kuantitas</label>
                                                    <input type="number" name="kuantitas_bahan" id="kuantitas_bahan"
                                                        class="form-control" placeholder="Masukkan Kuantitas">
                                                </div>
                                                <input type="hidden" id="editIndex">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="button" class="btn btn-primary" id="saveButton"
                                                    onclick="saveBahanBaku()">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Modal -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('bom_detail_form')
        <script>
            let editMode = false;
            let editRowIndex = null;

            function openAddBahanBakuModal() {
                document.getElementById("bahanBakuModalLabel").innerText = "Tambah Bahan Baku";
                document.getElementById("id_bahanbaku").value = "";
                document.getElementById("kuantitas_bahan").value = "";
                editMode = false;
                $('#bahanBakuModal').modal('show');
            }

            function saveBahanBaku() {
                const bahanBakuSelect = document.getElementById("id_bahanbaku");
                const bahanBakuId = bahanBakuSelect.value;
                const bahanBakuNama = bahanBakuSelect.options[bahanBakuSelect.selectedIndex].getAttribute("data-nama");
                const bahanBakuHarga = parseFloat(bahanBakuSelect.options[bahanBakuSelect.selectedIndex].getAttribute(
                    "data-harga")) || 0;
                const kuantitas = parseFloat(document.getElementById("kuantitas_bahan").value);

                if (!bahanBakuId || !kuantitas || kuantitas <= 0) {
                    alert("Pilih bahan baku dan masukkan kuantitas yang benar.");
                    return;
                }

                const totalHarga = kuantitas * bahanBakuHarga;
                const tableBody = document.getElementById("bahanBakuTable");

                if (editMode) {
                    const row = tableBody.rows[editRowIndex];
                    row.cells[0].innerHTML = `
            <input type="hidden" name="id_bahanbaku[]" value="${bahanBakuId}">
            ${bahanBakuNama}
        `;
                    row.cells[1].innerHTML = `
            <input type="number" name="kuantitas_bahan[]" class="form-control" value="${kuantitas}" min="1" step="0.1" onchange="updateTotal(this)">
        `;
                    row.cells[2].textContent = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).format(totalHarga);
                } else {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                    <input type="hidden" name="total_harga[]" value="${totalHarga}">
            <td>
                <input type="hidden" name="id_bahanbaku[]" value="${bahanBakuId}">
                ${bahanBakuNama}
            </td>
            <td>
                <input type="number" name="kuantitas_bahan[]" class="form-control" value="${kuantitas}" min="1" step="0.1" onchange="updateTotal(this)">
            </td>
            <td>${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(totalHarga)}</td>
            <td>
                <button type="button" class="btn btn-warning btn-sm" onclick="editBahanBaku(this)">Edit</button>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Hapus</button>
            </td>
        `;
                    tableBody.appendChild(row);
                }

                updateTotalKeseluruhan();
                $('#bahanBakuModal').modal('hide');
            }

            function editBahanBaku(button) {
                const row = button.parentElement.parentElement;
                editRowIndex = row.rowIndex - 1;
                const bahanBakuId = row.cells[0].querySelector("input").value;
                const kuantitas = parseFloat(row.cells[1].querySelector("input").value);

                document.getElementById("id_bahanbaku").value = bahanBakuId;
                document.getElementById("kuantitas_bahan").value = kuantitas;
                document.getElementById("bahanBakuModalLabel").innerText = "Edit Bahan Baku";
                editMode = true;

                $('#bahanBakuModal').modal('show');
            }

            function removeRow(button) {
                button.closest("tr").remove();
                updateTotalKeseluruhan();
            }

            function updateTotal(input) {
                const row = input.closest("tr");
                const kuantitas = parseFloat(input.value) || 0;
                const bahanBakuId = row.cells[0].querySelector("input").value;
                const bahanBakuHarga = parseFloat(document.querySelector(`#id_bahanbaku option[value="${bahanBakuId}"]`)
                    .getAttribute("data-harga")) || 0;


                const totalHarga = kuantitas * bahanBakuHarga;
                row.cells[2].textContent = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(totalHarga);
                updateTotalKeseluruhan();
            }


            function updateTotalKeseluruhan() {
                const rows = document.querySelectorAll("#bahanBakuTable tr");
                let totalKeseluruhan = 0;

                rows.forEach(row => {
                    const totalHarga = row.cells[2].textContent.replace(/[^\d,-]/g, ""); // Remove 'Rp' and commas
                    const numericTotalHarga = parseFloat(totalHarga.replace(',', '.')) || 0; // Parse as float
                    totalKeseluruhan += numericTotalHarga;
                });

                document.getElementById("totalKeseluruhan").textContent = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(totalKeseluruhan);
            }
        </script>
    @endpush
@endsection
