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
    <div class="card mb-4">
        <div class="card-header bg-primary text-white text-center">
            <strong class="fs-2">Teams der Wertung {{ $wertung }}</strong>
        </div>
        <div class="card-body bg-light">
            <div class="container-fluid px-0">
                <div class="row justify-content-center align-items-stretch">
                    @for($i = 0; $i < $filled; $i++)
                        @if($i % $columns === 0 && $i > 0)
                            </div><div class="row justify-content-center align-items-stretch">
                        @endif
                        <div class="col-md-{{ 12 / $columns }} d-flex align-items-stretch">
                            @if(isset($teamsArr[$i]))
                                <div class="card bg-white border-primary mb-4 w-100 d-flex flex-column justify-content-center align-items-center shadow-sm">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <span class="card-title team-title text-primary fw-bold">{{ $teamsArr[$i]->teamname }}</span>
                                        @if($teamsArr[$i]->ort)
                                            <div class="card-text team-ort text-secondary">{{ $teamsArr[$i]->ort }}</div>
                                        @endif
                                        @if(isset($teamsArr[$i]->status) && $teamsArr[$i]->status !== 'Neuanmeldung')
                                            <div class="card-text" style="font-size:0.95em;">
                                                Status: <span class="text-dark">{{ $teamsArr[$i]->status }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="card bg-white border-0 mb-4 w-100 d-flex flex-column justify-content-center align-items-center" style="visibility:hidden;">
                                    <div class="card-body"></div>
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3 w-100">
        <div class="text-center bg-primary text-white rounded py-1 px-2 fw-semibold shadow-sm w-100">
            Gruppe {{ $currentGroupNumber }} von {{ $totalGroups }}
            - Seite {{ $page }} von {{ $totalPages }}
            – {{ $count }} Team{{ $count == 1 ? '' : 's' }} in dieser Gruppe
        </div>
    </div>
@endsection
