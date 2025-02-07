<?php
require_once 'config.php';

// Função para fazer requisições à API
function GetDataApi($endpoint, $params = []) {
    $url = API_URL . $endpoint . '?' . http_build_query($params);

    $headers = [
        "X-RapidAPI-Key: " . API_KEY,
        "X-RapidAPI-Host: v3.football.api-sports.io"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
?>

