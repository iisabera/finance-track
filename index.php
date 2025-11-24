<?php
// 1. Carrega a lógica
require_once 'backend.php';

// 2. Roteamento
$pagina = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($pagina) {
    case 'config':
        require_once 'settings.php';
        break;
    case 'metas':
        require_once 'goals.php';
        break;
    case 'historico':
        require_once 'history.php';
        break;
    case 'home':
    default:
        require_once 'home.php';
        break;
}
?>