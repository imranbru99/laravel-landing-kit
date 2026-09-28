<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>প্যাকিং স্লিপ #{{ $order->order_number }}</title>
    <style>
        body { font-family: 'Hind Siliguri', sans-serif; padding: 20px; font-size: 13px; color: #1e293b; }
        .header { border-bottom: 2px solid #334155; padding-bottom: 10px; margin-bottom: 15px; display: flex; justify-content: space-between; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px 10px; text-align: left; }
        th { background: #f1f5f9; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h2>প্যাকিং স্লিপ (Warehouse Packing Slip)</h2>
            <div>অর্ডার নং: <strong>{{ $order->order_number }}</strong> | তারিখ: {{ $order->created_at->format('d/m/Y') }}</div>
        </div>
        <div style="text-align: right;">
            <strong>{{ setting('site_name', 'Amar Shop BD') }}</strong>
        </div>
    </div>

    <div style="margin-bottom: 15px;">
        <strong>গ্রাহক:</strong> {{ $order->customer_name }} ({{ $order->customer_phone }})<br>
        <strong>ঠিকানা:</strong> {{ $order->customer_address }}, {{ $order->district?->name_bn }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">#</th>
                <th>আইটেমের নাম ও ভ্যারিয়েন্ট</th>
                <th style="width: 80px; text-align: center;">পরিমাণ</th>
                <th style="width: 100px; text-align: center;">চেক মার্ক</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $idx => $item)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->variant_name)
                            <br><small style="color: #64748b;">{{ $item->variant_name }}</small>
                        @endif
                    </td>
                    <td style="text-align: center; font-size: 15px; font-weight: bold;">{{ $item->quantity }}</td>
                    <td style="text-align: center;">[ &nbsp; ]</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
