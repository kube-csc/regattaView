<?php

namespace App\Http\Controllers;

use App\Models\RegattaTeam;
use App\Services\RegattaTeamHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RaceTeamController extends Controller
{
    public function __construct(private RegattaTeamHistoryService $historyService)
    {
    }

    public function steckbrief(Request $request, ?int $teamId = null)
    {
        $team = $this->resolveInitialTeam($request, $teamId);

        if (!$team) {
            abort(404, 'Team nicht gefunden.');
        }

        $eventId = (int) $team->regatta_id;
        $teams = RegattaTeam::with('teamWertungsGruppe.template')
            ->where('regatta_id', $eventId)
            ->where('status', '!=', 'Gelöscht')
            ->orderBy('teamname')
            ->get();

        if ($teams->isEmpty()) {
            abort(404, 'Keine Teams fuer diese Veranstaltung gefunden.');
        }

        $team = $this->resolveTeamFromIndexOrId($request, $teams, $team);

        $teamIndex = (int) $teams->search(fn (RegattaTeam $entry) => (int) $entry->id === (int) $team->id);
        $teamCount = $teams->count();

        $finaleOnly = (string) $request->query('finale', '1') !== '0';

        $prevIndex = ($teamIndex - 1 + $teamCount) % $teamCount;
        $nextIndex = ($teamIndex + 1) % $teamCount;

        $prevTeamUrl = $this->buildTeamRoute((int) $teams[$prevIndex]->id, $finaleOnly);
        $nextTeamUrl = $this->buildTeamRoute((int) $teams[$nextIndex]->id, $finaleOnly);

        $participationCount = 0;
        $lastResults = collect();
        $fallbackYear = null;

        if ((int) $team->teamlink > 0) {
            $teamlinks = collect([(int) $team->teamlink]);
            $teamIdToTeamlink = $this->historyService->getPastTeamIdToTeamlink($teamlinks, $eventId);

            $participationCount = (int) $this->historyService
                ->getParticipationCountByTeamlink($teamlinks, $eventId)
                ->get((int) $team->teamlink, 0);

            $pastTeamIds = $teamIdToTeamlink
                ->filter(fn (int $teamlink) => $teamlink === (int) $team->teamlink)
                ->keys()
                ->values();

            $lastResults = $this->historyService->getLastResultsByTeamIdsGroupedByEvent(
                $pastTeamIds,
                $eventId,
                $finaleOnly
            );

            if (empty($team->bild)) {
                $fallbackImage = RegattaTeam::query()
                    ->join('events', 'regatta_teams.regatta_id', '=', 'events.id')
                    ->where('regatta_teams.teamlink', (int) $team->teamlink)
                    ->where('regatta_teams.status', 'Neuanmeldung')
                    ->whereNotNull('regatta_teams.bild')
                    ->where('regatta_teams.bild', '!=', '')
                    ->where('events.datumbis', '<', now()->format('Y-m-d'))
                    ->where('events.id', '!=', $eventId)
                    ->orderByDesc('events.datumbis')
                    ->select('regatta_teams.bild', 'events.datumbis')
                    ->first();

                if ($fallbackImage) {
                    $team->bild = $fallbackImage->bild;
                    $fallbackYear = substr((string) $fallbackImage->datumbis, 0, 4);
                }
            }
        }

        return view('regattateam.regatta-team-steckbrief', [
            'team' => $team,
            'teamIndex' => $teamIndex,
            'teamCount' => $teamCount,
            'participationCount' => $participationCount,
            'lastResults' => $lastResults,
            'fallbackYear' => $fallbackYear,
            'prevTeamUrl' => $prevTeamUrl,
            'nextTeamUrl' => $nextTeamUrl,
            'finaleOnly' => $finaleOnly,
        ]);
    }

    private function resolveInitialTeam(Request $request, ?int $teamId): ?RegattaTeam
    {
        $resolvedTeamId = $teamId ?: (int) $request->query('id', 0);

        if ($resolvedTeamId <= 0) {
            return null;
        }

        return RegattaTeam::with('teamWertungsGruppe.template')->find($resolvedTeamId);
    }

    private function resolveTeamFromIndexOrId(Request $request, Collection $teams, RegattaTeam $fallbackTeam): RegattaTeam
    {
        $teamCount = $teams->count();

        if ($request->has('team')) {
            $teamIndex = (int) $request->query('team', 0);
            $teamIndex = max(0, min($teamIndex, $teamCount - 1));

            return $teams[$teamIndex];
        }

        return $teams->firstWhere('id', $fallbackTeam->id) ?? $teams->first();
    }

    private function buildTeamRoute(int $teamId, bool $finaleOnly): string
    {
        return route('RegattaTeam.steckbrief', [
            'teamId' => $teamId,
            'finale' => $finaleOnly ? 1 : 0,
        ]);
    }
}

