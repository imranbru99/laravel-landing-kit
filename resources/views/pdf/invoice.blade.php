<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>চালান / ইনভয়েস #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Hind Siliguri', 'SolaimanLipi', sans-serif;
            margin: 0;
            padding: 24px;
            color: #1e293b;
            font-size: 13px;
            line-height: 1.5;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #059669;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }
        .store-title {
            font-size: 22px;
            font-weight: bold;
            color: #059669;
        }
        .store-sub {
            color: #64748b;
            font-size: 11px;
        }
        .invoice-title {
            text-align: right;
        }
        .badge {
            background-color: #ecfdf5;
            color: #065f46;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
            display: inline-block;
        }
        .grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .box {
            width: 48%;
            background: #f8fafc;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }
        .box h4 {
            margin: 0 0 6px 0;
            color: #334155;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 4px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background: #059669;
            color: #ffffff;
            text-align: left;
            padding: 8px 10px;
            font-size: 12px;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        tr:nth-child(even) {
            background: #f8fafc;
        }
        .total-box {
            width: 40%;
            margin-left: auto;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .grand-total {
            background: #059669;
            color: #ffffff;
            font-weight: bold;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="store-title">{{ setting('site_name', 'Amar Shop BD') }}</div>
            <div class="store-sub">{{ setting('site_tagline', 'প্রিমিয়াম অনলাইন কেনাকাটা') }}</div>
            <div class="store-sub">হটলাইন: {{ setting('primary_phone', '01700000000') }}</div>
        </div>
        <div class="invoice-title">
            <h2 style="margin: 0; color: #059669;">ইনভয়েস / চালান (Invoice)</h2>
            <div style="font-size: 12px; margin-top: 4px;">অর্ডার নং: <strong>{{ $order->order_number }}</strong></div>
            <div style="font-size: 11px; color: #64748b;">তারিখ: {{ $order->created_at->format('d/m/Y h:i A') }}</div>
            <div style="margin-top: 6px;">
                <span class="badge">{{ $order->status->labelBn() }}</span>
            </div>
        </div>
    </div>

    <div class="grid">
        <div class="box">
            <h4>গ্রাহকের তথ্য (বিল টু)</h4>
            <div><strong>নাম:</strong> {{ $order->customer_name }}</div>
            <div><strong>মোবাইল:</strong> {{ $order->customer_phone }}</div>
            <div><strong>ঠিকানা:</strong> {{ $order->customer_address }}</div>
            <div><strong>জেলা / থানা:</strong> {{ $order->district?->name_bn ?? $order->district?->name_en }} {{ $order->thana ? '(' . ($order->thana->name_bn ?? $order->thana->name_en) . ')' : '' }}</div>
        </div>

        <div class="box">
            <h4>অর্ডার ও পেমেন্ট বিবরণ</h4>
            <div><strong>পেমেন্ট পদ্ধতি:</strong> {{ strtoupper($order->payment_method) }} (ক্যাশ অন ডেলিভারি)</div>
            <div><strong>পেমেন্ট স্ট্যাটাস:</strong> {{ strtoupper($order->payment_status) }}</div>
            <div><strong>ডেলিভারি জোন:</strong> {{ $order->deliveryZone?->name ?? 'স্ট্যান্ডার্ড ডেলিভারি' }}</div>
            @if($order->customer_notes)
                <div style="margin-top: 4px; color: #047857;"><strong>বিশেষ দ্রষ্টব্য:</strong> {{ $order->customer_notes }}</div>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">ক্রম</th>
                <th>পণ্যের বিবরণ</th>
                <th style="width: 80px; text-align: right;">মূল্য</th>
                <th style="width: 70px; text-align: center;">পরিমাণ</th>
                <th style="width: 90px; text-align: right;">মোট</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->variant_name)
                            <div style="font-size: 11px; color: #64748b;">ভ্যারিয়েন্ট: {{ $item->variant_name }}</div>
                        @endif
                    </td>
                    <td style="text-align: right;">৳ {{ number_format((float) $item->unit_price, 2) }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">৳ {{ number_format((float) $item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <div class="total-row">
            <span>সাবটোটাল:</span>
            <strong>৳ {{ number_format((float) $order->subtotal, 2) }}</strong>
        </div>
        <div class="total-row">
            <span>ডেলিভারি চার্জ:</span>
            <strong>৳ {{ number_format((float) $order->delivery_charge, 2) }}</strong>
        </div>
        @if((float) $order->discount_amount > 0)
            <div class="total-row" style="color: #dc2626;">
                <span>ডিসকাউন্ট:</span>
                <strong>- ৳ {{ number_format((float) $order->discount_amount, 2) }}</strong>
            </div>
        @endif
        <div class="total-row grand-total">
            <span>সর্বমোট পরিশোধযোগ্য:</span>
            <span>৳ {{ number_format((float) $order->total_amount, 2) }}</span>
        </div>
    </div>

    <div class="footer">
        ধন্যবাদ আমাদের সাথে কেনাকাটা করার জন্য! যেকোনো প্রয়োজনে যোগাযোগ করুন: {{ setting('primary_phone', '01700000000') }}
    </div>
</body>
</html>
