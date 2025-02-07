<?php
require_once 'functions.php';

// Chama a função para obter as ligas
$leagues = getLeagues();

// Verifica se houve erro na resposta da API
if (isset($leagues['errors']) && !empty($leagues['errors'])) {
    $errorMessages = implode(' ', $leagues['errors']);
} else {
    $errorMessages = null;
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futebol ao Vivo</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="container mt-4">

    <h1 class="text-center mb-4">Pesquisar Últimos Jogos</h1>

    <?php if ($errorMessages): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($errorMessages, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <form action="lastGames.php" method="GET" class="row g-3">
        <div class="col-md-6">
            <label for="league" class="form-label">Escolha a Liga:</label>
            <select name="league" id="league" class="form-select">
                <option value="" disabled <?php echo !isset($_GET['league']) ? 'selected' : ''; ?>>Selecione um Campeonato</option>
                <?php
                if (isset($leagues['response']) && !empty($leagues['response'])):
                    foreach ($leagues['response'] as $league):
                        $selected = isset($_GET['league']) && $_GET['league'] == $league['league']['id'] ? 'selected' : '';
                        echo "<option value=\"{$league['league']['id']}\" $selected>{$league['league']['name']}</option>";
                    endforeach;
                else:
                    echo "<option value='' disabled>Não foi possível carregar as ligas</option>";
                endif;
                ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="year" class="form-label">Selecione o ano:</label>
            <input type="number" id="year" name="year" min="1900" max="2024" step="1" value="<?= isset($_GET['year']) ? htmlspecialchars($_GET['year'], ENT_QUOTES, 'UTF-8') : date('Y'); ?>" class="form-control">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Pesquisar</button>
        </div>
    </form>

    <a class="btn btn-info mt-3" href="searchTeam.php">Próximos Jogos</a>
    <a class="btn btn-primary mt-3" href="searchTeam.php">Pesquisar Time</a>

    <hr>

    <?php
    if (isset($_GET['league']) && isset($_GET['year'])) {
        $leagueId = htmlspecialchars($_GET['league'], ENT_QUOTES, 'UTF-8');
        $season = htmlspecialchars($_GET['year'], ENT_QUOTES, 'UTF-8');

        // Obtém os jogos com base na liga e no ano
        $games = getGamesByLeagueAndYear($leagueId, $season);

        if (isset($games['errors']) && !empty($games['errors'])) {
            echo "<div class='alert alert-danger' role='alert'>Erro ao obter os jogos: " . htmlspecialchars(implode(' ', $games['errors']), ENT_QUOTES, 'UTF-8') . "</div>";
        } elseif (!empty($games['response'])) {
            echo "<h2 class='mt-4'>Jogos Anteriores:</h2>";
            echo "<div class='table-responsive'>
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
                        <tbody>";

            foreach ($games['response'] as $game) {
                // Obtém o estádio, caso exista
                $stadium = isset($game['fixture']['venue']['name']) ? htmlspecialchars($game['fixture']['venue']['name'], ENT_QUOTES, 'UTF-8') : 'Informação não disponível';
                
                // Verifica e define o placar
                $homeScore = isset($game['score']['fulltime']['home']) ? htmlspecialchars($game['score']['fulltime']['home'], ENT_QUOTES, 'UTF-8') : 'N/D';
                $awayScore = isset($game['score']['fulltime']['away']) ? htmlspecialchars($game['score']['fulltime']['away'], ENT_QUOTES, 'UTF-8') : 'N/D';

                // Exibe os dados na tabela
                echo "<tr>
                        <td>" . htmlspecialchars($game['teams']['home']['name'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>{$homeScore} X {$awayScore}</td>
                        <td>" . htmlspecialchars($game['teams']['away']['name'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . date('d/m/Y H:i', strtotime($game['fixture']['date'])) . "</td>
                        <td>{$stadium}</td>
                      </tr>";
            }

            echo "</tbody></table></div>";
        } else {
            echo "<div class='alert alert-warning' role='alert'>Nenhum jogo encontrado para a liga e ano selecionados.</div>";
        }
    }
    ?>

    <h3 class="mt-4">Últimos Resultados</h3>

</body>
</html>
