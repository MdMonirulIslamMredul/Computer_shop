<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ওয়ার্ক অর্ডার স্লিপ - {{ $order->tracking_id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff;
            color: #000;
        }
        .receipt-card {
            max-width: 780px;
            margin: 20px auto;
            padding: 30px;
            border: 1px dashed #94a3b8;
            border-radius: 8px;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .receipt-card {
                border: none;
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container my-3">
        <div class="text-center mb-3 no-print">
            <button onclick="window.print()" class="btn btn-primary px-4 rounded-pill">
                <i class="bi bi-printer"></i> স্লিপ প্রিন্ট করুন
            </button>
            <button onclick="window.close()" class="btn btn-outline-secondary px-4 rounded-pill ms-2">
                বন্ধ করুন
            </button>
        </div>

        <div class="receipt-card">
            {{-- Header --}}
            <div class="text-center border-bottom pb-3 mb-4">
                <h3 class="fw-bold mb-1 text-success">{{ $settings->site_name ?? 'সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার' }}</h3>
                <p class="mb-1 text-muted">{{ $settings->contact_address ?? 'জনতা বাজার, মেইন রোড, আলেকজান্ডার, রামগতি, লক্ষ্মীপুর' }}</p>
                <p class="mb-0 small">মোবাইল: <strong>{{ $settings->contact_phone ?? '01711-665098, 01717-074428' }}</strong> | ইমেইল: {{ $settings->contact_email ?? 'abrahman9196@gmail.com' }}</p>
                <div class="badge bg-dark text-white px-3 py-2 mt-2 fs-6">গ্রাহক সেবা অর্ডার স্লিপ (Work Order Receipt)</div>
            </div>

            {{-- Order & Customer Info --}}
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width: 120px;">ট্র্যাকিং আইডি:</td>
                            <td><strong class="font-monospace text-primary fs-5">{{ $order->tracking_id }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">তারিখ:</td>
                            <td><strong>{{ $order->created_at->format('d M, Y - h:i A') }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">অগ্রগতি স্ট্যাটাস:</td>
                            <td><span class="badge bg-secondary">{{ $order->status_label }}</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width: 120px;">গ্রাহকের নাম:</td>
                            <td><strong>{{ $order->customer_name }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">মোবাইল নম্বর:</td>
                            <td><strong class="font-monospace">{{ $order->customer_phone }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">ঠিকানা:</td>
                            <td>{{ $order->customer_address ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Service Details Table --}}
            <table class="table table-bordered mb-4">
                <thead class="table-light">
                    <tr>
                        <th>বিবরণ (Service Description)</th>
                        <th class="text-center" style="width: 130px;">সেবা ফি</th>
                        <th class="text-center" style="width: 130px;">সরকারি ফি</th>
                        <th class="text-end" style="width: 140px;">সর্বমোট</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong class="d-block">{{ $order->service_title }}</strong>
                            <small class="text-muted">আবেদনকারীর নাম: {{ $order->customer_name }}</small>
                        </td>
                        <td class="text-center">৳{{ number_format($order->service_fee, 2) }}</td>
                        <td class="text-center">৳{{ number_format($order->govt_fee, 2) }}</td>
                        <td class="text-end fw-bold">৳{{ number_format($order->total_fee, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">পরিশোধিত / প্রদেয় টাকা:</th>
                        <th class="text-end text-success fs-5">৳{{ number_format($order->total_fee, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">পেমেন্ট মেথড ও TrxID:</th>
                        <th class="text-end font-monospace small">
                            {{ $order->payment_method_name }}<br>
                            {{ $order->transaction_id ? 'Trx: ' . $order->transaction_id : '' }}
                            ({{ $order->payment_status_label }})
                        </th>
                    </tr>
                </tfoot>
            </table>

            {{-- Application Submitted Info --}}
            @if(!empty($order->application_data))
                <div class="p-3 border rounded mb-4 bg-light">
                    <h6 class="fw-bold mb-2">আবেদনের সারসংক্ষেপ:</h6>
                    <div class="row g-2 small">
                        @foreach($order->application_data as $k => $v)
                            <div class="col-6">
                                <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $k)) }}:</span>
                                <strong>{{ $v }}</strong>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Footer Signatures --}}
            <div class="row mt-5 pt-4 text-center">
                <div class="col-6">
                    <div class="border-top pt-2 mx-4 text-muted small">
                        গ্রাহকের স্বাক্ষর
                    </div>
                </div>
                <div class="col-6">
                    <div class="border-top pt-2 mx-4 text-muted small">
                        অপারেটর / দোকানের স্বাক্ষর ও সিল
                    </div>
                </div>
            </div>

            <div class="text-center mt-4 pt-3 border-top small text-muted">
                ধন্যবাদ! সৃজনশীল লাইব্রেরী এন্ড কম্পিউটার বেছে নেওয়ার জন্য। অনলাইনে আপনার কাজের অগ্রগতি ট্র্যাক করতে ভিজিট করুন আমাদের ওয়েবসাইট।
            </div>
        </div>
    </div>
</body>
</html>

