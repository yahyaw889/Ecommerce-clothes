<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PricingSetting;
use App\Models\Sosherl;

class PdfController extends Controller
{
    public function generatePDF($id)
{
    // Fetch the order with relationships
    $order = Order::with(['items', 'special'])->findOrFail($id);

    $total = $order->items->sum('total_amount') +
             $order->special->sum(fn($special) => $special->price * $special->quantity);

    $tax = PricingSetting::first();
    $social = Sosherl::query()->first();




    $data = compact('order', 'tax', 'social', 'total');

    return view('order.invoices', $data);
}

}
