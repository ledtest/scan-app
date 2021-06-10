<?php

use App\Http\Controllers\PostController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ScanOutController;

use Illuminate\Http\Request;

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

Route::get('/', function () {
    return Inertia::render('Index', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/scan/samsung/add', [ScanOutController::class, 'add']);
Route::get('/scan/samsung/index', [ScanOutController::class, 'renderSamsungOutIndex']);

Route::middleware('auth:sanctum')->get('/scan/samsung/form', [ScanOutController::class, 'renderSamsungOutForm'])->name('samsungScanForm');

Route::middleware('auth:sanctum')->post('/scan/samsung/form', [ScanOutController::class, 'renderSamsungScan']);

Route::post('/scan/samsung', [ScanOutController::class, 'checkSamsungOut']);

//Route::post('/scan/samsung/delete/{id}', [ScanOutController::class, 'samsungDeleteGroup']);
Route::delete('/scan/samsung/form', [ScanOutController::class, 'samsungDeleteGroup']);

Route::post('/scan/samsung/print', [ScanOutController::class, 'samsungPrint']);
Route::post('/scan/samsung/forcePrint', [ScanOutController::class,'samsungForcePrint']);
Route::post('/scan/samsung/exportExcel', [ScanOutController::class, 'samsungExportExcel']);

Route::get('/scan/samsung/next', [ScanOutController::class, 'scanNextBox']);

Route::post('/samsung/type', [ScanOutController::class, 'postSamsungType']);

Route::middleware(['auth:sanctum', 'verified'])->resource('posts', PostController::class);

Route::get('storage/{filename}', function ($filename)
{
    $path = storage_path($filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('storage/app/{filename}', function ($filename)
{
    $path = storage_path('app/'.$filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
