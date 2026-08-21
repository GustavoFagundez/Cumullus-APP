<?php
// Inicia ou retoma a sessão (para acessar o login do usuário)
session_start();
// Conecta ao banco de dados usando o arquivo de configuração
require 'config.php';

// Recebe os dados enviados via fetch (POST JSON) do JavaScript
$data = json_decode(file_get_contents('php://input'), true);
// Tenta obter o ID do usuário logado na sessão
$usuario_id = $_SESSION['usuario_id'] ?? null;

// ======================================
// 1. VALIDAÇÃO BÁSICA
// ======================================
if (!$usuario_id || !$data['nome_local'] || !isset($data['latitude']) || !isset($data['longitude'])) {
    // Se o usuário não estiver logado ou faltarem dados, retorna um erro
    echo json_encode(['success' => false, 'error' => 'Dados inválidos.']);
    exit;
}

// Armazena os dados recebidos em variáveis para uso
$nome = $data['nome_local'];
$lat = $data['latitude'];
$lon = $data['longitude'];

// ======================================
// 2. VERIFICA SE JÁ EXISTE
// ======================================
// Prepara a consulta para verificar se o local (com a mesma latitude/longitude)
// já foi salvo por este usuário.
$stmt = $mysqli->prepare("SELECT id FROM favoritos WHERE usuario_id = ? AND latitude = ? AND longitude = ?");
$stmt->bind_param("idd", $usuario_id, $lat, $lon);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Se a contagem de linhas for maior que zero, o local já existe
    echo json_encode(['success' => false, 'error' => 'Local já está nos favoritos.']);
    exit;
}

// ======================================
// 3. INSERÇÃO NO BANCO
// ======================================
// Prepara a inserção do novo favorito (ID do usuário, nome, latitude e longitude)
$stmt = $mysqli->prepare("INSERT INTO favoritos (usuario_id, nome_local, latitude, longitude, criado_em) VALUES (?, ?, ?, ?, NOW())");
// Liga as variáveis à consulta (i=integer, s=string, d=double)
$stmt->bind_param("isdd", $usuario_id, $nome, $lat, $lon);

if ($stmt->execute()) {
    // Se a execução foi um sucesso, retorna a confirmação
    echo json_encode(['success' => true]);
} else {
    // Se houve erro na execução (banco de dados), retorna o erro
    echo json_encode(['success' => false, 'error' => 'Erro ao salvar.']);
}
?>