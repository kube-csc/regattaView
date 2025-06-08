@if($beschreibung != null)
<a href="/Sprecher/Mannschaft/{{ $id }}/{{ $raceId }}" class="me-2">
    <button type="button" class="btn btn-secondary ml-2">{{ $teamname }}</button>
</a>
@else
{{ $teamname }}
@endif
