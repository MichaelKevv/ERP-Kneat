@extends('template')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3 id="judul">Detail Manufacturing Order</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('manufacturing_orders') }}">Data MO</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail MO</li>
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
                                <div class="col-12 mb-2">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="id_produk">Produk :
                                                    {{ $manufacturingOrder->tb_produk->nama_produk }}</label>
                                            </div>

                                            <div class="form-group">
                                                <label for="quantity_to_produce">Kuantitas Produk yang
                                                    Dihasilkan : {{ $manufacturingOrder->kuantitas_produk }}</label>
                                                <p></p>
                                            </div>

                                            <div class="form-group">
                                                <label for="id_bom">Nama BoM :
                                                    {{ $manufacturingOrder->tb_bom->reference }} </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="tanggal_produksi">Tanggal MO :
                                                    {{ $manufacturingOrder->tanggal_produksi }}</label>
                                            </div>
                                            <div class="form-group">
                                                <label for="tanggal_deadline">Tanggal Deadline :
                                                    {{ $manufacturingOrder->tanggal_deadline }}</label>
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
                                    <tbody>
                                        @foreach ($bomDetails as $material)
                                            <tr data-material-id="{{ $material->tb_bahanbaku->id }}">
                                                <td>{{ '[' . $material->tb_bahanbaku->barcode . '] ' . $material->tb_bahanbaku->nama }}
                                                </td>
                                                <td>{{ $material->tb_bahanbaku->satuan }}</td>
                                                <td>{{ $material->kuantitas_bahan * $manufacturingOrder->kuantitas_produk }}
                                                </td>
                                                <td class="reserved">
                                                    {{ $reservedData[$material->tb_bahanbaku->id]->reserved ?? 0 }}
                                                </td>
                                                <td>0</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <button type="button" class="btn btn-primary mt-3" id="check-availability-btn"
                                    onclick="checkAvailability()">Check Availability</button>
                                <button type="button" class="btn btn-success mt-3" id="start-production-btn"
                                    style="display: none;" onclick="startProduction()">Mulai Produksi</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const status = '{{ $manufacturingOrder->status }}';
            const rows = document.querySelectorAll('#bom-table tbody tr');

            if (status === 'confirmed') {
                // Ambil data reserved langsung dari atribut data-reserved pada baris tabel
                rows.forEach((row) => {
                    const reservedCell = row.querySelector('.reserved');
                    reservedCell.style.color = 'green';
                });
                document.getElementById('check-availability-btn').style.display = 'none';
                document.getElementById('start-production-btn').style.display = 'inline-block';
            }
        });

        function checkAvailability() {
            // Gather the material IDs and quantities to consume
            const manufacturingOrderId = '{{ $manufacturingOrder->id }}';
            const rows = document.querySelectorAll('#bom-table tbody tr');
            const data = [];

            rows.forEach(row => {
                const materialId = row.getAttribute('data-material-id'); // Get material ID from data attribute
                const toConsume = parseFloat(row.cells[2].textContent); // Get 'To Consume' value
                data.push({
                    materialId,
                    toConsume
                });
            });

            // Send the data to the controller via an Ajax request
            fetch('{{ route('check-availability') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        manufacturingOrderId: manufacturingOrderId,
                        materials: data
                    })
                })
                .then(response => response.json())
                .then(availability => {
                    let allReserved = true; // Flag to check if all materials are reserved
                    // Update the reserved cells based on the returned availability data
                    rows.forEach((row, index) => {
                        const materialId = row.getAttribute('data-material-id');
                        const reservedCell = row.querySelector('.reserved');
                        const toConsume = data.find(item => item.materialId == materialId)?.toConsume;
                        const available = availability[materialId] || 0;

                        if (toConsume !== undefined) {
                            if (available >= toConsume) {
                                reservedCell.textContent = toConsume;
                                reservedCell.style.color = 'green'; // Set text color to green
                            } else {
                                reservedCell.textContent = available;
                                reservedCell.style.color = 'red'; // Set text color to red
                                allReserved = false; // If any material is not fully reserved
                            }
                        }
                    });

                    // If all materials are reserved, show the "Mulai Produksi" button
                    if (allReserved) {
                        document.getElementById('start-production-btn').style.display = 'inline-block';
                        document.getElementById('check-availability-btn').style.display = 'none';
                        fetch('{{ route('update-status', ['id' => $manufacturingOrder->id]) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: JSON.stringify({
                                    status: 'confirmed'
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Bahan baku telah tersedia untuk produksi!',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    document.getElementById('start-production-btn').style.display =
                                        'inline-block';
                                });
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    } else {
                        document.getElementById('start-production-btn').style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function startProduction() {
            // Send a request to the server to change the status to 'in_progress'
            fetch('{{ route('update-status', ['id' => $manufacturingOrder->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        status: 'in_progress'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // After the status update, change the button visibility and alert
                    alert('Production has started!');
                    document.getElementById('start-production-btn').style.display = 'none';
                    // Optionally, you can also update the status visually on the page
                    document.getElementById('status-label').textContent = 'Status: In Progress';
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
@endsection
