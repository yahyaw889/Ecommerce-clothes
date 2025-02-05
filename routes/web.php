<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;



Route::get('/invoice/{id}/show', [PdfController::class , 'downloadPdf'])->name('invoice_download')->middleware('auth');

Route::get('/invoice/{id}', [PdfController::class , 'generatePDF'])->name('invoice')->middleware('auth');



