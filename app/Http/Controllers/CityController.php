<?php

namespace App\Http\Controllers;

use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        try {
            $cities = \App\Models\City::where('status' , 1)->get() ?? null;
            return $this->success($cities);
        } catch (\Throwable $th) {
            return $this->ErrorResponse('Something went wrong', 500, 'Error while fetching cities');
        }
    }
}
