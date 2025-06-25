<b>Platz:
    {{ $tabeledata->platz }}</b> {{ $tabeledata->getMannschaft->teamname }}
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
{{ $tabeledata->punkte }} Punkt(e) / {{ $tabeledata->rennanzahl }} von {{ $raceResoult->raceTabele->maxrennen }} Rennen
@if($raceResoult->raceTabele->buchholzwertungaktiv)
    / {{ $tabeledata->buchholzzahl }} Buchholzzahl
@endif
<br>
