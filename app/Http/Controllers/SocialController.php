<?php

namespace App\Http\Controllers;

use App\Models\Sosherl;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    use ResponseTrait;


    public function index(){
    $social =   Sosherl::first();
    if(!$social){
        return $this->ErrorResponse('No social media found' , 404);
    }
    unset($social->sorting_product);
    unset($social->quantity);
    return $this->success($social);
    }
}
