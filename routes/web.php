<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('Welcome');
// });

/*Route::get('/', [PostController::class, 'index'])->name('posts.index');

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/create/', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.view');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
// Route::post('/posts/edit', [PostController::class, 'update'])->name('posts.update');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
// Route::get('/posts/delete/{post}', [PostController::class, 'delete'])->name('posts.delete');
Route::delete('/posts/delete/{post}', [PostController::class, 'delete'])->name('posts.delete');
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');*/

Auth::routes();

Route::middleware('auth')->group(function () {

    Route::get('/', [PostController::class, 'index'])->name('posts.index');

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

    Route::get('/posts/create/', [PostController::class, 'create'])->name('posts.create');

    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.view');

    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');

    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');

    Route::delete('/posts/delete/{post}', [PostController::class, 'delete'])->name('posts.delete');

    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('github.auth');

Route::get('/auth/callback', function () {
    $githubUser = Socialite::driver('github')->user();
// dd($githubUser);

    $user = User::where('github_id', $githubUser->id)->first();
    if ($user) {
        $user->update([
            'github_token' => $githubUser->token,
            'github_refresh_token' => $githubUser->refreshToken,
        ]);
    } else {
        $user = User::create([
            'name' => $githubUser->name,
            'email' => $githubUser->email,
            'password'=>$githubUser->token,
            'github_id' => $githubUser->id,
            'github_token' => $githubUser->token,
            'github_refresh_token' => $githubUser->refreshToken,
        ]);
    }

    Auth::login($user);

    return redirect('/posts');
});
