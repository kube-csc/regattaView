@extends('layouts.presentation')

@section('title', 'Information')

@section('head')
    @if($info)
        <meta http-equiv="refresh" content="10;url={{ $nextUrl }}">
    @endif
@endsection

@section('content')
    @if($info)
        <div class="card mx-auto mb-4" style="max-width: 700px;">
            <div class="card-header bg-info text-white text-center">
                <h2 class="mb-0">{{ $info->informationTittel }}</h2>
            </div>
            <div class="card-body">
                {!! $info->informationBeschreibung !!}
            </div>
        </div>
        <div class="text-center mb-2 bg-dark text-white rounded py-1 px-2">
            <small>
                Information {{ $infoIndex+1 }} von {{ $infoCount }}
            </small>
        </div>
    @else
        <div class="alert alert-warning text-center">Keine aktuellen Informationen vorhanden.</div>
    @endif
@endsection

