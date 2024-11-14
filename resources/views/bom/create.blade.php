@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3 id="judul">Tambah Bill of Material</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('bom') }}">Data Bill of Material</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Data Bill of Material</li>
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
                                <form class="form form-vertical" action="{{ route('bom.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="nama">Produk</label>
                                                            <select name="id_produk" id="id_produk" class="form-control">
                                                                <option disabled selected>Pilih Produk</option>
                                                                @foreach ($produk as $p)
                                                                    <option value="{{ $p->id }}">
                                                                        {{ $p->nama_produk }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="internal_reference">Reference</label>
                                                            <input type="text" id="reference" class="form-control"
                                                                placeholder="Masukkan Reference" name="reference">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="kuantitas">Kuantitas</label>
                                                            <input type="number" value="1" readonly id="kuantitas"
                                                                class="form-control" placeholder="Masukkan Kuantitas"
                                                                name="kuantitas">
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
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="bahanBakuTable">
                                                        </tbody>
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
                                                                data-nama="{{ $bahan->nama }}">
                                                                {{ $bahan->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="kuantitas_bahan">Kuantitas</label>
                                                    <input type="number" name="kuantitas_bahan" id="kuantitas_bahan" class="form-control"
                                                        placeholder="Masukkan Kuantitas">
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
                // Reset modal to "Tambah" mode
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
                const kuantitas = document.getElementById("kuantitas_bahan").value;

                if (!bahanBakuId || !kuantitas || kuantitas <= 0) {
                    alert("Pilih bahan baku dan masukkan kuantitas yang benar.");
                    return;
                }

                const tableBody = document.getElementById("bahanBakuTable");

                if (editMode) {
                    // Update row in edit mode
                    const row = tableBody.rows[editRowIndex];
                    row.cells[0].innerHTML = `
                    <input type="hidden" name="id_bahanbaku[]" value="${bahanBakuId}">
                    ${bahanBakuNama}
                `;
                    row.cells[1].innerHTML = `
                    <input type="number" name="kuantitas_bahan[]" class="form-control" value="${kuantitas}" min="1" step="0.1">
                `;
                } else {
                    // Add new row in add mode
                    const row = document.createElement("tr");
                    row.innerHTML = `
                    <td>
                        <input type="hidden" name="id_bahanbaku[]" value="${bahanBakuId}">
                        ${bahanBakuNama}
                    </td>
                    <td>
                        <input type="number" name="kuantitas_bahan[]" class="form-control" value="${kuantitas}" min="1" step="0.1">
                    </td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" onclick="editBahanBaku(this)">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Hapus</button>
                    </td>
                `;
                    tableBody.appendChild(row);
                }

                $('#bahanBakuModal').modal('hide');
            }

            function editBahanBaku(button) {
                const row = button.parentElement.parentElement;
                editRowIndex = row.rowIndex - 1;
                const bahanBakuId = row.cells[0].querySelector("input").value;
                const kuantitas = row.cells[1].querySelector("input").value;

                document.getElementById("id_bahanbaku").value = bahanBakuId;
                document.getElementById("kuantitas_bahan").value = kuantitas;
                document.getElementById("bahanBakuModalLabel").innerText = "Edit Bahan Baku";
                editMode = true;

                $('#bahanBakuModal').modal('show');
            }

            function removeRow(button) {
                button.closest("tr").remove();
            }
        </script>
    @endpush
@endsection
