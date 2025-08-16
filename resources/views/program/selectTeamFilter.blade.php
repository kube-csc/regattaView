@extends('layouts.frontend')

@section('title', 'Teamfilter wählen')

@section('content')
<main id="main">
    <section id="services" class="services">
        <div class="container">
            <div class="section-title" data-aos="fade-in" data-aos-delay="50">
                <h2>Teamfilter wählen</h2>
                <div class="row g-2 align-items-end justify-content-center">
                    <div class="col-12 col-sm-6 d-flex justify-content-center align-items-stretch">
                        <form method="POST" action="{{ route('program.setTeamFilter') }}" class="w-100 d-flex flex-column" style="max-width: 400px; min-height: 100%;">
                            @csrf
                            <div class="mb-3 flex-grow-1">
                                <label for="team_id" class="form-label">Team auswählen:</label>
                                <select name="team_id" id="team_id" class="form-select w-100" style="min-width: 0;">
                                    <option value="" @if(empty($currentFilter)) selected @endif>-- Kein Filter --</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" @if($currentFilter == $team->id) selected @endif>
                                            {{ $team->teamname }}
                                            @if($team->teamWertungsGruppe && $team->teamWertungsGruppe->typ)
                                                ({{ $team->teamWertungsGruppe->typ }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-auto">Filter setzen</button>
                        </form>
                    </div>
                    @if(!empty($currentFilter))
                    <div class="col-12 col-sm-6 d-flex justify-content-center align-items-stretch">
                        <form method="POST" action="{{ route('program.setTeamFilter') }}" class="w-100 d-flex flex-column" style="max-width: 400px; min-height: 100%;">
                            @csrf
                            <input type="hidden" name="team_id" value="">
                            <input type="hidden" name="clear" value="1">
                            <div class="flex-grow-1"></div>
                            <button type="submit" class="btn btn-danger w-100 mt-auto">Filter löschen</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
