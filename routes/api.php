<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\StreamingController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/ping', function() {
    return ['ponng' => true];
});
/** movie route */
Route::get('/movies/average-rating', [MovieController::class, 'averageRating']);
Route::get('/movies/average-ratings-by-genre-and-year', [MovieController::class, 'averageRatingsByGenreAndYear']);
Route::get('/movies/by-rating', [MovieController::class, 'findMoviesByRating']);
Route::get('/movies/by-year', [MovieController::class, 'moviesByYear']);
Route::resource('/movies', MovieController::class);
Route::get('/movies/{id}/streaming-count', [MovieController::class, 'streamingCount']);
Route::post('/movies/{id}/rate', [MovieController::class, 'rateMovie']);
Route::put('/movies/{id}/associate-streamings', [MovieController::class, 'associateStreamings']);
/**genre route */
Route::resource('/genre', GenreController::class);
/**streaming route */
Route::resource('/streaming', StreamingController::class);
/**user route */
Route::resource('/user', UserController::class);
