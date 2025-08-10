@extends('layouts.presentation')

@section('title', 'Information')

@section('head')
    @if($info)
        <meta http-equiv="refresh" content="10;url={{ $nextUrl }}">
    @endif
@endsection

@section('content')
    @if($info)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white text-center">
                <strong class="fs-2">{{ $info->informationTittel }}</strong>
            </div>
            <div class="card-body bg-light">
                {!! $info->informationBeschreibung !!}
            </div>
        </div>
        <div class="mt-3 w-100">
            <div class="text-center bg-primary text-white rounded py-1 px-2 fw-semibold shadow-sm w-100">
                Information {{ $infoIndex+1 }} von {{ $infoCount }}
            </div>
        </div>
    @else
        <div class="alert alert-warning text-center">Keine aktuellen Informationen vorhanden.</div>
    @endif
@endsection
