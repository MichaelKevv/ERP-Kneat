@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Detail RFQ</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('rfq') }}">Data RFQ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail RFQ</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6">
                                <h4>{{ $rfq->kode_rfq }}</h4>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    {{-- <a href="{{ route('rfq.print', $rfq->id) }}" target="_blank"
                                        class="btn btn-success me-2">
                                        <i class="bi bi-printer"></i> Print PDF
                                    </a> --}}
                                    <button type="button" class="btn btn-success me-2" id="send-rfq-btn"
                                        onclick="sendRfq()">Send RFQ</button>
                                    <button type="button" class="btn btn-success" id="approve-rfq-btn"
                                        onclick="approveRfq()" style="display: none;">Approve RFQ</button>
                                    <button type="button" class="btn btn-success" id="po-rfq-btn" onclick="purchaseOrder()"
                                        style="display: none;">Create Purchase Order</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nama Vendor:</strong> {{ $rfq->tb_vendor->nama }}</p>
                        </div>
                    </div>

                    <hr>

                    <h5>Bahan Baku</h5>
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Nama Bahan Baku</th>
                                <th>Kuantitas</th>
                                <th>Satuan</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rfq->tb_rfq_details as $detail)
                                <tr>
                                    <td>{{ $detail->tb_bahanbaku->nama }}</td>
                                    <td>{{ $detail->kuantitas }}</td>
                                    <td>{{ $detail->tb_bahanbaku->satuan }}</td>
                                    <td>Rp{{ number_format($detail->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end mt-3">
                        <a href="{{ route('rfq.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const status = '{{ $rfq->status }}';
            if (status === 'rfq_sent') {
                const RfqSentBtn = document.getElementById('send-rfq-btn');
                const RfqApprovedBtn = document.getElementById('approve-rfq-btn');
                const poBtn = document.getElementById('po-rfq-btn');
                if (RfqSentBtn) RfqSentBtn.style.display = 'none';
                if (RfqApprovedBtn) RfqApprovedBtn.style.display = 'inline-block';
                if (poBtn) poBtn.style.display = 'none';
            } else if (status === 'rfq_approved') {
                const RfqSentBtn = document.getElementById('send-rfq-btn');
                const RfqApprovedBtn = document.getElementById('approve-rfq-btn');
                const poBtn = document.getElementById('po-rfq-btn');
                if (RfqSentBtn) RfqSentBtn.style.display = 'none';
                if (RfqApprovedBtn) RfqApprovedBtn.style.display = 'none';
                if (poBtn) poBtn.style.display = 'inline-block';
            } else if (status === 'purchase_order') {
                const RfqSentBtn = document.getElementById('send-rfq-btn');
                const RfqApprovedBtn = document.getElementById('approve-rfq-btn');
                const poBtn = document.getElementById('po-rfq-btn');
                if (RfqSentBtn) RfqSentBtn.style.display = 'none';
                if (RfqApprovedBtn) RfqApprovedBtn.style.display = 'none';
                if (poBtn) poBtn.style.display = 'none';
            }
        });

        function sendRfq() {
            fetch('{{ route('update-status.rfq', ['id' => $rfq->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        status: 'rfq_sent'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'RFQ telah dikirim ke Vendor',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        document.getElementById('send-rfq-btn').style.display = 'none';
                        document.getElementById('approve-rfq-btn').style.display = 'inline-block';
                        document.getElementById('po-rfq-btn').style.display = 'none';
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function approveRfq() {
            fetch('{{ route('update-status.rfq', ['id' => $rfq->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        status: 'rfq_approved'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'RFQ telah diverifikasi dan disetujui oleh Vendor',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        document.getElementById('send-rfq-btn').style.display = 'none';
                        document.getElementById('approve-rfq-btn').style.display = 'none';
                        document.getElementById('po-rfq-btn').style.display = 'inline-block';
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function purchaseOrder() {
            fetch('{{ route('update-status.rfq', ['id' => $rfq->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        status: 'purchase_order'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Purchase Order dilakukan!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        document.getElementById('send-rfq-btn').style.display = 'none';
                        document.getElementById('approve-rfq-btn').style.display = 'none';
                        document.getElementById('po-rfq-btn').style.display = 'none';

                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
@endsection
