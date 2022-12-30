<?php

use App\Http\Controllers\User\FileController;
use App\Http\Controllers\User\FolderController;
use App\Http\Controllers\User\StorageController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/dashboard', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified', 'user', 'active'])
    ->prefix('my-storage')
    ->group(function () {
        Route::controller(StorageController::class)
            ->name('storage.')
            ->group(function () {
                Route::get('/', 'root')->name('root');

                Route::get('/folder/{folder:encoded_name}', 'folder')->name('folder');

                Route::get('/search', 'search')->name('search');
            });

        Route::controller(FolderController::class)
            ->prefix('folder')
            ->name('folder.')
            ->group(function () {
                Route::post('/{parent:encoded_name?}', 'store')->name('store');

                Route::put('/{folder:encoded_name}', 'update')->name('update');

                Route::delete('/{folder:encoded_name}', 'destroy')->name('destroy');
            });

        Route::controller(FileController::class)
            ->prefix('files')
            ->name('files.')
            ->group(function () {
                Route::get('/create/{folder:encoded_name?}', 'create')->name('create');

                Route::post('/{folder:encoded_name?}', 'store')->name('store');

                Route::put('/{file:encoded_name}', 'update')->name('update');

                Route::delete('/{file:encoded_name}', 'destroy')->name('destroy');

                Route::get('/{file:encoded_name}/download', 'download')->name('download');

                Route::get('/{file:encoded_name}/share', 'share')->name('share');

                Route::middleware('signed')
                    ->withoutMiddleware(['auth', 'verified', 'user', 'active'])
                    ->group(function () {
                        Route::get('/{shared:encoded_name}', 'show')->name('show');

                        Route::get('/{shared:encoded_name}/download-shared', 'downloadShared')
                            ->name('downloadShared');
                    });
            });
    });

require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
