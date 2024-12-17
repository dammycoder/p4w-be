<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VolunteerController;

use TCG\Voyager\Models\Post;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/all', function () {
    $page = Post::all();
    return $page;
});


Route::get('/blogs', [PostController::class, 'get_all_blogs']);


Route::get('/send-test-email', function () {
    $data = ['message' => 'This is a test email'];
    
    try {
        $data = ['name' => 'Emmanuel'];
    
       $testing =  Mail::raw('This is a test email without a view.', function ($message) {
            $message->to('gabrielpromise18@gmail.com', 'Test User')
                    ->subject('Test Email from Laravel');
        });
    
    } catch (\Exception $e) {
        dd($e);  // Display error if something fails
    }

    return 'Test email sent!';
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
