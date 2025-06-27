<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    use ResponseTrait;

    public function index()
    {
        $categories = Category::query()
            ->where('status', 1)
            ->get();

        if ($categories->isEmpty()) {
            return $this->ErrorResponse('No Categories found', 404, 'No Categories found');
        }

        foreach ($categories as $cate) {
            $cate->image = $cate->image ?  asset('storage/' . $cate->image) : null;
        }

        return $this->success($categories, 200);
    }

    public function brand(){
        $brand =Brand::where('status' , 1 )->paginate(10);
        if(!$brand){
            return $this->ErrorResponse('No Brand found', 404, 'No Brand found');
        }

        return $this->success($brand);
    }


    protected function accessorImages(?array $value): array
    {
        $lastArray = [];

        if (is_array($value)) {
            foreach ($value as $image) {
                $lastArray[] = asset('storage/' . $image);
            }
        }else{
            $lastArray[] = asset('storage/' . $value);
        }

        return $lastArray;
    }

}
