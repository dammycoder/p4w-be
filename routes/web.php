<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\PaymentController;

use TCG\Voyager\Models\Post;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/all', function () {
    $page = Post::all();
    return $page;
});

Route::post("/webhook/paystack",  [PaymentController::class, "handleWebhook"]);


Route::get('/blogs', [PostController::class, 'get_all_blogs']);
Route::get('/blogs/{id}', [PostController::class, 'get_blog_by_id']);



Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
