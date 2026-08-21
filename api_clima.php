<?php
// Define a chave de acesso (API Key) para o serviço OpenWeatherMap
// IMPORTANTE: Esta chave deve ser mantida em segredo em um ambiente de produção
$apiKey = '02c7bfa7c79b3cafca53710053ea52a2';

// ======================================
// 1. VALIDAÇÃO DE ENTRADA
// ======================================

// Verifica se os parâmetros 'lat' (latitude) ou 'lon' (longitude) foram omitidos na URL
if (!isset($_GET['lat']) || !isset($_GET['lon'])) {
    // Se faltarem, envia um código de erro HTTP 400 (Bad Request)
    http_response_code(400);
    // Retorna uma mensagem de erro em formato JSON
    echo json_encode(['error' => 'lat e lon são obrigatórios']);
    exit; // Interrompe a execução
}

// =اتاخد dados de latitude e longitude da URL (GET) e os codifica (embora não seja estritamente necessário para números, é boa prática)
$lat = urlencode($_GET['lat']);
$lon = urlencode($_GET['lon']);
// Define que a temperatura deve ser retornada em unidades métricas (Celsius)
$units = 'metric';
// Define que a descrição do clima deve ser retornada em português (pt_br)
$lang = 'pt_br';

// ======================================
// 2. CONSTRUÇÃO E REQUISIÇÃO DA URL
// ======================================

// Monta a URL completa para a API OpenWeatherMap, incluindo as variáveis
$url = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&units={$units}&lang={$lang}&appid={$apiKey}";
// Faz a requisição à URL externa (o '@' suprime avisos caso a conexão falhe)
$resp = @file_get_contents($url);

// ======================================
// 3. TRATAMENTO DE ERRO DE CONEXÃO EXTERNA
// ======================================

// Verifica se a requisição à API externa falhou
if ($resp === false) {
    // Se falhou, envia um código de erro HTTP 500 (Erro Interno do Servidor)
    http_response_code(500);
    // Retorna uma mensagem de erro
    echo json_encode(['error' => 'falha ao consultar API externa']);
    exit;
}

// ======================================
// 4. RESPOSTA FINAL
// ======================================

// Define o cabeçalho HTTP para indicar que a resposta será em formato JSON
header('Content-Type: application/json; charset=utf-8');
// Envia a resposta bruta (JSON) que veio da API OpenWeatherMap de volta ao cliente (JavaScript)
echo $resp;
?>