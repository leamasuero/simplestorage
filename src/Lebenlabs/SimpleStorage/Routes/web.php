<?php
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


use Lebenlabs\SimpleStorage\Http\Controllers\ArchivosController;


Route::group(['prefix' => 'simplestorage', 'as' => 'simplestorage.'], function () {

    Route::get('/archivos/{id}', [
        'uses' => ArchivosController::class . "@show",
        'as' => 'archivos.show'
    ]);

});


