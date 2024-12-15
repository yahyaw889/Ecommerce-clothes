<?php

namespace App\Http\Controllers;

use App\Models\ProductLove;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductLoveController extends Controller
{
    use ResponseTrait;


    public function index(Request $request){
        $productLoves = ProductLove::with(['product'])->where('user_id' , $request->user()->id)
        ->paginate(20);
        if(!$productLoves->total()){
            return $this->ErrorResponse( 'No product loved yet' , 404);
        }
        return $this->success($productLoves, 200);
    }

    public function store(Request $request){

        $validation = Validator::make($request->all(), [
            'product_id' => 'required|string|max:255|exists:products,id',
        ]);
        if ($validation->fails()) {
            return $this->ErrorResponse($validation->errors(), 422);
        }

        try{

        if(ProductLove::query()
        ->where('product_id' , $request->product_id)
        ->where('user_id' , $request->user()->id)
        ->exists()
        ){
            return $this->ErrorResponse( 'You have already loved this product' , 422);
        }

        ProductLove::create([
            'product_id' => $request->product_id,
            'user_id' => $request->user()->id
        ]);
        return $this->success( [] , 201 , 'Product loved successfully');

        }catch(\Exception $e){
        return $this->ErrorResponse( $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request){
        $validation = Validator::make($request->all(), [
            'product_id' => 'required|string|max:255|exists:products,id',
        ]);
        if ($validation->fails()) {
            return $this->ErrorResponse($validation->errors(), 422);
        }
        try{
        $ProductLove = ProductLove::where('product_id' , $request->product_id)->where('user_id' , $request->user()->id);
        if(!$ProductLove){
            return $this->ErrorResponse( 'You have not loved this product' , 422);
        }

            $ProductLove->delete();
            return $this->success( [] ,201 , 'Product unloved successfully');

        }catch(\Exception $e){
        return $this->ErrorResponse( message: $e->getMessage(), code: 500);
        }
    }


}
