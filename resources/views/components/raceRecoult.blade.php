<label for="name">Platz:</label>
{{ $lane->platz }}
<label for="name">Bahn:</label>
{{ $lane->bahn }}
@if($lane->mannschaft_id != Null)
    @if($lane->regattaTeam->beschreibung != Null)
        <a href="/Sprecher/Mannschaft/{{ $lane->mannschaft_id }}/{{ $raceResoult->id }}" class="me-2">
            <button type="button" class="btn btn-secondary ml-2">{{ $lane->regattaTeam->teamname }}</button>
        </a>
    @else
        {{ $lane->regattaTeam->teamname }}
    @endif
    @if($raceResoult->mix == 1 && $lane->tabele_id <> $raceResoult->tabele_id)
        <a href="/Sprecher/Tabelle/{{ $lane->tabele_id }}/{{ $raceResoult->id }}" class="me-2">
            <button type="button" class="btn btn-primary ml-2">{{ $lane->getTableLane->ueberschrift }}</button>
        </a>
    @endif
@endif
<br>

