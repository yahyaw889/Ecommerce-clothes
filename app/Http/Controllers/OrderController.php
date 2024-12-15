<?php

namespace App\Http\Controllers;

use App\Jobs\MailNotification;
use App\Jobs\TelegramNotification;
use App\Mail\OrderShipped;
use App\Models\LoginUser;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\OrderUser;
use App\Models\PricingSetting;
use App\Models\Product;
use App\Models\Special;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
class OrderController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $key = request()->ip(); // Or use auth()->id() for authenticated users

        if (RateLimiter::tooManyAttempts($key, 1)) {
            // Calculate retry time
            $retryAfter = RateLimiter::availableIn($key);

            throw new ThrottleRequestsException(
            "Too many requests. Please wait {$retryAfter} seconds."
            );
        }

        // Allow request and set the cooldown for 1 minute
        RateLimiter::hit($key, 60); // The second parameter is the expiration time in seconds
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = $this->validateOrder($request);
        if ($validation->fails()) {
            return $this->ErrorResponse($validation->errors(), 422);
        }

        try {
            if (!empty($request->items)) {
                $this->checkProductQuantity($request->items);
            }

            $order = $this->createOrder($request);

            if (!empty($request->special_items)) {
                $this->processSpecialItems($request->special_items, $order);
            }

            if (!empty($request->items)) {
                $this->processOrderItems($request->items, $order);
            }

            if($request->user()){
                $this->createOrderUser($request->user()->id , $order->id);
            }

            $this->notifyUser($order);

            return $this->success(null, 201, 'Order created successfully');
        } catch (\Exception $e) {
            return $this->ErrorResponse($e->getMessage(), 500);
        }
    }

    public function showTax()
    {

        try {
            $tax = PricingSetting::first() ?? null;

            return $this->success($tax, 200);
        } catch (\Exception $e) {
            return $this->ErrorResponse($e->getMessage(), 500);
        }
    }








    private function validateOrder($request)
    {
        return Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'country' => 'required',
            'address' => 'required',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required|string|max:255|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.color' => 'required|string|max:255',
            'items.*.size' => 'required|string|max:255',
            'special_items' => 'nullable|array',
            'special_items.*.name' => 'required|string|max:255',
            'special_items.*.quantity' => 'required|integer|min:1',
            'special_items.*.image' => 'required|array|max:2',
            'special_items.*.image.*' => 'image|mimes:jpg,jpeg,png|max:4096',
            'special_items.*.size' => 'required|string|max:255',
            'special_items.*.color' => 'required|string|max:255',
        ]);
    }

    private function createOrder($request)
    {
        $invoice_number = rand(1000, 99999999);
            $exists = Order::query()->where( 'invoice_number' , $invoice_number )->exists();
            while($exists){
                $invoice_number = rand(1000, 99999999);
                $exists = Order::query()->where( 'invoice_number' , $invoice_number )->exists();
            }

        return Order::create([
            'invoice_number' => $invoice_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country' => $request->country,
            'address' => $request->address,
        ]);
    }

    private function processSpecialItems($specialItems, $order)
{
    $price = PricingSetting::query()->first();

    foreach ($specialItems as $specialItem) {
        $totalPrice = $this->calculateSpecialItemPrice($specialItem['image'], $price);

        $imagePaths = $this->uploadImages($specialItem['image'], 'special'); // Use

        Special::create([
            'name' => $specialItem['name'],
            'quantity' => $specialItem['quantity'] ?? 1,
            'image' => ($imagePaths),
            'size' => $specialItem['size'],
            'color' => $specialItem['color'] ?? 'white',
            'order_id' => $order->id,
            'price' => $totalPrice,
            'status' => 1
        ]);
    }
}

private function calculateSpecialItemPrice($images, $price)
{
    $totalPrice = 0;
    $counter = 0;
    foreach ($images as $image) {
        if ($counter == 0 ) {
            $totalPrice += $price->model_price;
        }
        if ($counter == 1) {
            $totalPrice += $price->additional_pricing;
        }
        $counter++;
    }


    return $totalPrice;
}

private function uploadImages($images, $folder)
{
    $paths = [];
    foreach ($images as $image) {
        // Store images in the 'special' folder instead of 'products'
        $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
        $paths[] = $image->storeAs('special', $imageName, 'public'); // Changed 'products' to 'special'
    }
    return $paths;
}



    private function processOrderItems($items, $order)
    {
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);

            if (!$product) {
                throw new \Exception('Product not found');
            }

            $unitAmount = $product->price -  ($product->discount );
            $totalAmount = $unitAmount * ($item['quantity'] ?? 1);

            OrderItems::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'] ?? 1,
                'color' => $item['color'],
                'size' => $item['size'],
                'unit_amount' => $unitAmount,
                'total_amount' => $totalAmount,
            ]);
        }
    }

    private function notifyUser($order)
    {
        $subject = 'Order Confirmation';
        $users = User::all();

        MailNotification::dispatch($order);
        TelegramNotification::dispatch($order , $users);


    dispatch(function () use ($order ,  $users) {
        FilamentNotification::make()
            ->title('New Order '. ' - ' . $order->invoice_number )
            ->body("Order for {$order->first_name} {$order->last_name} has been created.")
            ->sendToDatabase($users);
        });

    }

    private function createOrderUser( $user_id, $order_id){

        $user = OrderUser::query()->create([
            'user_id' => $user_id,
            'order_id'=> $order_id
        ]);

    }

    private function checkProductQuantity($items)
    {
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);

            if (!$product) {
                throw new \Exception('Product not found');
            }

            if ($product->quantity < $item['quantity']) {
                throw new \Exception("Not enough quantity of product with id {$item['product_id']}");
            }
        }
    }
    /**
     * Display the specified resource.
     */


}
