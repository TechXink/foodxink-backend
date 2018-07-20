<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\YueDanResource;

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

# 用户点击登录按钮时请求的地址
Route::post('/auth/oauth', 'Auth\AuthController@oauth');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->middleware('auth:api')->group(function () {
    Route::apiResources([
            'yuedan' => 'API\YueDanController',
            'participator' => 'API\ParticipatorController'
    ]);

    Route::get('yuedans', function () {
        $user = Auth::guard('api')->user();
        return YueDanResource::collection(\App\YueDan::byParticipator($user['id'])->simplePaginate(5));
    });

    Route::post('yuedan/uploadimg', 'API\YueDanController@uploadImg');
});
