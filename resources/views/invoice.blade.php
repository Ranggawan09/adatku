<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Reservation Invoice</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@400;600&display=swap" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: 80mm auto; /* Lebar printer thermal umum */
                margin: 3mm;
            }
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            color: #000000;
            font-family: 'Source Code Pro', monospace;
            font-size: 9pt; /* Ukuran font yang umum untuk struk */
            line-height: 1.4;
        }

        * {
            box-sizing: border-box;
        }

        h1, h3, h4, p, ul, li {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .receipt-container {
            width: 100%;
        }

        .header, .footer-thanks {
            text-align: center;
        }

        .header h1 {
            font-size: 14pt;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 8pt;
        }

        .line-separator {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .details-row {
            display: flex;
            justify-content: space-between;
        }

        .item-details {
            margin-top: 10px;
        }

        .item-details h4 {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .totals-section {
            margin-top: 10px;
        }

        .totals-section .details-row {
            margin-bottom: 3px;
        }

        .totals-section .total-row {
            font-weight: 600;
            font-size: 10pt;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 5px 0;
            margin-top: 5px;
        }

        .footer-thanks {
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <div class="header" style="margin-top: 15px;">
            <img src="/storage/logos/LOGObg.png" alt="Logo" style="width: 80px; margin: 0 auto;">
        </div>

        <div class="header">
            <h1>Griya Paes Salsabila</h1>
            <p>Jl. Dukuh, Desa Dukuhklopo, Jombang</p>
            <p>081231180775</p>
        </div>


        <div class="line-separator"></div>

        <div class="details-row">
            <span>Invoice:</span>
            <span>{{ $reservation->order_id }}</span>
        </div>
        <div class="details-row">
            <span>Tanggal:</span>
            <span>{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}</span>
        </div>
        <div class="details-row">
            <span>Pelanggan:</span>
            <span>{{ $reservation->user->name }}</span>
        </div>

        <div class="line-separator"></div>

        <div class="item-details">
            @foreach($relatedReservations as $item)
                <div style="margin-bottom: 8px;">
                    <h4>{{ $item->pakaianAdat->nama }}</h4>
                    <div class="details-row">
                        <span>Ukuran: {{ $item->variant->size }}</span>
                        <span>x{{ $item->quantity }}</span>
                    </div>
                </div>
            @endforeach
            <p style="font-size: 8pt; margin-top: 5px;">
                Durasi: {{ $reservation->days }} hari ({{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m') }} - {{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y') }})
            </p>
        </div>

        <div class="line-separator"></div>

        <div class="totals-section">
            <div class="details-row">
                <span>Subtotal ({{ $relatedReservations->sum('quantity') }} item)</span>
                <span>Rp{{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>

            @if($reservation->status === 'Terlambat' && $reservation->late_fee > 0)
                @php
                    $lateDays = $reservation->late_fee / 50000;
                @endphp
                <div class="details-row">
                    <span>Pembayaran Awal</span>
                    <span>(Lunas)</span>
                </div>
                <div class="line-separator"></div>
                <div class="details-row">
                    <span>Denda ({{ $lateDays }} hari)</span>
                    <span>Rp{{ number_format($reservation->late_fee, 0, ',', '.') }}</span>
                </div>
                <div class="details-row total-row">
                    <span>KURANG PEMBAYARAN:</span>
                    <span>Rp{{ number_format($reservation->late_fee, 0, ',', '.') }}</span>
                </div>
            @else
                <div class="details-row total-row">
                    <span>TOTAL:</span>
                    <span>Rp{{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="details-row" style="margin-top: 5px;">
                <span>Status Pembayaran:</span>
                <span>{{ $reservation->payment_status }}</span>
            </div>
        </div>

        <div class="line-separator"></div>

        <div class="footer-thanks">
            <p>Terima kasih telah memilih Adatku ❤️</p>
            <p style="font-size: 8pt; margin-top: 5px;">Tunjukkan struk ini untuk pembayaran/pengambilan pakaian.</p>
        </div>
    </div>
    <script>
        window.addEventListener('load', function() {
            window.print();
            // The window will be closed by the user through the print dialog
            // or by browser behavior after printing.
            // A timeout to force close can be disruptive.
            // setTimeout(() => window.close(), 500);
        });
    </script>
</body>

</html>
