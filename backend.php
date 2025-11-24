<?php
session_start();

// --- INICIALIZAÇÃO ---
if (!isset($_SESSION['transacoes'])) $_SESSION['transacoes'] = [];
if (!isset($_SESSION['metas'])) $_SESSION['metas'] = [];

// --- HELPER FUNCTIONS ---
function formatarMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

// --- PROCESSAMENTO POST (Escrita) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Nova Transação
    if (isset($_POST['nova_transacao'])) {
        $valor = floatval($_POST['valor']);
        if ($_POST['tipo'] === 'despesa') $valor *= -1;
        
        $_SESSION['transacoes'][] = [
            'id' => uniqid(),
            'data' => $_POST['data'],
            'descricao' => htmlspecialchars(trim($_POST['descricao'])),
            'categoria' => $_POST['categoria'],
            'valor' => $valor,
            'tipo' => $_POST['tipo']
        ];
        $_SESSION['flash_msg'] = "Transação adicionada!";
    }

    // 2. Excluir Transação (NOVO)
    if (isset($_POST['excluir_transacao'])) {
        $id_del = $_POST['id_transacao'];
        $_SESSION['transacoes'] = array_filter($_SESSION['transacoes'], function($t) use ($id_del) {
            return $t['id'] !== $id_del;
        });
        $_SESSION['flash_msg'] = "Transação removida.";
    }

    // 3. Nova Meta
    if (isset($_POST['nova_meta'])) {
        $_SESSION['metas'][] = [
            'id' => uniqid(),
            'nome' => htmlspecialchars(trim($_POST['nome_meta'])),
            'valor_alvo' => floatval($_POST['valor_meta']),
            'categoria' => $_POST['categoria_meta']
        ];
        $_SESSION['flash_msg'] = "Meta definida!";
    }

    // 4. Excluir Meta
    if (isset($_POST['excluir_meta'])) {
        $id_excluir = $_POST['id_meta'];
        $_SESSION['metas'] = array_filter($_SESSION['metas'], function($m) use ($id_excluir) {
            return $m['id'] !== $id_excluir;
        });
        $_SESSION['flash_msg'] = "Meta removida.";
    }
    
    // 5. Reset
    if (isset($_POST['limpar_dados'])) {
        session_destroy();
        session_start();
        $_SESSION['flash_msg'] = "App resetado!";
    }

    // Manter os parâmetros da URL (para não perder o filtro ao excluir algo)
    $query_string = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
    header("Location: index.php" . $query_string);
    exit;
}

// --- MENSAGENS ---
$mensagem = '';
if (isset($_SESSION['flash_msg'])) {
    $mensagem = $_SESSION['flash_msg'];
    unset($_SESSION['flash_msg']);
}

// --- CÁLCULOS DASHBOARD ---
$total_receitas = 0;
$total_despesas = 0;
$saldo_atual = 0;
$despesas_por_cat = [];
$receitas_por_cat = [];

// Loop principal de cálculos
foreach ($_SESSION['transacoes'] as $t) {
    $saldo_atual += $t['valor'];
    if ($t['valor'] > 0) {
        $total_receitas += $t['valor'];
        $cat = $t['categoria'];
        if (!isset($receitas_por_cat[$cat])) $receitas_por_cat[$cat] = 0;
        $receitas_por_cat[$cat] += $t['valor'];
    } else {
        $abs_val = abs($t['valor']);
        $total_despesas += $abs_val;
        $cat = $t['categoria'];
        if (!isset($despesas_por_cat[$cat])) $despesas_por_cat[$cat] = 0;
        $despesas_por_cat[$cat] += $abs_val;
    }
}

// --- LÓGICA DE FILTRAGEM (Para a página Histórico) ---
// Por padrão mostra tudo
$historico_filtrado = $_SESSION['transacoes'];

// Se tiver filtros via GET
if (isset($_GET['page']) && $_GET['page'] === 'historico') {
    
    // Filtro Categoria
    if (isset($_GET['filtro_cat']) && $_GET['filtro_cat'] !== '') {
        $cat_filtro = $_GET['filtro_cat'];
        $historico_filtrado = array_filter($historico_filtrado, function($t) use ($cat_filtro) {
            return $t['categoria'] === $cat_filtro;
        });
    }

    // Filtro Data Inicio
    if (isset($_GET['data_ini']) && $_GET['data_ini'] !== '') {
        $historico_filtrado = array_filter($historico_filtrado, function($t) {
            return $t['data'] >= $_GET['data_ini'];
        });
    }

    // Filtro Data Fim
    if (isset($_GET['data_fim']) && $_GET['data_fim'] !== '') {
        $historico_filtrado = array_filter($historico_filtrado, function($t) {
            return $t['data'] <= $_GET['data_fim'];
        });
    }
}

// Ordenar por data (mais recente primeiro)
usort($historico_filtrado, function($a, $b) {
    return strtotime($b['data']) - strtotime($a['data']);
});


// --- CÁLCULOS DE METAS ---
$metas_processadas = [];
foreach ($_SESSION['metas'] as $meta) {
    $progresso = 0;
    if ($meta['categoria'] === 'Geral') {
        $progresso = $saldo_atual;
    } else {
        $cat = $meta['categoria'];
        $progresso = isset($despesas_por_cat[$cat]) ? $despesas_por_cat[$cat] : 0;
    }
    $meta['progresso'] = $progresso;
    $meta['porcentagem'] = ($meta['valor_alvo'] > 0) ? min(100, ($progresso / $meta['valor_alvo']) * 100) : 0;
    $metas_processadas[] = $meta;
}

// JSONs (sem alteração)
$json_despesas_labels = json_encode(array_keys($despesas_por_cat));
$json_despesas_data = json_encode(array_values($despesas_por_cat));
$json_receitas_labels = json_encode(array_keys($receitas_por_cat));
$json_receitas_data = json_encode(array_values($receitas_por_cat));
$json_comparativo_labels = json_encode(['Receitas', 'Despesas']);
$json_comparativo_data = json_encode([$total_receitas, $total_despesas]);
?>
