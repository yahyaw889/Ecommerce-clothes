<!DOCTYPE html>
<meta charset="UTF-8">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        @media print {
            @page {
                margin: 0;
                size: 210mm 297mm;
            }

            body {
                margin: 0;
            }
        }

        body {
            background-color: #f3f4f6;
            padding: 40px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.2;
            margin: 0;
        }

        .container {
            max-width: 1024px;
            margin: 0 auto;
            background-color: #fff;
            padding: 32px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }

        .header {
            display: flex;
            align-items: center;
            border-top: 8px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .logo {
            width: 140px;
            /* margin-right: 20px; */
            margin-top: 10px;
        }

        .store-info {
            margin-left: -100px;
            flex: 1;
            text-align: center;
        }

        .store-title {
            font-size: 2rem;
            font-weight: bold;
            color: #2563eb;
        }

        .contact-info {
            font-size: 0.875rem;
            color: #666;
            font-weight: 600;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table th {
            background-color: #2563eb;
            color: #fff;
            font-weight: bold;
        }

        .table tfoot td {
            font-weight: bold;
            background-color: #f9fafb;
        }

        .table tfoot tr:last-child td {
            background-color: #2563eb;
            color: #fff;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #666;
            align-items: center;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer .thank-you {
            font-size: 1rem;
            font-weight: bold;
            color: #2563eb;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .container {
                box-shadow: none;
                border: none;
                padding: 16px;
            }

            .header {
                margin-bottom: 20px;
                padding-bottom: 10px;
            }

            .store-title {
                font-size: 1.5rem;
            }

            .section-title {
                font-size: 0.875rem;
            }

            .table th,
            .table td {
                padding: 6px;
            }

            .footer {
                margin-top: 10px;
            }

            .footer .thank-you {
                font-size: 0.875rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            {{-- @dd(file_exists(public_path('storage/images/1.jpg'))); --}}
            <img src="{{ url('public/storage/' . $social->image ) }}" alt="Logo" class="logo">
            <div class="store-info">
                <h1 class="store-title">ARTIVA STORE</h1>
                <p class="contact-info">Phone number: {{ $social->phone }}</p>
            </div>
        </div>
        <hr>

        <!-- Invoice Info -->
        <div class="section">
            <h2 class="section-title">Invoice Details</h2>
            <p>Invoice #: {{$order->invoice_number}}</p>
            <p>Date: {{ $order->created_at }}</p>
        </div>
        <hr>
        <!-- Bill To -->
        <div class="section">
            <h2 class="section-title">Bill To</h2>
            <p><strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>

            <p><strong>Address:</strong> {{ $order->address }} , {{ $order->country }}</p>
            <p><strong>Phone:</strong> {{ $order->phone }}</p>
        </div>

        <!-- Items Table -->
        <div class="section">
            <h2 class="section-title">Items</h2>
            <hr>
            <table class="table">
                <thead>
                    <tr>
                        <th>Item Description</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product->slug }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="text-right">${{ $item->unit_amount }}</td>
                            <td class="text-right">${{ $item->total_amount }}</td>
                        </tr>
                    @endforeach
                    @foreach ($order->special as $special)
                        <tr>
                            <td>{{ $special->name }}</td>
                            <td>{{ $special->quantity }}</td>
                            <td class="text-right">${{ $special->price }}</td>
                            <td class="text-right">${{ $special->price * $special->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Subtotal</td>
                        <td>${{ $total }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">Additional Costs</td>
                        <td>${{ $tax->tax }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">Total Cost</td>
                        <td>${{ $total + $tax->tax }}</td>
                    </tr>
                </tfoot>
            </table>
            <p>If you have any questions concerning this invoice, use the following contact information:</p>
            <p>Contact: {{ $social->email }}</p>
            <p class="thank-you">THANK YOU!</p>
        </div>

         
    </div>
</body>

</html>
