<?php


use App\Http\Controllers\Api\V1\CrosswordController;
use App\Http\Controllers\Api\V1\DictionaryController;
use App\Http\Controllers\Api\V1\LettersController;
use App\Http\Controllers\Api\V1\MemoryGameController;
use App\Http\Controllers\Api\V1\PlanController;
use App\Http\Controllers\Api\V1\QuizController;
use App\Http\Controllers\Api\V1\QuizResultController;
use App\Http\Controllers\Api\V1\SectionController;
use App\Http\Controllers\Api\V1\SpellingController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\SyllabusController;
use App\Http\Controllers\Api\V1\ThemeController;
use App\Http\Controllers\Api\V1\UsersController;
use App\Http\Controllers\Api\V1\VerifyCodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::middleware('auth:sanctum')->apiResource('syllabus', SyllabusController::class);
Route::middleware('auth:sanctum')->apiResource('users', UsersController::class);
Route::middleware('auth:sanctum')->apiResource('sections', SectionController::class);
Route::middleware('auth:sanctum')->apiResource('themes', ThemeController::class);
Route::middleware('auth:sanctum')->apiResource('quiz', QuizController::class);
Route::middleware('auth:sanctum')->apiResource('spellings', SpellingController::class);
Route::middleware('auth:sanctum')->apiResource('dictionary', DictionaryController::class);
Route::middleware('auth:sanctum')->apiResource('quiz-results', QuizResultController::class);
Route::middleware('auth:sanctum')->apiResource('plans', PlanController::class);
Route::middleware('auth:sanctum')->apiResource('subscriptions', SubscriptionController::class);
Route::middleware('auth:sanctum')->apiResource('memory-game', MemoryGameController::class);
Route::middleware('auth:sanctum')->apiResource('letters', LettersController::class);
Route::middleware('auth:sanctum')->apiResource('crosswords', CrosswordController::class);
Route::middleware('auth:sanctum')->apiResource('verify-code', VerifyCodeController::class);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');






