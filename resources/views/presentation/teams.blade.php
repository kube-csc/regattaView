@extends('layouts.presentation')

@section('title', 'Alle Teams')

@section('head')
    @php
        $teamsArr = $teams->values()->all();
        $count = count($teamsArr);
        $columns = min(3, max(1, $count));
        $rows = ceil($count / $columns);
        $filled = $rows * $columns;
        $page = request()->query('page', 1);
        $teamsPerPage = 15;
        $totalPages = max(1, ceil($count / $teamsPerPage));
        $page = max(1, min($page, $totalPages));
        $nextPage = $page < $totalPages ? $page + 1 : 1;
    @endphp
    <meta http-equiv="refresh" content="10;url={{ $nextUrl }}">
@endsection

@section('content')
    <h1 class="mb-2" style="margin-top:0;">Teams der Wertung {{ $wertung }}</h1>
    <h3 class="mt-2 mb-3 wertung-title"></h3>
    <div class="container-fluid px-0">
        <div class="row justify-content-center align-items-stretch">
            @for($i = 0; $i < $filled; $i++)
                @if($i % $columns === 0 && $i > 0)
                    </div><div class="row justify-content-center align-items-stretch">
                @endif
                <div class="col-md-{{ 12 / $columns }} d-flex align-items-stretch">
                    @if(isset($teamsArr[$i]))
                        <div class="card team-blue mb-4 w-100 d-flex flex-column justify-content-center align-items-center">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <span class="card-title team-title">{{ $teamsArr[$i]->teamname }}</span>
                                @if($teamsArr[$i]->ort)
                                    <div class="card-text team-ort">{{ $teamsArr[$i]->ort }}</div>
                                @endif
                                @if(isset($teamsArr[$i]->status) && $teamsArr[$i]->status !== 'Neuanmeldung')
                                    <div class="card-text" style="font-size:0.95em;">
                                        Status: {{ $teamsArr[$i]->status }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="card team-blue mb-4 w-100 d-flex flex-column justify-content-center align-items-center" style="visibility:hidden;">
                            <div class="card-body"></div>
                        </div>
                    @endif
                </div>
            @endfor
        </div>
        <div class="mt-3 text-center">
            <span class="seitenzahl bg-dark text-white rounded py-1 px-2">
                Gruppe {{ $currentGroupNumber }} von {{ $totalGroups }}
                - Seite {{ $page }} von {{ $totalPages }}
                – {{ $count }} Team{{ $count == 1 ? '' : 's' }} in dieser Gruppe
            </span>
        </div>
    </div>
@endsection
