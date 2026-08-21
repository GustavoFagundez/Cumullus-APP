<?php
// =========================================
// ARQUIVO: favoritos.php
// Mostra todos os locais salvos como favoritos pelo usuário logado
// =========================================

session_start();
require 'config.php';

// Verifica se o usuário está logado; caso contrário, redireciona
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

// Busca todos os favoritos do usuário logado no banco de dados
$stmt = $mysqli->prepare("SELECT * FROM favoritos WHERE usuario_id = ?");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$result = $stmt->get_result();

// Transforma os resultados em um array associativo
$fav = $result->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Meus Favoritos - Cumulus</title>

    <!-- Importa o Bootstrap para layout e componentes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">

    <div class="container">
        <h3 class="mb-3">🌤️ Meus Favoritos</h3>

        <!-- Lista de locais favoritos -->
        <ul class="list-group">
            <?php foreach ($fav as $f): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <!-- Nome do local -->
                    <?php echo htmlspecialchars($f['nome_local']); ?>

                    <!-- Botões de ação -->
                    <div>
                        <!-- Abre detalhes do clima do local -->
                        <a class="btn btn-sm btn-primary" 
                           href="detalhes_clima.php?lat=<?php echo $f['latitude']; ?>&lon=<?php echo $f['longitude']; ?>">
                           Ver
                        </a>

                        <!-- Remove local dos favoritos -->
                        <a class="btn btn-sm btn-danger" 
                           href="remover_favorito.php?id=<?php echo $f['id']; ?>">
                           Remover
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Botão de retorno à página principal -->
        <a class="btn btn-secondary mt-3" href="home.php">Voltar</a>
    </div>

</body>
</html>
