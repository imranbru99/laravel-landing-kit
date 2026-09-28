<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>কুরিয়ার স্টিকার #{{ $order->order_number }}</title>
    <style>
        body { font-family: 'Hind Siliguri', sans-serif; padding: 15px; font-size: 14px; margin: 0; }
        .box { border: 2px solid #000; padding: 15px; border-radius: 8px; }
        .row { display: flex; justify-content: space-between; border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px; }
        .cod-amount { font-size: 24px; font-weight: bold; color: #dc2626; border: 2px solid #dc2626; padding: 6px 12px; display: inline-block; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="box">
        <div class="row">
            <div>
                <strong style="font-size: 18px;">{{ setting('site_name', 'Amar Shop BD') }}</strong>
                <div style="font-size: 12px;">প্রেরক: {{ setting('primary_phone', '01700000000') }}</div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 15px; font-weight: bold;">অর্ডার: {{ $order->order_number }}</div>
                <div style="font-size: 12px;">তারিখ: {{ $order->created_at->format('d/m/Y') }}</div>
            </div>
        </div>

        <div style="font-size: 16px; margin-bottom: 15px;">
            <div style="color: #64748b; font-size: 12px; text-transform: uppercase;">প্রাপক / DELIVER TO:</div>
            <div style="font-size: 18px; font-weight: bold; margin-top: 4px;">{{ $order->customer_name }}</div>
            <div style="font-size: 20px; font-weight: bold; color: #0284c7; margin: 4px 0;">📞 {{ $order->customer_phone }}</div>
            <div style="margin-top: 6px; font-size: 14px; line-height: 1.4;">{{ $order->customer_address }}</div>
            <div style="font-weight: bold; margin-top: 4px;">জেলা: {{ $order->district?->name_bn ?? $order->district?->name_en }} | থানা: {{ $order->thana?->name_bn ?? $order->thana?->name_en ?? 'N/A' }}</div>
        </div>

        <div style="text-align: center; margin-top: 20px; background: #fff1f2; padding: 12px; border-radius: 6px;">
            <div style="font-size: 13px; font-weight: bold; margin-bottom: 4px;">ক্যাশ অন ডেলিভারি (COD কালেকশন)</div>
            <div class="cod-amount">৳ {{ number_format((float) $order->total_amount, 0) }}</div>
        </div>

        @if($order->customer_notes)
            <div style="margin-top: 10px; font-size: 12px; color: #b91c1c;">
                <strong>নোট:</strong> {{ $order->customer_notes }}
            </div>
        @endif
    </div>
</body>
</html>
