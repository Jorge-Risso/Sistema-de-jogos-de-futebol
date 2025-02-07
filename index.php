<?php
require_once 'functions.php';

// Buscar as ligas antes de usar
$leagues = getLeagues();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Próximos Jogos de Futebol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="container mt-4">
    <h1 class="text-center mb-4">Próximos Jogos de Futebol</h1>

    <form action="index.php" method="GET" class="row g-3">
        <div class="col-md-6">
            <label for="league" class="form-label">Escolha a Liga:</label>
            <select name="league" id="league" class="form-select">
                <option value="" disabled selected>Selecione um Campeonato</option>
                <?php
                if (!empty($leagues['response']) && is_array($leagues['response'])) {
                    foreach ($leagues['response'] as $league) {
                        $leagueId = $league['league']['id'];
                        $leagueName = $league['league']['name'];
                        $selected = (isset($_GET['league']) && $_GET['league'] == $leagueId) ? 'selected' : '';
                        echo "<option value=\"$leagueId\" $selected>$leagueName</option>";
                    }
                } else {
                    echo "<option value='' disabled>Nenhuma liga encontrada</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-success w-100">Pesquisar</button>
        </div>
    </form>

    <a class="btn btn-warning mt-3" href="lastGames.php">Últimos Jogos</a>
    <a class="btn btn-primary mt-3" href="searchTeam.php">Pesquisar Time</a>

    <hr>

    <?php
    if (!isset($_GET['league'])) {
        echo "<div class='alert alert-secondary' role='alert'>Por favor, selecione uma liga para ver os próximos jogos.</div>";
    } elseif (empty($upcomingGames)) {
        echo "<div class='alert alert-warning' role='alert'>Nenhum jogo encontrado para a liga selecionada.</div>";
    }
    ?>

</body>
</html>
