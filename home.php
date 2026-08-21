<?php
// ==========================================
// home.php — Página principal do sistema Cumulus
// ==========================================
session_start();
require 'config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'] ?? 'Usuário';

// Busca os locais favoritos do usuário logado
$stmt = $mysqli->prepare("SELECT * FROM favoritos WHERE usuario_id = ? ORDER BY criado_em DESC");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$favoritos = $result->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cumulus - Home</title>

    <!-- Bootstrap + CSS próprio -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Home.css">
    <style>#map { height: 60vh; }</style>
</head>
<body>

<!-- =======================================
     NAVBAR SUPERIOR
======================================== -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <div>
            <a class="logo" href="home.php">
                <img src="https://i.imgur.com/i8SjH5P_d.webp?maxwidth=760&fidelity=grand" width="120px">
            </a>
        </div>
        <div class="d-flex">
            <span class="me-3">Olá, <?php echo htmlspecialchars($usuario_nome); ?></span>
            <a class="btn btn-outline-secondary" href="logout.php">Sair</a>
        </div>
    </div>
</nav>

<!-- =======================================
     CONTEÚDO PRINCIPAL
======================================== -->
<div class="container my-3">
    <div class="row">
        <!-- MAPA -->
        <div class="col-md-8">
            <div id="map"></div>

            <!-- Campo de busca de cidade -->
            <div class="input-group mt-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Digite o nome da cidade ou local...">
                <button class="btn btn-success" id="localizar" type="button" onclick="searchLocation()">
                    <i class="fas fa-search"></i> Buscar no Mapa
                </button>
            </div>
        </div>

        <!-- LATERAL DIREITA (favoritos e clima) -->
        <div class="col-md-4">
            <h6>Adicionar favorito (clique no mapa)</h6>
            <p class="small text-muted">Clique no mapa para adicionar um local aos seus favoritos.</p>

            <h5>Favoritos</h5>
            <ul class="list-group mb-3" id="favList">
                <?php foreach ($favoritos as $f): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><?php echo htmlspecialchars($f['nome_local']); ?></span>
                        <div>
                            <button class="btn btn-sm btn-primary"
                                    onclick="showWeather(<?php echo $f['latitude']; ?>, <?php echo $f['longitude']; ?>)">
                                Ver
                            </button>
                            <a class="btn btn-sm btn-danger" href="remover_favorito.php?id=<?php echo $f['id']; ?>">Remover</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Área para mostrar o clima -->
            <h5>Detalhes do clima</h5>
            <div id="weatherDetails" class="p-3 border rounded" style="min-height:150px;">
                <p class="text-muted">Clique em "Ver" para mostrar o clima aqui.</p>
            </div>
        </div>
    </div>
</div>

<!-- =======================================
     SCRIPTS JAVASCRIPT
======================================== -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
// Variável global para marcador temporário (ping no mapa)
let currentMarker = null;

// Ícone personalizado dos marcadores
const customIcon = L.icon({
    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34]
});

// Inicializa o mapa centralizado em São Paulo
const map = L.map('map').setView([-23.55, -46.63], 10);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 18}).addTo(map);

// =======================================
// Função: Exibir clima na lateral
// =======================================
function showWeather(lat, lon) {
    const detailsDiv = document.getElementById('weatherDetails');
    detailsDiv.innerHTML = '<p class="text-muted">Carregando...</p>';

    fetch(`detalhes_clima.php?lat=${lat}&lon=${lon}`)
        .then(response => response.text())
        .then(html => { detailsDiv.innerHTML = html; })
        .catch(err => {
            detailsDiv.innerHTML = '<p class="text-danger">Erro ao carregar o clima.</p>';
            console.error(err);
        });
}

// =======================================
// Função: Salvar local como favorito
// =======================================
function saveFavorite(lat, lon) {
    const nome = prompt('Nome do local para salvar como favorito:');
    if (!nome) return;

    fetch('adicionar_favorito.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({nome_local: nome, latitude: lat, longitude: lon})
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('Local adicionado aos favoritos!');
            location.reload();
        } else {
            alert('Erro: ' + (data.error || 'Não foi possível salvar.'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('Erro ao conectar com o servidor.');
    });
}

// =======================================
// Função: Centraliza e marca localização
// =======================================
function handleLocationSelection(lat, lon) {
    if (currentMarker) map.removeLayer(currentMarker);

    const latlng = L.latLng(lat, lon);
    currentMarker = L.marker(latlng, {icon: customIcon}).addTo(map);

    currentMarker.bindPopup(`
        <b>Local Selecionado:</b><br>
        Coordenadas: ${lat.toFixed(6)}, ${lon.toFixed(6)}<br>
        <div class="text-center mt-2">
            <button class="btn btn-sm btn-primary" onclick="saveFavorite('${lat}', '${lon}')">
                Salvar Localização
            </button>
        </div>
    `).openPopup();

    showWeather(lat, lon);
}

// =======================================
// Função: Buscar local pelo nome (via PHP proxy)
// =======================================
function searchLocation() {
    const query = document.getElementById('searchInput').value.trim();
    if (!query) {
        alert('Por favor, digite o nome do local para buscar.');
        return;
    }

    const url = `search_proxy.php?query=${encodeURIComponent(query)}`;
    const searchButton = document.getElementById('localizar');

    searchButton.disabled = true;
    searchButton.innerHTML = 'Buscando...';

    fetch(url)
        .then(response => {
            if (!response.ok) throw new Error(`Erro HTTP: ${response.status}`);
            return response.json();
        })
        .then(data => {
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);
                map.setView([lat, lon], 14);
                handleLocationSelection(lat, lon);
            } else {
                alert('Local não encontrado. Tente novamente.');
            }
        })
        .catch(error => {
            console.error('Erro na busca:', error);
            alert('Erro ao buscar o local. Veja o console (F12).');
        })
        .finally(() => {
            searchButton.disabled = false;
            searchButton.innerHTML = '<i class="fas fa-search"></i> Buscar no Mapa';
        });
}

// Evento de clique no mapa para adicionar marcador
map.on('click', function(e) {
    const { lat, lng } = e.latlng;
    handleLocationSelection(lat, lng);
});
</script>
</body>
</html>
