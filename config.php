<?php
// ================================
// CONFIGURAÇÃO DE CONEXÃO COM O BANCO DE DADOS
// ================================

// Endereço do servidor MySQL (localhost)
$host = '127.0.0.1';

// Nome de usuário do MySQL
$user_name = 'root';

// Senha do MySQL (vazia por padrão no XAMPP)
$password = '';

// Nome do banco de dados
$db_name = 'cumulus';

// Porta de conexão do MySQL (3306 é padrão, mas aqui está em 3308)
$port = 3308;

// ================================
// CONECTA AO BANCO USANDO MYSQLI
// ================================

// Cria a conexão usando as variáveis acima
// OBS: não use aspas ao redor das variáveis, pois elas já contêm strings
$mysqli = mysqli_connect($host, $user_name, $password, $db_name, $port);

// ================================
// TESTA SE A CONEXÃO FOI BEM-SUCEDIDA
// ================================

// mysqli_connect_errno() retorna um código de erro se a conexão falhar
if (mysqli_connect_errno()) {
    // Exibe a mensagem de erro detalhada
    echo "Conexão não estabelecida com MySQL: " . mysqli_connect_error();

    // Define o código de resposta HTTP como 500 (erro interno)
    http_response_code(500);

    // Interrompe o script para evitar erros em outras partes
    exit();
}

// ================================
// DEFINE O PADRÃO DE CODIFICAÇÃO DE CARACTERES
// ================================

// Define o charset para UTF-8 (suporta acentuação e emojis)
$mysqli->set_charset('utf8mb4');

?>
