<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->invoice_code }}</title>

    <!-- Link to Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            padding: 20px;
        }

        .invoice-container {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-header h3 {
            font-size: 1.8rem;
        }

        .invoice-detail {
            margin-top: 20px;
        }

        .invoice-detail table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-detail th, .invoice-detail td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .qr-container {
            margin-top: 30px;
            text-align: center;
        }

        .back-link {
            text-align: center;
            margin-top: 40px;
        }

        .back-link a {
            font-size: 1.1rem;
            text-decoration: none;
            color: #007bff;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body onload="window.print()">

    <!-- Invoice Container -->
    <div class="invoice-container">

        <div class="invoice-header">
            <h3>Invoice Order - {{ $order->invoice_code }}</h3>
            <p>Tanggal: {{ $order->order_date->format('d M Y') }}</p>
        </div>

        <div class="invoice-detail">
            <h5>Detail Order:</h5>
            <table>
                <thead>
                    <tr>
                        <th>Nama Item</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->menu->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp{{ number_format($item->menu->price) }}</td>
                        <td>Rp{{ number_format($item->menu->price * $item->quantity) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                        <td><strong>Rp{{ number_format($order->total_amount) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- QRIS Section (if applicable) -->
        @if ($order->payment_method === 'qris' && !empty($qrBase64))
        <div class="qr-container">
            <p><strong>Scan QRIS untuk pembayaran:</strong></p>
            <img src="{{ $qrBase64 }}" alt="QRIS Code" style="width: 200px; height: 200px;">
        </div>
        @endif

    </div>

    <!-- Back Link -->
    <div class="back-link">
        <a href="{{ route('orders.index') }}">Kembali ke Halaman Buat Order Baru</a>
    </div>

    <!-- Bootstrap 5 JS Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
