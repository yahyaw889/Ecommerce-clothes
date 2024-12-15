<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Redirect;




Route::get('/invoice/{id}', [PdfController::class , 'generatePDF'])->name('invoice')->middleware('auth');
