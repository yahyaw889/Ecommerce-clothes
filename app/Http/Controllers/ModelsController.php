<?php

namespace App\Http\Controllers;

use App\Models\ModelName;
use App\Models\Models;
use App\Models\PricingSetting;
use App\Models\Sosherl;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ModelsController extends Controller
{
    use ResponseTrait;
    public function index()
{
    try {
        $models = ModelName::query()->where('status', 1)->get();
        $allmodels = [];
        foreach ($models as $model) {
            $items = Models::where('model_name_id', $model->id)->get();
            foreach ($items as $item) {
                $item->image_forward = $this->accessorImages($item->image_forward);
                $item->image_back = $this->accessorImages($item->image_back);
            }
            $allmodels[$model->name] = $items;
        }

        // Fetch pricing information
        $pricing = PricingSetting::query()->first();

        if ($models->isEmpty()) {
            return $this->ErrorResponse('No models found.', 404, 'Models not found.');
        }
        if (!$pricing) {
            return $this->ErrorResponse('Pricing information not found.', 404, 'Pricing not found.');
        }



        $data = [
            'pricing' => $pricing->model_price,
            'additional_pricing' => $pricing->additional_pricing,
            'models' => $allmodels,
        ];

        // Return success response
        return $this->success($data, 200, 'Models fetched successfully.');
    } catch (\Throwable $exception) {
        Log::error('Error fetching models: ' . $exception->getMessage());

        return $this->ErrorResponse(
            'An unexpected error occurred.',
            500,
            $exception->getMessage()
        );
    }
}

protected function accessorImages(?string $value): string
{
        return asset('storage/' . $value);
}




}
