<label for="name">Bahn:</label>
{{ $lane->bahn }}
@if($lane->mannschaft_id != Null)
    @if($lane->regattaTeam->beschreibung != Null)
        <a href="/Sprecher/Mannschaft/{{ $lane->mannschaft_id }}/{{ $raceNext->id }}" class="me-2">
            <button type="button" class="btn btn-secondary ml-2">{{ $lane->regattaTeam->teamname }}</button>
        </a>
    @else
        {{ $lane->regattaTeam->teamname }}
    @endif
    @if($raceNext->mix == 1 && $lane->tabele_id <> $raceNext->tabele_id)
        @if($raceNext->stautus == 4)
        <a href="/Sprecher/Tabelle/{{ $lane->tabele_id }}/{{ $raceNext->id }}" class="me-2">
            <button type="button" class="btn btn-primary ml-2">{{ $lane->getTableLane->ueberschrift }}</button>
        </a>
        @else
            <span class="text-primary">{{ $lane->getTableLane->ueberschrift }}</span>
        @endif
    @endif
@else
    frei
@endif
<br>
