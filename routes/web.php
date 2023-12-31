<?php

use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RawReportController;
use App\Http\Controllers\SimCController;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Livewire\Acp\Dashboard;
use App\Livewire\Acp\Teams;
use App\Livewire\CharacterProfile;
use App\Livewire\EncounterSelect;
use App\Livewire\Landing;
use App\Livewire\Login;
use App\Livewire\LootOverview;
use App\Livewire\RaidSelect;
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

Route::get('/raid', RaidSelect::class)->name('raid-select');

Route::get('/raid/{raid:slug}', EncounterSelect::class)->name('encounter-select');

Route::get('/raid/{raid:slug}/encounter/{encounterSlug}', LootOverview::class)->name('encounter');

Route::get('/characters/{character:public_id}', CharacterProfile::class)->name('character-profile');

Route::get('/raw/{report:public_id}', RawReportController::class)->name('raw-report');
Route::get('/raw/{report:public_id}/simc', SimCController::class)->name('simc');

Route::middleware(RedirectIfAuthenticated::class)->get('/login', Login::class)->name('login');

Route::middleware('auth')->prefix('/acp')->name('admin.')->group(function () {
    Route::post('/logout', LogoutController::class)
        ->name('logout');

    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/teams', Teams::class)->name('teams');

});
