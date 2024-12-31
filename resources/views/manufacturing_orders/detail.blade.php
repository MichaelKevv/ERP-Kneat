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
                                                <td class="consumed">
                                                    {{ $reservedData[$material->tb_bahanbaku->id]->consumed ?? 0 }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <button type="button" class="btn btn-success mt-3" id="check-availability-btn"
                                    onclick="checkAvailability()">Check Availability</button>
                                <button type="button" class="btn btn-success mt-3" id="start-production-btn"
                                    style="display: none;" onclick="startProduction()">Mulai Produksi</button>
                                <button type="button" class="btn btn-success mt-3" id="done-btn" style="display: none;"
                                    onclick="done()">Selesai</button>

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
                rows.forEach((row) => {
                    const reservedCell = row.querySelector('.reserved');
                    if (reservedCell) {
                        reservedCell.style.color = 'green';
                    }
                });
                const checkAvailabilityBtn = document.getElementById('check-availability-btn');
                const startProductionBtn = document.getElementById('start-production-btn');
                const doneBtn = document.getElementById('done-btn');
                if (checkAvailabilityBtn) checkAvailabilityBtn.style.display = 'none';
                if (startProductionBtn) startProductionBtn.style.display = 'inline-block';
                if (doneBtn) doneBtn.style.display = 'none';
            } else if (status === 'in_progress') {
                rows.forEach((row) => {
                    const reservedCell = row.querySelector('.reserved');
                    if (reservedCell) {
                        reservedCell.style.color = 'green';
                    }
                    const consumedCell = row.querySelector('.consumed');
                    if (consumedCell) {
                        consumedCell.style.color = 'green';
                    }
                });
                const checkAvailabilityBtn = document.getElementById('check-availability-btn');
                const startProductionBtn = document.getElementById('start-production-btn');
                const doneBtn = document.getElementById('done-btn');
                if (checkAvailabilityBtn) checkAvailabilityBtn.style.display = 'none';
                if (startProductionBtn) startProductionBtn.style.display = 'none';
                if (doneBtn) doneBtn.style.display = 'inline-block';
            } else if (status === 'done') {
                rows.forEach((row) => {
                    const reservedCell = row.querySelector('.reserved');
                    if (reservedCell) {
                        reservedCell.style.color = 'green';
                    }
                    const consumedCell = row.querySelector('.consumed');
                    if (consumedCell) {
                        consumedCell.style.color = 'green';
                    }
                });
                const checkAvailabilityBtn = document.getElementById('check-availability-btn');
                const startProductionBtn = document.getElementById('start-production-btn');
                const doneBtn = document.getElementById('done-btn');
                if (checkAvailabilityBtn) checkAvailabilityBtn.style.display = 'none';
                if (startProductionBtn) startProductionBtn.style.display = 'none';
                if (doneBtn) doneBtn.style.display = 'none';
            }
        });


        function checkAvailability() {
            const manufacturingOrderId = '{{ $manufacturingOrder->id }}';
            const rows = document.querySelectorAll('#bom-table tbody tr');
            const data = [];

            rows.forEach(row => {
                const materialId = row.getAttribute('data-material-id');
                const toConsume = parseFloat(row.cells[2].textContent);
                data.push({
                    materialId,
                    toConsume
                });
            });

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
                    let allReserved = true;
                    rows.forEach((row, index) => {
                        const materialId = row.getAttribute('data-material-id');
                        const reservedCell = row.querySelector('.reserved');
                        const toConsume = data.find(item => item.materialId == materialId)?.toConsume;
                        const available = availability[materialId] || 0;

                        if (toConsume !== undefined) {
                            if (available >= toConsume) {
                                reservedCell.textContent = toConsume;
                                reservedCell.style.color = 'green';
                            } else {
                                reservedCell.textContent = available;
                                reservedCell.style.color = 'red';
                                allReserved = false;
                            }
                        }
                    });

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
                                    text: 'Bahan baku telah tersedia untuk mulai produksi!',
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
            const manufacturingOrderId = '{{ $manufacturingOrder->id }}';
            const rows = document.querySelectorAll('#bom-table tbody tr');
            const data = [];

            rows.forEach(row => {
                const materialId = row.getAttribute('data-material-id');
                const reserved = parseFloat(row.cells[3].textContent); // Reserved column
                const consumed = parseFloat(row.cells[4].textContent) || 0; // Consumed column (default to 0)
                data.push({
                    materialId,
                    reserved,
                    consumed
                });
            });

            fetch('{{ route('start-production') }}', {
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
                    rows.forEach(row => {
                        const materialId = row.getAttribute('data-material-id');
                        const reservedCell = row.querySelector('.reserved');
                        const consumedCell = row.querySelector('.consumed');

                        const materialData = data.find(item => item.materialId == materialId);

                        if (materialData && materialData.reserved > 0) {
                            consumedCell.textContent = materialData
                                .reserved;
                            consumedCell.style.color = 'green'; // Change color to green
                        } else {
                            consumedCell.textContent = 0; // Default to 0 if no reserved quantity
                            consumedCell.style.color = 'red'; // Change color to red if no consumption
                        }
                    });

                    fetch('{{ route('update-status', ['id' => $manufacturingOrder->id]) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                status: 'in_progress',
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Semua bahan yang disediakan telah dikonsumsi untuk produksi!',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                document.getElementById('start-production-btn').style.display = 'none';
                                document.getElementById('check-availability-btn').style.display = 'none';
                                document.getElementById('done-btn').style.display = 'inline-block';
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });

                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function done() {
            const idProduk = '{{ $manufacturingOrder->id_produk }}';
            const quantity = '{{ $manufacturingOrder->kuantitas_produk }}';
            fetch('{{ route('done-production') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        idProduk: idProduk,
                        quantity: quantity
                    })
                })
                .then(response => {
                    fetch('{{ route('update-status', ['id' => $manufacturingOrder->id]) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                status: 'done',
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Semua produk telah selesai diproduksi!',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                document.getElementById('start-production-btn').style.display = 'none';
                                document.getElementById('check-availability-btn').style.display = 'none';
                                document.getElementById('done-btn').style.display = 'none';
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                })
                .catch(error => {
                    console.error('Error:', error);
                });

        }
    </script>
@endsection
