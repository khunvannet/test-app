<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TestCaseController;
use App\Http\Controllers\TestRunController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// routes for projects
Route::get('/projects', [ProjectController::class, 'getAllProject']);
Route::get('/SelectProject', [ProjectController::class, 'getSelectProject']);
Route::post('/projects', [ProjectController::class, 'add']);
Route::put('/projects/{id}', [ProjectController::class, 'update']);
Route::delete('/projects/{id}', [ProjectController::class, 'delete']);
Route::get('/projects/isExist', [ProjectController::class, 'isExist']);
Route::get('/projects/{id}', [ProjectController::class, 'getProjectById']);



// routes for main
Route::get('/main', [MainController::class, 'getAllMain']);
Route::get('/main/isExist', [MainController::class, 'isExist']);
Route::post('/main', [MainController::class, 'store']);
Route::put('/main/{id}', [MainController::class, 'update']);
Route::delete('/main/{id}', [MainController::class, 'delete']);

// routes for test cases
Route::get('/test', [TestCaseController::class, 'getAllTestCase']);
Route::get('/selectTree', [TestCaseController::class, 'getTestCase']);
Route::get('/test/isExist', [TestCaseController::class, 'isExist']);
Route::post('/test', [TestCaseController::class, 'store']);
Route::put('/test/{id}', [TestCaseController::class, 'update']);
Route::delete('/test/{id}', [TestCaseController::class, 'delete']);

//testrun
Route::get('/test/run', [TestRunController::class, 'getAllRun']);
Route::get('/test/run/close', [TestRunController::class, 'getCloseRun']);
Route::post('/test/run', [TestRunController::class, 'store']);
Route::post('/test/run/close/{id}', [TestRunController::class, 'closeRun']);
Route::post('/test/run/active/{id}', [TestRunController::class, 'activeRun']);
Route::put('/test/run/{id}', [TestRunController::class, 'update']);
Route::delete('/test/run/{id}', [TestRunController::class, 'destroy']);
Route::get('/test/run/isExist', [TestRunController::class, 'isExist']);