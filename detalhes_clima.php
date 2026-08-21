<?php
// =========================================
// ARQUIVO: detalhes_clima.php
// Mostra as informações climáticas de uma coordenada (lat, lon)
// obtendo os dados de outro arquivo: api_clima.php
// =========================================

// Verifica se os parâmetros "lat" e "lon" foram passados na URL
// Exemplo de uso: detalhes_clima.php?lat=-22.9&lon=-43.2
if (!isset($_GET['lat']) || !isset($_GET['lon'])) {
    echo 'Parâmetros ausentes.';
    exit; // Encerra o script se não houver dados
}

// Captura latitude e longitude e faz encode para evitar erros de URL
$lat = urlencode($_GET['lat']);
$lon = urlencode($_GET['lon']);

// Faz a requisição local para api_clima.php passando lat/lon
$data = @file_get_contents("http://localhost/cumulus/api_clima.php?lat={$lat}&lon={$lon}");

// Se a requisição falhar, mostra mensagem de erro
if ($data === false) {
    echo "Erro ao obter dados climáticos.";
    exit;
}

// Converte o JSON retornado pela API em array associativo
$json = json_decode($data, true);
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Detalhes do Clima</title>

    <!-- Importa o Bootstrap para layout e estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <!-- Verifica se o JSON retornou informações de "weather" -->
        <?php if (isset($json['weather'])): ?>

            <!-- Mostra descrição do clima -->
            <p>
                <strong>Condição:</strong>
                <?php echo htmlspecialchars($json['weather'][0]['description']); ?>
            </p>

            <!-- Mostra temperatura -->
            <p>
                <strong>Temperatura:</strong>
                <?php echo htmlspecialchars($json['main']['temp']); ?> °C
            </p>

            <!-- Mostra umidade -->
            <p>
                <strong>Umidade:</strong>
                <?php echo htmlspecialchars($json['main']['humidity']); ?>%
            </p>

        <?php else: ?>
            <!-- Caso o JSON não tenha os dados esperados -->
            <div class="alert alert-warning">
                Não foi possível obter dados do clima.
            </div>
        <?php endif; ?>

        <!-- Botão para voltar à página principal -->
        <a href="home.php" class="btn btn-secondary">Voltar</a>
    </div>
</body>
</html>
