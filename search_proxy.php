<?php
// search_proxy.php: Faz a requisição ao Nominatim do servidor -- Nominatim é um mecanismo de busca 
// geográfica gratuito e de código aberto, muito utilizado como alternativa ao serviço de geocodificação do Google Maps.

Sua principal função é atuar como um tradutor entre nomes e coordenadas, baseado nos dados do projeto OpenStreetMap (OSM), que é um mapa colaborativo global.
header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['query']) || empty($_GET['query'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Parâmetro de busca ausente.']);
    exit;
}

$query = urlencode($_GET['query']);
$url = "https://nominatim.openstreetmap.org/search?q={$query}&format=json&limit=1";

// Configuração obrigatória para o Nominatim: User-Agent
$context = stream_context_create([
    'http' => [
        'header' => "User-Agent: CumulusWeatherApp/1.0 (seu.email@exemplo.com)\r\n"
    ]
]);

// Faz a requisição de busca do servidor
$response = @file_get_contents($url, false, $context);

if ($response === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Falha ao conectar com o serviço de busca externo.']);
    exit;
}

// Retorna a resposta do Nominatim diretamente ao JavaScript
echo $response;
?>