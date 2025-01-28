<?php

use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


Route::get('/{any}', function () {
    return file_get_contents(public_path('../../frontend/index.html'));
})->where('any', '.*');

Route::get('/invoice/{id}/show', [PdfController::class , 'downloadPdf'])->name('invoice_download')->middleware('auth');

Route::get('/invoice/{id}', [PdfController::class , 'generatePDF'])->name('invoice')->middleware('auth');



// Route::get('refresh', function () {
//     Artisan::call('view:clear');
//     Artisan::call('cache:clear');
//     Artisan::call('config:clear');
//     Artisan::call('filament:optimize-clear');
//     Artisan::call('filament:optimize');

//     return ;
// });


