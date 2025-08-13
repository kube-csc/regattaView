@extends('layouts.presentation')

@section('title', 'Neue Tabelle')

@php
    $tableIndex = request()->query('table', 0);
    $tableCount = count($tables);
    $table = $tables[$tableIndex] ?? null;

    // Paginierung für Platzierungen
    $platzPage = (int) request()->query('platzPage', 1);
    $platzProSeite = 12;
    $tabeledataShows = $table ? ($table->tabeledataShows ?? []) : [];
    // Sortierung: Punkte absteigend, dann Buchholzzahl absteigend
    $sorted = collect($tabeledataShows)->sort(function($a, $b) {
        if ($a->punkte != $b->punkte) {
            return $b->punkte <=> $a->punkte;
        }
        return ($b->buchholzzahl ?? 0) <=> ($a->buchholzzahl ?? 0);
    })->values();

    $gesamtPlatzierungen = $sorted->count();
    $gesamtSeiten = max(1, ceil($gesamtPlatzierungen / $platzProSeite));
    $platzPage = max(1, min($platzPage, $gesamtSeiten));
    $start = ($platzPage - 1) * $platzProSeite;
    $platzierungenSeite = $sorted->slice($start, $platzProSeite);

    // Für Slideshow: nächste Seite oder nächste Tabelle
    if ($platzPage < $gesamtSeiten) {
        $nextUrl = route('presentation.newTable', ['tableId' => $table->id, 'platzPage' => $platzPage + 1]);
    } else {
        $nextUrl = $redirectUrl;
    }

    // Platzberechnung
    $platz = $start + 1;
    $platzCounter = $start + 1;
    $lastPunkte = null;
    $lastBuchholz = null;
@endphp

@section('head')
    @if($table)
        <meta http-equiv="refresh" content="10;url={{ $nextUrl }}">
    @endif
@endsection

@section('content')
    @if($table)
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <strong class="fs-2">Neue Tabelle: {{ $table->ueberschrift }}</strong>
            </div>
            <div class="card-body p-0">
                @if($table->beschreibung)
                    <div class="mb-2">{{ $table->beschreibung }}</div>
                @endif
                @if($platzierungenSeite->count())
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-success">
                                <tr>
                                    <th>Platz</th>
                                    <th>Team</th>
                                    <th>Punkte</th>
                                    @if($table->buchholzwertungaktiv ?? false)
                                        <th>Buchholzzahl <sup>*</sup></th>
                                    @endif
                                    <th>Absolvierte Rennen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($platzierungenSeite as $idx => $platzierung)
                                    @php
                                        $punkte = $platzierung->punkte;
                                        $buchholz = $platzierung->buchholzzahl ?? 0;
                                        if($idx === 0 && $platzPage === 1) {
                                            $platz = 1;
                                            $platzCounter = 1;
                                            $lastPunkte = null;
                                            $lastBuchholz = null;
                                        }
                                        if($idx === 0 && $platzPage > 1 && $start > 0) {
                                            // Hole die Werte des letzten Eintrags der vorherigen Seite
                                            $prev = $sorted[$start - 1];
                                            $lastPunkte = $prev->punkte;
                                            $lastBuchholz = $prev->buchholzzahl ?? 0;
                                            // Prüfe, ob Gleichstand mit letztem der vorherigen Seite
                                            if($punkte == $lastPunkte && $buchholz == $lastBuchholz) {
                                                // Platz bleibt gleich wie vorherige Seite
                                                $platz = $platzCounter = $start; // Platznummer wie letzter auf vorheriger Seite
                                            } else {
                                                $platz = $platzCounter = $start + 1;
                                            }
                                        }
                                        if($idx > 0) {
                                            if($punkte == $lastPunkte && $buchholz == $lastBuchholz) {
                                                // gleicher Platz
                                            } else {
                                                $platz = $platzCounter;
                                            }
                                        }
                                        $lastPunkte = $punkte;
                                        $lastBuchholz = $buchholz;
                                        $platzCounter++;
                                    @endphp
                                    <tr>
                                        <td>{{ $platz }}</td>
                                        <td>{{ $platzierung->getMannschaft->teamname ?? '' }}</td>
                                        <td>{{ $platzierung->punkte }}</td>
                                        @if($table->buchholzwertungaktiv ?? false)
                                            <td>{{ $platzierung->buchholzzahl }}</td>
                                        @endif
                                        <td>
                                            {{ $platzierung->rennanzahl }}
                                            @if($table->maxrennen)
                                                von {{ $table->maxrennen }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($table->buchholzwertungaktiv ?? false)
                        <div class="alert alert-info py-2 px-3 mb-3">
                            <small class="text-muted">
                                <sup>*</sup> Die Buchholzzahl ist eine Feinwertung, bei der die Punkte aller Gegner, gegen die ein Team gespielt hat, aufsummiert werden. Sie dient dazu, bei Punktgleichheit die Platzierung zu bestimmen.
                            </small>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        <div class="mt-3 w-100">
            <div class="text-center bg-success text-white rounded py-1 px-2 fw-semibold shadow-sm w-100">
                Tabelle {{ $tableIndex+1 }} von {{ $tableCount }}
                @if($gesamtSeiten > 1)
                    – Seite {{ $platzPage }} von {{ $gesamtSeiten }}
                @endif
            </div>
        </div>
    @else
        <div class="alert alert-warning">Keine Tabellen vorhanden.</div>
    @endif
@endsection
