@extends('layouts.frontend')

@section('title', 'Teamfilter wählen')

@section('content')
<main id="main">
    <section id="services" class="services">
        <div class="container">
            <div class="section-title" data-aos="fade-in" data-aos-delay="50">
                <h2>Teamfilter wählen</h2>
                <div class="d-flex flex-row align-items-end">
                    <form method="POST" action="{{ route('program.setTeamFilter') }}" class="me-2">
                        @csrf
                        <div class="mb-3">
                            <label for="team_id" class="form-label">Team auswählen:</label>
                            <select name="team_id" id="team_id" class="form-select">
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
                        <button type="submit" class="btn btn-primary">Filter setzen</button>
                    </form>
                    <form method="POST" action="{{ route('program.setTeamFilter') }}">
                        @csrf
                        <input type="hidden" name="team_id" value="">
                        <input type="hidden" name="clear" value="1">
                        <button type="submit" class="btn btn-danger ms-2">Filter löschen</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
