<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'dejavusans', sans-serif;
        color: #333;
        font-size: 12px;
        line-height: 1.8;
        direction: rtl;
    }

    .header {
        border-bottom: 3px solid #f59e0b;
        padding-bottom: 20px;
        margin-bottom: 30px;
        width: 100%;
    }

    .header table {
        width: 100%;
        border: none;
    }

    .header td {
        vertical-align: top;
        border: none;
        padding: 0;
    }

    .header .right {
        text-align: right;
    }

    .header .left {
        text-align: left;
    }

    .logo {
        font-size: 28px;
        font-weight: bold;
        color: #f59e0b;
        margin-bottom: 5px;
    }

    .invoice-title {
        font-size: 22px;
        font-weight: bold;
        color: #1f2937;
        margin-bottom: 5px;
    }

    .invoice-number {
        color: #6b7280;
        font-size: 12px;
    }

    /* Info Sections */
    .info-section {
        width: 100%;
        margin-bottom: 25px;
    }

    .info-section table {
        width: 100%;
        border: none;
    }

    .info-section td {
        vertical-align: top;
        width: 48%;
        padding: 0;
        border: none;
    }

    .info-box {
        background: #f9fafb;
        padding: 15px;
        border-right: 4px solid #f59e0b;
    }

    .info-box h3 {
        font-size: 14px;
        color: #1f2937;
        margin-bottom: 10px;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 6px;
    }

    .info-box p {
        margin-bottom: 4px;
        color: #4b5563;
        font-size: 11px;
    }

    .info-box strong {
        color: #1f2937;
    }

    /* Table */
    .products-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 25px;
    }

    .products-table thead {
        background: #f59e0b;
    }

    .products-table thead th {
        color: white;
        padding: 12px 8px;
        text-align: right;
        font-size: 12px;
        font-weight: bold;
    }

    .products-table tbody td {
        padding: 10px 8px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 11px;
        color: #4b5563;
    }

    .products-table tbody tr:nth-child(even) {
        background: #f9fafb;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-processing {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-shipped {
        background: #ede9fe;
        color: #5b21b6;
    }

    .status-delivered {
        background: #d1fae5;
        color: #065f46;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Totals */
    .totals {
        width: 50%;
        margin-right: auto;
        margin-left: 0;
    }

    .totals table {
        width: 100%;
        border: none;
    }

    .totals td {
        padding: 8px 0;
        border-bottom: 1px solid #e5e7eb;
        font-size: 12px;
        border-left: none;
        border-right: none;
    }

    .totals .label {
        text-align: right;
    }

    .totals .value {
        text-align: left;
    }

    .totals tr.final td {
        background: #fef3c7;
        padding: 12px;
        border: 2px solid #f59e0b;
        font-size: 14px;
        font-weight: bold;
        color: #92400e;
    }

    /* Footer */
    .footer {
        margin-top: 60px;
        padding-top: 20px;
        border-top: 2px solid #e5e7eb;
        text-align: center;
        color: #6b7280;
        font-size: 11px;
    }

    .footer .thanks {
        font-size: 14px;
        color: #f59e0b;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .footer p {
        margin-bottom: 4px;
    }
</style>

{{-- ================== المحتوى ================== --}}

{{-- Header --}}
<div class="header">
    <table>
        <tr>
            <td class="right" style="width: 50%;">
                <div class="logo">🛍️ متجري</div>
                <p style="color: #6b7280; font-size: 11px;">تجربة تسوق عصرية</p>
            </td>
            <td class="left" style="width: 50%;">
                <div class="invoice-title">فاتورة ضريبية</div>
                <p class="invoice-number">رقم الفاتورة: <strong>{{ $order->order_number }}</strong></p>
                <p class="invoice-number">التاريخ: {{ $order->created_at->format('Y-m-d') }}</p>
            </td>
        </tr>
    </table>
</div>

{{-- Info Sections --}}
<div class="info-section">
    <table>
        <tr>
            <td style="padding-left: 10px;">
                <div class="info-box">
                    <h3>📋 معلومات العميل</h3>
                    <p><strong>الاسم:</strong> {{ $order->user->name }}</p>
                    <p><strong>البريد:</strong> {{ $order->user->email }}</p>
                    @if($order->user->phone)
                        <p><strong>الهاتف:</strong> {{ $order->user->phone }}</p>
                    @endif
                </div>
            </td>
            <td style="padding-right: 10px;">
                <div class="info-box">
                    <h3>📍 عنوان الشحن</h3>
                    <p><strong>المدينة:</strong> {{ $order->address->city->name ?? '—' }}</p>
                    <p><strong>العنوان:</strong> {{ $order->address->street_address ?? '—' }}</p>
                    @if($order->address->phone)
                        <p><strong>الهاتف:</strong> {{ $order->address->phone }}</p>
                    @endif
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- Status --}}
<div>
    <span class="status-badge status-{{ $order->status }}">
        @php
            $labels = [
                'pending' => '⏳ قيد المراجعة',
                'processing' => '⚙️ قيد المعالجة',
                'shipped' => '🚚 تم الشحن',
                'delivered' => '✅ تم التوصيل',
                'cancelled' => '❌ ملغي',
            ];
        @endphp
        {{ $labels[$order->status] ?? $order->status }}
    </span>
</div>

{{-- Products Table --}}
<table class="products-table">
    <thead>
        <tr>
            <th style="width: 50%;">المنتج</th>
            <th style="width: 15%; text-align: center;">الكمية</th>
            <th style="width: 17%;">سعر الوحدة</th>
            <th style="width: 18%;">الإجمالي</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>
                    <strong>{{ $item->variant->product->name ?? 'منتج محذوف' }}</strong>
                    @if(($item->variant->color ?? null) || ($item->variant->size ?? null))
                        <br>
                        <span style="font-size: 10px; color: #9ca3af;">
                            {{ $item->variant->color ?? '' }}
                            {{ $item->variant->size ? '- ' . $item->variant->size : '' }}
                        </span>
                    @endif
                </td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
                <td>${{ number_format($item->unit_price, 2) }}</td>
                <td>${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Totals --}}
<div class="totals">
    <table>
        <tr>
            <td class="label">المجموع الفرعي:</td>
            <td class="value">${{ number_format($order->total_amount - $order->shipping_cost, 2) }}</td>
        </tr>
        <tr>
            <td class="label">الشحن:</td>
            <td class="value">
                @if($order->shipping_cost > 0)
                    ${{ number_format($order->shipping_cost, 2) }}
                @else
                    مجاني
                @endif
            </td>
        </tr>
        <tr class="final">
            <td class="label">الإجمالي النهائي:</td>
            <td class="value">${{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </table>
</div>

{{-- Footer --}}
<div class="footer">
    <p class="thanks">شكراً لك على تسوقك معنا! ❤️</p>
    <p>لأي استفسار، تواصل معنا عبر البريد الإلكتروني أو الهاتف</p>
    <p>info@matjari.com • +970 599 123 456</p>
    <p style="margin-top: 10px; font-size: 10px; color: #9ca3af;">
        © {{ date('Y') }} متجري. جميع الحقوق محفوظة.
    </p>
</div>