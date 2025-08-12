@extends('layouts.presentation')

@section('title', 'Neue Tabelle')

@php
    $tableIndex = request()->query('table', 0);
    $tableCount = count($tables);
    $table = $tables[$tableIndex] ?? null;

    // Pagination-Logik: 12 Einträge pro Seite
    $page = (int) request()->query('page', 1);
    $entriesPerPage = 12;
    $totalEntries = $table->tabeledataShows->count();
    $totalPages = max(1, ceil($totalEntries / $entriesPerPage));
    $page = max(1, min($page, $totalPages));
    $start = ($page - 1) * $entriesPerPage;
    $pagedRows = $table->tabeledataShows->sortBy('platz')->slice($start, $entriesPerPage);

    // Ziel-URL für die nächste Seite oder die normale Tabellenanzeige
    $nextPage = $page + 1;
    $hasNextPage = $nextPage <= $totalPages;
    $nextUrl = $hasNextPage
        ? route('presentation.newTable', ['tableId' => $table->id, 'page' => $nextPage])
        : $redirectUrl;

    // Prüfe, ob Buchholzzahl vorhanden ist
    $hasBuchholzzahl = $table->tabeledataShows->first(function($row) {
        return isset($row->buchholzzahl);
    }) !== null;
@endphp

@section('head')
    <meta http-equiv="refresh" content="10;url={{ $nextUrl }}">
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
                <table class="table table-striped mb-0">
                    <thead class="table-success">
                        <tr>
                            <th class="text-end" style="width:8%;">Platz</th>
                            <th class="text-start" style="width:40%;">Team</th>
                            <th class="text-end" style="width:12%;">Punkte</th>
                            @if($hasBuchholzzahl)
                                <th class="text-end" style="width:20%;">Buchholzzahl <sup>*</sup></th>
                            @endif
                            <th class="text-end" style="width:12%;">Absolvierte Rennen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pagedRows as $row)
                            <tr>
                                <td class="text-end">{{ $row->platz }}</td>
                                <td class="text-start">
                                    {{ $row->getMannschaft ? $row->getMannschaft->teamname : 'Unbekannt' }}
                                </td>
                                <td class="text-end">{{ $row->punkte }}</td>
                                @if($hasBuchholzzahl)
                                    <td class="text-end">
                                        {{ isset($row->buchholzzahl) ? $row->buchholzzahl : '-' }}
                                    </td>
                                @endif
                                <td class="text-end">
                                    {{ $row->rennanzahl }}
                                    @if($table->maxrennen)
                                        von {{ $table->maxrennen }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3 w-100">
            <div class="text-center bg-success text-white rounded py-1 px-2 fw-semibold shadow-sm w-100">
                Tabelle {{ $page }} von {{ $totalPages }}
            </div>
        </div>
        @if($table->buchholzwertungaktiv ?? false)
            <div class="alert alert-info py-2 px-3 mb-3">
                <small class="text-muted">
                    <sup>*</sup> Die Buchholzzahl ist eine Feinwertung, bei der die Punkte aller Gegner, gegen die ein Team gespielt hat, aufsummiert werden. Sie dient dazu, bei Punktgleichheit die Platzierung zu bestimmen.
                </small>
            </div>
        @endif
    @endif
@endsection
