@extends('template')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data RFQ</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data RFQ</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('rfq.create') }}"><button class="btn btn-success">Tambah Data</button></a>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Vendor</th>
                                <th>Tanggal Order</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $rfq)
                                <tr>
                                    <td>{{ $rfq->kode_rfq }}</td>
                                    <td>{{ $rfq->tb_vendor->nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($rfq->tanggal_order)->format('d/m/Y') }}</td>
                                    <td>
                                        @if (strtolower($rfq->status) == 'draft')
                                            <span class="badge bg-secondary">Draft</span>
                                        @elseif (strtolower($rfq->status) == 'rfq_sent')
                                            <span class="badge bg-primary">RFQ Sent</span>
                                        @elseif (strtolower($rfq->status) == 'rfq_approved')
                                            <span class="badge bg-info">RFQ Approved</span>
                                        @elseif (strtolower($rfq->status) == 'purchase_order')
                                            <span class="badge bg-success">Purchase Order</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('rfq.edit', $rfq->id) }}"
                                            class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                                            data-original-title="Edit RFQ">
                                            <button class="btn btn-primary" type="button">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </a>
                                        <a href="{{ route('rfq.show', $rfq->id) }}"
                                            class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                                            data-original-title="RFQ Detail">
                                            <button class="btn btn-primary" type="button">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </button>
                                        </a>
                                        <a href="{{ route('rfq.destroy', $rfq->id) }}"
                                            class="btn btn-danger font-weight-bold text-xs" data-confirm-delete="true">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
@endsection
