<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'localization',
    'prefix' => 'challenge'
], function () {
    Route::post('/create', 'ChallengeController@createChallenge');
    Route::get('/getAll', 'ChallengeController@getAllChallenge');
    Route::get('/{id}', 'ChallengeController@getChallenge');
    Route::post('/{id}/submitVideo', 'ChallengeController@submitVideo');
    Route::post('/video/{video_id}/rate', 'ChallengeController@voteVideo');

});
