<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\TabeleController;
use App\Http\Controllers\DokumenteController;
use App\Http\Controllers\OBSLiveController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\InstructionController;
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

/*
Route::get('/', function () {
    return view('index');
});
*/

Route::get('/',                                                    [HomeController::class, 'index']);
Route::get('/Anfahrt',                                             [HomeController::class, 'journey']);
Route::get('/Impressum',                                           [HomeController::class, 'imprint']);
Route::get('/Information/{information}',                           [HomeController::class, 'instructionShow']);

Route::get('/Programm',                                            [ProgramController::class, 'index'])                ->name('program.index');
Route::get('/Programm/geplante',                                   [ProgramController::class, 'indexNotResult'])       ->name('program.indexNotResult');
Route::get('/Ergebnisse',                                          [ProgramController::class, 'indexResult'])          ->name('program.indexResult');

Route::get('/Bahnbelegung/{raceId}',                               [ProgramController::class, 'laneOccupancy'])        ->name('program.laneOccupancy');
Route::get('/Ergebnis/{raceId}',                                   [ProgramController::class, 'result'])               ->name('program.result');

Route::get('/Tabellen',                                            [TabeleController::class, 'index'])                 ->name('program.index');

Route::get('/Dokumente',                                           [DokumenteController::class, 'index'])              ->name('dokumente.index');

Route::get('/OBSLive/Ergebniss',                                   [OBSLiveController::class, 'result'])               ->name('obsLive.result');
Route::get('/OBSLive/Bahnbelegung',                                [OBSLiveController::class, 'laneOccupancy'])        ->name('obsLive.laneOccupancy');
