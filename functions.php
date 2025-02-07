<?php
require_once 'api.php';

function getLeagues() {
    return GetDataApi('leagues');
}

function getGamesByLeagueAndYear($leagueId, $season) {
    $endpoint = 'fixtures';
    $params = [
        'league' => $leagueId,
        'season' => $season
    ];

    return GetDataApi($endpoint, $params);
}

function UpcomingMatches($games) {
    $currentDate = date('Y-m-d H:i:s');  
    $upcomingGames = [];

    foreach ($games['response'] as $game) {
        if ($game['fixture']['date'] >= $currentDate) {
            $upcomingGames[] = $game; 
        }
    }

    return $upcomingGames;
}

function getTeamsByLeague($leagueId) {
    return GetDataApi('teams', ['league' => $leagueId]);
}


function getMatchesBySeason($leagueId, $season) {
    return getGamesByLeagueAndYear($leagueId, $season);
}




function getLastMatches($teamId) {
    return GetDataApi('fixtures', [
        'team' => $teamId,
        'last' => 5 
    ]);
}

?>
