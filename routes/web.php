<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\TabeleController;
use App\Http\Controllers\DokumenteController;
use App\Http\Controllers\OBSLiveController;
use App\Http\Controllers\SpeekerController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\InstructionController;
use App\Http\Controllers\PresentationController;
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

Route::get('/Tabellen',                                            [TabeleController::class, 'index'])                 ->name('table.index');
Route::get('Tabelle/{tableId}',                                    [TabeleController::class, 'show'])                  ->name('table.show');

Route::get('/Dokumente',                                           [DokumenteController::class, 'index'])              ->name('dokumente.index');

Route::get('/OBSLive/Ergebniss',                                   [OBSLiveController::class, 'result'])               ->name('obsLive.result');
Route::get('/OBSLive/Bahnbelegung',                                [OBSLiveController::class, 'laneOccupancy'])        ->name('obsLive.laneOccupancy');
Route::get('/OBSLive/Naechstesrennen',                             [OBSLiveController::class, 'nextRace'])             ->name('obsLive.nextRace');
Route::get('/OBSLive/Aktuellesrennen',                             [OBSLiveController::class, 'currentRace'])          ->name('obsLive.currentRace');
Route::get('/OBSLive/Ergebnissall',                                [OBSLiveController::class, 'resultall'])            ->name('obsLive.resultall');

Route::get('/Sprecher/{speekerId?}',                               [SpeekerController::class, 'show'])                 ->name('speeker.show');
Route::post('/Sprecher/Auswahl',                                   [SpeekerController::class, 'choose'])               ->name('speeker.choose');
Route::get('/Sprecher/Mannschaft/{teamId}/{raceId}',               [SpeekerController::class, 'teamShow'])             ->name('speeker.teamShow');
Route::get('/Sprecher/Tabelle/{tableId}/{raceId}',                 [SpeekerController::class, 'tabeleShow'])           ->name('speeker.tabeleShow');
Route::post('/Sprecher/Mannschaft/Auswahl',                        [SpeekerController::class, 'teamChoose'])           ->name('speeker.teamChoose');
Route::post('/Sprecher/Tabellen/Auswahl',                          [SpeekerController::class, 'tableChoose'])          ->name('speeker.tableChoose');

Route::get('/Mannschaftsfilter',                                   [ProgramController::class, 'selectTeamFilter'])->name('program.selectTeamFilter');
Route::post('/Mannschaftsfilter/aktiv',                            [ProgramController::class, 'setTeamFilter'])->name('program.setTeamFilter');

Route::get('/Praesentation',                                       [PresentationController::class, 'welcome'])->name('presentation.welcome');
Route::get('/Praesentation/Information',                           [PresentationController::class, 'information'])->name('presentation.information');
Route::get('/Praesentation/Mannschaft',                            [PresentationController::class, 'teams'])->name('presentation.teams');
Route::get('/Praesentation/Mannschaftssteckbrief',                 [PresentationController::class, 'teamProfile'])->name('presentation.teamProfile');
Route::get('/Praesentation/Bahnaufstellung',                       [PresentationController::class, 'laneOccupancy'])->name('presentation.laneOccupancy');
Route::get('/Praesentation/Ergebnis',                              [PresentationController::class, 'result'])->name('presentation.result');
Route::get('/Praesentation/Ergebnis/Neu/{raceId}',                 [PresentationController::class, 'newResult'])->name('presentation.newResult');
Route::get('/Praesentation/Tabelle/Neu/{tableId}',                 [PresentationController::class, 'newTable'])->name('presentation.newTable');
Route::get('/Praesentation/Tabelle',                               [PresentationController::class, 'table'])->name('presentation.table');
Route::get('/Praesentation/Video',                                 [PresentationController::class, 'video'])->name('presentation.video');
