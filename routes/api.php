<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\PostController;
use TCG\Voyager\Models\Page;

Route::get('/all', function () {
    $page = Page::all();
    return $page;
});

Route::post('/volunteer', [VolunteerController::class, 'submit_volunteer_form']);
Route::post('/subscribe', [SubscriberController::class, 'subscribe']);
Route::post('/contact', [ContactUsController::class, 'store']);
Route::get('/blogs', [PostController::class, 'get_all_blogs']);




?>