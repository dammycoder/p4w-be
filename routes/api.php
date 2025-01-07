<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PostController;
use TCG\Voyager\Models\Page;
use App\Models\University;

use Illuminate\Http\Request;

Route::get('/all', function () {
    $page = Page::all();
    return $page;
});

Route::post('/volunteer', [VolunteerController::class, 'submit_volunteer_form']);
Route::post('/subscribe', [SubscriberController::class, 'subscribe']);
Route::post('/contact', [ContactUsController::class, 'store']);
Route::get('/blogs', [PostController::class, 'get_all_blogs']);
Route::get('/blogs/{id}', [PostController::class, 'get_blog_by_id']);
Route::get('/jobs', [JobController::class, 'get_all_jobs']);
Route::get('/jobs/{id}', [JobController::class, 'get_job_by_id']);
Route::any("/webhook/paystack", [PaymentController::class, "handleWebhook"]);
Route::get('/institutions', function (Request $request) {
    $query = $request->query('query');
    $results = University::orwhere('country', 'like', '%' . $query . '%')
        ->limit(10) 
        ->pluck('country');

    return response()->json(['data' => $results]);
});
Route::post('/job/apply', [JobController::class, 'submit_job_application']);

