@extends('layouts.frontend')

@section('title', 'Mannschaftsfilter wählen')

@section('content')
<main id="main">
    <section id="services" class="services">
        <div class="container">
            <div class="section-title" data-aos="fade-in" data-aos-delay="50">
                <h2>Mannschaftsfilter wählen</h2>
                <form method="POST" action="{{ route('program.setTeamFilter') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="team_id" class="form-label">Mannschaft auswählen:</label>
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
            </div>
        </div>
    </section>
</main>
@endsection
