<?php

use App\Livewire\Landing;
use App\Livewire\Report;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', Landing::class)->name('landing');

Route::get('/report/{report:public_id}', Report::class)->name('report');

Route::get('/raid', \App\Livewire\RaidSelect::class)->name('raid-select');

Route::get('/raid/{raid:slug}', \App\Livewire\EncounterSelect::class)->name('encounter-select');

Route::get('/raid/{raid:slug}/encounter/{encounter:slug}', fn () => 'TODO')->name('encounter');

Route::get('/test', function () {
    return \App\Models\ReportRawData::first()->data->sim;
});
