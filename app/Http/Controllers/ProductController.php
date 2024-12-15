<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sosherl;
use App\Models\Special;
use App\Models\Views;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ProductController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $this->incrementViews();

        $products = Product::query()
        ->active()
        ->sorting(Sosherl::first()->sorting_product)
        ->paginate(20);

        if ($products->isEmpty()) {
            return $this->ErrorResponse('No products found', 404);
        }

        return $this->success($products, 200);
    }
    public function more_sold_products()
    {
        try{
            $this->incrementViews();

            $products = Product::query()
            ->active()
            ->latest('unit_sold')
            ->paginate(20);

            return $this->success($products, 200);

        }catch(\Exception $e){
            return $this->ErrorResponse($e->getMessage() , 404 , 'no any product');
        }
    }
    public function new_product()
    {


        try{
            $this->incrementViews();

            $products = Product::query()
            ->latest('created_at')
            ->active()
            ->paginate(20);

            return $this->success($products, 200);


        }catch(\Exception $e){
            return $this->ErrorResponse($e->getMessage() , 404 , 'no any product');
        }


    }


    public function high_views_product()
    {
        try{
        $products = Product::query()
        ->latest('views')
        ->active()
        ->paginate(20);

        return $this->success($products, 200);
        }catch(\Exception $e){
            return $this->ErrorResponse($e->getMessage() , 404 );
        }
    }

    public function show(string $id)
    {
        $product = Product::query()
            ->with(['categore','brand'])
            ->active()
            ->where( 'id', $id)
            ->first();

        if ($product) {
            $product->increment('views');
            return $this->success( $product, 200);
        } else {
            return $this->ErrorResponse('Product not found', 404);
        }
    }


    public function search(Request $request)
    {
        try {
            $validated = $request->validate([
                'search' => 'required|string',
            ]);

            $search = $validated['search'];

            $products = Product::query()
                ->where( function($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
                })
                ->active()
                ->latest('views')
                ->paginate(20);

            if ($products->isEmpty()) {
                return $this->ErrorResponse('No products found for your search.', 404);
            }

            return $this->success($products , 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->ErrorResponse($e->getMessage(), 400 , $e->errors());
        } catch (\Exception $e) {
            return $this->ErrorResponse($e->getMessage(), 500 ,'An unexpected error occurred.' );
        }
    }


    public function filter(Request $request){
        try {
                    $validated = $request->validate($this->validateFilter());

                    $filters = $request->only(['priceFrom', 'priceTo', 'name', 'category', 'brand']);

                    $products = Product::query()
                        ->active()
                        ->filter($filters)
                        ->latest('price')
                        ->paginate(20);

                    if ($products->isEmpty()) {
                        return $this->success([], 200 , 'No products found for your filter.');
                    }

                    return $this->success($products , 200);

                } catch (\Illuminate\Validation\ValidationException $e) {

                    return $this->ErrorResponse($e->getMessage(), 400 , $e->errors());
                } catch (\Exception $e) {
                    return $this->ErrorResponse($e->getMessage(), 500 ,'An unexpected error occurred.' );
                }
    }

    private function incrementViews()
    {
        $views = Views::first();
        if ($views) {
            $views->increment('views');
        }
    }

    private function validateFilter(){
        return [
            'priceTo' => 'nullable|integer',
            'priceFrom' => 'nullable|integer',
            'name' => 'nullable|string',
            'category' => 'nullable|array',
            'category.*' => 'exists:categories,id',
            'brand'    => 'nullable|array',
            'brand.*'    => 'exists:brands,id',
        ];
    }
}
