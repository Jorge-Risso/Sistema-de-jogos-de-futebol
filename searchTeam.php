<?php
require_once 'functions.php';

$leagueId = isset($_GET['league']) ? $_GET['league'] : null;
$teamName = isset($_GET['team']) ? trim($_GET['team']) : null;
$season = isset($_GET['season']) ? $_GET['season'] : null;

$matches = [];
$errorMessage = '';

if ($leagueId && $teamName && $season) {
    $games = getGamesByLeagueAndYear($leagueId, $season);
    if (empty($games['response'])) {
        $errorMessage = "Nenhum jogo encontrado para a liga selecionada na temporada.";
    } else {
        $teamMatches = [];
        foreach ($games['response'] as $game) {
            if (strcasecmp($game['teams']['home']['name'], $teamName) === 0 || strcasecmp($game['teams']['away']['name'], $teamName) === 0) {
                $teamMatches[] = $game;  
            }
        }

        if (!empty($teamMatches)) {
            $matches = $teamMatches;  
        } else {
            $errorMessage = "Desculpe, mas nenhum jogo encontrado para o time '{$teamName}' na temporada {$season}.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futebol ao Vivo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="container mt-4">
    <h1 class="text-center mb-4">Pesquisar Time</h1>

    <form action="searchTeam.php" method="GET" class="row g-3">
        <div class="col-md-4">
            <label for="league" class="form-label">Escolha a Liga:</label>
            <select name="league" id="league" class="form-select" required>
                <option value="" disabled selected>Selecione um Campeonato</option>
                <?php
                $leagues = getLeagues();
                foreach ($leagues['response'] as $league) {
                    $selected = (isset($_GET['league']) && $_GET['league'] == $league['league']['id']) ? 'selected' : '';
                    echo "<option value=\"{$league['league']['id']}\" $selected>{$league['league']['name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="season" class="form-label">Escolha a Temporada:</label>
            <input type="text" id="season" name="season" class="form-control" required>
        </div>

        <div class="col-md-4">
            <label for="team" class="form-label">Nome do Time:</label>
            <input type="text" id="team" name="team" class="form-control" placeholder="Digite o nome do time" required>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Pesquisar</button>
        </div>
    </form>

    <a class="btn btn-info mt-3" href="index.php">Próximos Jogos</a>
    <a class="btn btn-warning mt-3" href="lastGames.php">Últimos Jogos</a>

    <hr>

    <?php if ($errorMessage): ?>
        <div class="alert alert-warning" role="alert"><?php echo $errorMessage; ?></div>
    <?php elseif (!empty($matches)): ?>
        <h2 class='mt-4'>Jogos do <?php echo htmlspecialchars($teamName); ?> na temporada <?php echo htmlspecialchars($season); ?></h2>
        <div class='table-responsive'>
            <table class='table table-striped table-hover'>
                <thead class='table-dark'>
                    <tr>
                        <th>Casa</th>
                        <th>Placar</th>
                        <th>Visitante</th>
                        <th>Data/Hora</th>
                        <th>Estádio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matches as $game): ?>
                        <tr>
                            <td><?php echo $game['teams']['home']['name']; ?></td>
                            <td><?php echo "{$game['score']['fulltime']['home']} X {$game['score']['fulltime']['away']}"; ?></td>
                            <td><?php echo $game['teams']['away']['name']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($game['fixture']['date'])); ?></td>
                            <td><?php echo $game['fixture']['venue']['name'] ?? 'Não informado'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-secondary" role="alert">Nenhum jogo encontrado para o time informado na temporada selecionada.</div>
    <?php endif; ?>

</body>
</html>
