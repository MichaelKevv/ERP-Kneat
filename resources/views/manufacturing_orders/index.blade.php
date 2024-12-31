@extends('template')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Manufacturing Order</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data Manufacturing Order</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('manufacturing_orders.create') }}"><button class="btn btn-success">Tambah
                            Data</button></a>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Produk</th>
                                <th>Kuantitas yang Dihasilkan</th>
                                <th>Tanggal Produksi</th>
                                <th>Tanggal Deadline</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($manufacturingOrders as $order)
                                <tr>
                                    <td>{{ $order->kode_mo }}</td>
                                    <td>{{ $order->tb_produk->nama_produk }}</td>
                                    <td>{{ $order->kuantitas_produk }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->tanggal_produksi)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->tanggal_deadline)->format('d/m/Y') }}</td>
                                    <td>
                                        @if (strtolower($order->status) == 'draft')
                                            <span class="badge bg-secondary">Draft</span>
                                        @elseif (strtolower($order->status) == 'confirmed')
                                            <span class="badge bg-primary">Confirmed</span>
                                        @elseif (strtolower($order->status) == 'in_progress')
                                            <span class="badge bg-info">In Progress</span>
                                        @elseif (strtolower($order->status) == 'done')
                                            <span class="badge bg-success">Done</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('manufacturing_orders.show', $order->id) }}"
                                            class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                                            data-original-title="Detail MO">
                                            <button class="btn btn-success" type="button">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </button>
                                        </a>
                                        <a href="{{ route('manufacturing_orders.destroy', $order->id) }}"
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
