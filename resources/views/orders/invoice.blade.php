<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'dejavusans', sans-serif;
        color: #0f0f0f;
        font-size: 12px;
        line-height: 1.7;
        direction: rtl;
        background: #ffffff;
    }

    /* ═══════════ HEADER ═══════════ */
    .header {
        border-bottom: 3px solid #005a96;
        padding-bottom: 22px;
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
        font-size: 26px;
        font-weight: bold;
        color: #005a96;
        margin-bottom: 6px;
        letter-spacing: -0.5px;
    }

    .logo-mark {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #14b8a6;
        margin-left: 8px;
        vertical-align: middle;
    }

    .invoice-title {
        font-size: 20px;
        font-weight: bold;
        color: #0f0f0f;
        margin-bottom: 6px;
        letter-spacing: -0.3px;
    }

    .invoice-number {
        color: #6b6b5e;
        font-size: 11px;
        margin-bottom: 3px;
    }

    .invoice-number strong {
        color: #0f0f0f;
    }

    /* ═══════════ INFO BOXES ═══════════ */
    .info-section {
        width: 100%;
        margin-bottom: 28px;
    }

    .info-section table {
        width: 100%;
        border: none;
        border-spacing: 0;
    }

    .info-section td {
        vertical-align: top;
        width: 48%;
        padding: 0;
        border: none;
    }

    .info-box {
        background: #fafaf9;
        padding: 16px;
        border-right: 3px solid #005a96;
    }

    .info-box h3 {
        font-size: 11px;
        color: #005a96;
        margin-bottom: 12px;
        border-bottom: 1px solid #e6e6e4;
        padding-bottom: 8px;
        font-weight: bold;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .info-box p {
        margin-bottom: 5px;
        color: #4a4a4a;
        font-size: 11px;
    }

    .info-box strong {
        color: #0f0f0f;
        font-weight: bold;
    }

    /* ═══════════ STATUS BADGE ═══════════ */
    .status-wrapper {
        margin-bottom: 20px;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 14px;
        font-size: 10px;
        font-weight: bold;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        border: 1px solid;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
    }

    .status-processing {
        background: #e0e7ff;
        color: #3730a3;
        border-color: #a5b4fc;
    }

    .status-shipped {
        background: #dbeafe;
        color: #1e40af;
        border-color: #93c5fd;
    }

    .status-delivered {
        background: #d1fae5;
        color: #065f46;
        border-color: #6ee7b7;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fca5a5;
    }

    /* ═══════════ PRODUCTS TABLE ═══════════ */
    .products-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 28px;
        border: 1px solid #e6e6e4;
    }

    .products-table thead {
        background: #005a96;
    }

    .products-table thead th {
        color: white;
        padding: 13px 12px;
        text-align: right;
        font-size: 10px;
        font-weight: bold;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        border: none;
    }

    .products-table thead th:first-child {
        padding-right: 16px;
    }

    .products-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #f0f0ee;
        font-size: 11px;
        color: #2a2a2a;
        vertical-align: top;
    }

    .products-table tbody td:first-child {
        padding-right: 16px;
    }

    .products-table tbody tr:last-child td {
        border-bottom: none;
    }

    .products-table tbody tr:nth-child(even) {
        background: #fafaf9;
    }

    .product-name {
        font-weight: bold;
        color: #0f0f0f;
        font-size: 12px;
        display: block;
        margin-bottom: 3px;
    }

    .product-variant {
        font-size: 10px;
        color: #9a9a8c;
    }

    .product-variant span {
        margin-left: 6px;
    }

    /* ═══════════ TOTALS ═══════════ */
    .totals {
        width: 55%;
        margin-right: auto;
        margin-left: 0;
    }

    .totals table {
        width: 100%;
        border: 1px solid #e6e6e4;
        border-collapse: collapse;
    }

    .totals td {
        padding: 10px 14px;
        border-bottom: 1px solid #e6e6e4;
        font-size: 12px;
    }

    .totals tr:last-child td {
        border-bottom: none;
    }

    .totals .label {
        text-align: right;
        color: #6b6b5e;
        font-size: 11px;
    }

    .totals .value {
        text-align: left;
        color: #0f0f0f;
        font-weight: bold;
    }

    .totals tr.final td {
        background: #005a96;
        padding: 14px;
        border: none;
        color: #ffffff;
        font-size: 14px;
        font-weight: bold;
    }

    .totals tr.final .label {
        color: #ffffff;
        font-size: 12px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .totals tr.final .value {
        color: #ffffff;
        font-size: 16px;
        letter-spacing: -0.5px;
    }

    /* ═══════════ FOOTER ═══════════ */
    .footer {
        margin-top: 60px;
        padding-top: 24px;
        border-top: 2px solid #e6e6e4;
        text-align: center;
    }

    .footer .thanks {
        font-size: 14px;
        color: #005a96;
        font-weight: bold;
        margin-bottom: 10px;
        letter-spacing: -0.3px;
    }

    .footer .thanks span {
        color: #14b8a6;
    }

    .footer p {
        margin-bottom: 5px;
        color: #6b6b5e;
        font-size: 11px;
    }

    .footer .contact {
        margin: 12px 0;
        color: #0f0f0f;
        font-weight: bold;
        font-size: 11px;
    }

    .footer .copyright {
        margin-top: 16px;
        font-size: 10px;
        color: #9a9a8c;
        letter-spacing: 0.5px;
    }

    .footer .divider {
        display: inline-block;
        width: 40px;
        height: 2px;
        background: #14b8a6;
        margin: 14px 0;
    }
</style>

{{-- ══════════════════════════════════════ --}}
{{-- HEADER --}}
{{-- ══════════════════════════════════════ --}}
<div class="header">
    <table>
        <tr>
            <td class="right" style="width: 50%;">
                <div class="logo">
                    {{ $siteSettings->site_name ?? 'متجري' }}<span class="logo-mark"></span>
                </div>
                <p style="color: #6b6b5e; font-size: 11px;">
                    {{ $siteSettings->tagline ?? 'تجربة تسوق عصرية' }}
                </p>
            </td>
            <td class="left" style="width: 50%;">
                <div class="invoice-title">فاتورة ضريبية</div>
                <p class="invoice-number">رقم الفاتورة: <strong>{{ $order->order_number }}</strong></p>
                <p class="invoice-number">التاريخ: {{ $order->created_at->format('Y-m-d') }}</p>
            </td>
        </tr>
    </table>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- INFO SECTIONS --}}
{{-- ══════════════════════════════════════ --}}
<div class="info-section">
    <table>
        <tr>
            <td style="padding-left: 12px;">
                <div class="info-box">
                    <h3>معلومات العميل</h3>
                    <p><strong>الاسم:</strong> {{ $order->user->name }}</p>
                    <p><strong>البريد:</strong> {{ $order->user->email }}</p>
                    @if($order->user->phone)
                        <p><strong>الهاتف:</strong> <span dir="ltr">{{ $order->user->phone }}</span></p>
                    @endif
                </div>
            </td>
            <td style="padding-right: 12px;">
                <div class="info-box">
                    <h3>عنوان الشحن</h3>
                    <p><strong>المدينة:</strong> {{ $order->address->city->name ?? '—' }}</p>
                    <p><strong>العنوان:</strong> {{ $order->address->street_address ?? '—' }}</p>
                    @if($order->address->phone)
                        <p><strong>الهاتف:</strong> <span dir="ltr">{{ $order->address->phone }}</span></p>
                    @endif
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- STATUS --}}
{{-- ══════════════════════════════════════ --}}
<div class="status-wrapper">
    @php
        $labels = [
            'pending' => 'قيد المراجعة',
            'processing' => 'قيد المعالجة',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التوصيل',
            'cancelled' => 'ملغي',
        ];
    @endphp
    <span class="status-badge status-{{ $order->status }}">
        {{ $labels[$order->status] ?? $order->status }}
    </span>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- PRODUCTS TABLE --}}
{{-- ══════════════════════════════════════ --}}
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
                    <span class="product-name">{{ $item->variant->product->name ?? 'منتج محذوف' }}</span>
                    @if(($item->variant->color ?? null) || ($item->variant->size ?? null))
                        <div class="product-variant">
                            @if($item->variant->color ?? null)
                                <span>{{ $item->variant->color }}</span>
                            @endif
                            @if($item->variant->size ?? null)
                                <span>{{ $item->variant->size }}</span>
                            @endif
                        </div>
                    @endif
                </td>
                <td style="text-align: center; font-weight: bold;">{{ $item->quantity }}</td>
                <td>${{ number_format($item->unit_price, 2) }}</td>
                <td style="font-weight: bold;">${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- ══════════════════════════════════════ --}}
{{-- TOTALS --}}
{{-- ══════════════════════════════════════ --}}
<div class="totals">
    <table>
        <tr>
            <td class="label">المجموع الفرعي</td>
            <td class="value">${{ number_format($order->total_amount - $order->shipping_cost, 2) }}</td>
        </tr>
        <tr>
            <td class="label">الشحن</td>
            <td class="value">
                @if($order->shipping_cost > 0)
                    ${{ number_format($order->shipping_cost, 2) }}
                @else
                    مجاني
                @endif
            </td>
        </tr>
        <tr class="final">
            <td class="label">الإجمالي</td>
            <td class="value">${{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </table>
</div>

{{-- ══════════════════════════════════════ --}}
{{-- FOOTER --}}
{{-- ══════════════════════════════════════ --}}
<div class="footer">
    <p class="thanks">شكراً لك على تسوقك معنا <span>—</span> نتطلع لخدمتك مجدداً</p>
    <div class="divider"></div>

    <p>لأي استفسار، تواصل معنا:</p>
    <p class="contact">
        @if($siteSettings->email ?? null) {{ $siteSettings->email }} @else info@matjari.com @endif
        @if($siteSettings->phone ?? null)
            <span style="color: #9a9a8c; margin: 0 8px;">•</span>
            <span dir="ltr">{{ $siteSettings->phone }}</span>
        @else
            <span style="color: #9a9a8c; margin: 0 8px;">•</span>
            <span dir="ltr">+970 599 123 456</span>
        @endif
    </p>

    <p class="copyright">
        © {{ date('Y') }} {{ $siteSettings->site_name ?? 'متجري' }} — جميع الحقوق محفوظة
    </p>
</div>