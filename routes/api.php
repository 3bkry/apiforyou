<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiGenerateController;

// Route to generate image (receiving request to start image generation)
Route::post('ai/generate/image', [AiGenerateController::class, 'generateImage']);

// Route to check the status of the image generation
Route::get('ai/generate/image/{content_id}/status', [AiGenerateController::class, 'checkStatus']);

// Route to receive the image URL once the image generation is completed
Route::post('ai/generate/image/{content_id}/done', [AiGenerateController::class, 'imageGenerationDone']);
