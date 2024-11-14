<!DOCTYPE html>
<html>

<head>
    <title>BoM - {{ $bom->tb_produk->nama_produk }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            padding: 0 10 0 0;
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }
    </style>
</head>

<body>
    <h3>BoM Structure & Cost</h3>
    <h5>[{{ $bom->reference }}] {{ $bom->tb_produk->nama_produk }}</h5>

    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>BoM</th>
                <th>Kuantitas</th>
                <th>Satuan</th>
                <th>Product Cost</th>
                <th>BoM Cost</th>
            </tr>
        </thead>
        <tbody>
            <!-- Main product row -->
            <tr>
                <td>[{{ $bom->tb_produk->barcode }}] {{ $bom->tb_produk->nama_produk }}</td>
                <td>[{{ $bom->reference }}] {{ $bom->tb_produk->nama_produk }}</td>
                <td>{{ number_format($bom->kuantitas, 2) }}</td>
                <td>{{ $bom->tb_produk->satuan }}</td>
                <td>Rp{{ number_format($bom->tb_produk->biaya_produk, 2) }}</td>
                <td>Rp{{ number_format(
                    $bom->kuantitas *
                        $bom->tb_bom_details->sum(function ($detail) {
                            return ($detail->tb_bahanbaku->satuan == 'gram'
                                ? $detail->kuantitas_bahan / 1000
                                : ($detail->tb_bahanbaku->satuan == 'milliliter'
                                    ? $detail->kuantitas_bahan / 1000
                                    : $detail->kuantitas_bahan)) * $detail->tb_bahanbaku->harga_beli;
                        }),
                    2,
                ) }}
                </td>
            </tr>

            @php
                $totalCost = 0;
            @endphp
            @foreach ($bom->tb_bom_details as $detail)
                @php
                    $materialCost =
                        ($detail->tb_bahanbaku->satuan == 'gram'
                            ? $detail->kuantitas_bahan / 1000
                            : ($detail->tb_bahanbaku->satuan == 'milliliter'
                                ? $detail->kuantitas_bahan / 1000
                                : $detail->kuantitas_bahan)) * $detail->tb_bahanbaku->harga_beli;
                    $totalCost += $materialCost;
                @endphp
                <tr>
                    <td style="padding-left: 30px;">[{{ $detail->tb_bahanbaku->barcode }}]
                        {{ $detail->tb_bahanbaku->nama }}</td>
                    <td></td>
                    <td>{{ number_format($detail->kuantitas_bahan, 2) }}</td>
                    <td>{{ $detail->tb_bahanbaku->satuan }}</td>
                    <td>Rp{{ number_format($detail->tb_bahanbaku->harga_beli, 2) }}</td>
                    <td>Rp{{ number_format($materialCost, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2"></td>
                <td><strong>Unit Cost</strong></td>
                <td></td>
                <td><strong>Rp{{ number_format($bom->tb_produk->biaya_produk, 2) }}</strong></td>
                <td><strong>Rp{{ number_format($totalCost, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>

</html>
