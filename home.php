<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinanceTrack - PoC Modular</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="top-header">
    <div class="container">
        <span class="fw-bold fs-4"><i class="fas fa-wallet me-2 text-primary"></i>FinanceTrack</span>
    </div>
</header>

<div class="container pb-5">
    
    <?php if ($mensagem): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $mensagem ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <?php
        $meta_poupanca = null;
        foreach ($_SESSION['metas'] as $meta) {
            if ($meta['categoria'] === 'Geral') {
                $meta_poupanca = $meta['valor_alvo'];
                break;
            }
        }
    ?>

    <!-- Resumo (KPIs) -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="card h-100 bg-gradient-primary text-white">
                <div class="card-body">
                    <h6 class="texts-mono text-uppercase small">Saldo Atual</h6>
                    <h3 class="fw-bold"><?= formatarMoeda($saldo_atual) ?></h3>
                    <small>
                        Meta de Poupança: 
                        <?= $meta_poupanca !== null ? formatarMoeda($meta_poupanca) : 'Não Definida' ?>
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-start border-4 border-success">
                <div class="card-body">
                    <h6 class="texts-mono text-uppercase small">Receitas</h6>
                    <h3 class="text-receita fw-bold"><?= formatarMoeda($total_receitas) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-start border-4 border-danger">
                <div class="card-body">
                    <h6 class="texts-mono text-uppercase small">Despesas</h6>
                    <h3 class="text-despesa fw-bold"><?= formatarMoeda($total_despesas) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Seção de Dicas -->
    <?php 
        // Lógica simples de dica baseada no saldo
        $dica_titulo = "Dica do dia";
        $dica_texto = "Mantenha seus gastos anotados diariamente para não perder o controle.";
        $dica_icone = "fa-lightbulb";
        $dica_cor = "alert-info";

        if ($saldo_atual < 0) {
            $dica_titulo = "Atenção ao Saldo";
            $dica_texto = "Você está no negativo. Revise suas categorias de maior gasto na aba 'Metas'.";
            $dica_icone = "fa-exclamation-circle";
            $dica_cor = "alert-danger";
        } elseif ($saldo_atual > 0 && $saldo_atual > ($total_receitas * 0.2)) {
            $dica_titulo = "Ótimo Trabalho!";
            $dica_texto = "Você guardou mais de 20% da renda. Que tal criar uma meta de investimento?";
            $dica_icone = "fa-star";
            $dica_cor = "alert-success";
        }
    ?>
    <div class="alert <?= $dica_cor ?> d-flex align-items-center mb-4 shadow-sm" role="alert">
        <i class="fas <?= $dica_icone ?> fa-2x me-3"></i>
        <div>
            <div class="fw-bold"><?= $dica_titulo ?></div>
            <small><?= $dica_texto ?></small>
        </div>
    </div>

    <!-- Card de Gráficos com Abas -->
    <div class="card mb-4">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs card-header-tabs m-0" id="graficosTab" role="tablist">
                <!-- Aba 1: Comparativo -->
                <li class="nav-item">
                    <button class="texts-mono nav-link border-top-0 border-end-0" id="comparativo-tab" data-bs-toggle="tab" data-bs-target="#tab-comparativo" type="button">
                        <i class="fas fa-balance-scale text-primary me-2"></i>Balanço
                    </button>
                </li>
                <!-- Aba 2: Despesas -->
                <li class="nav-item">
                    <button class="texts-mono nav-link active border-top-0 border-start-0" id="despesas-tab" data-bs-toggle="tab" data-bs-target="#tab-despesas" type="button">
                        <i class="fas fa-chart-pie text-danger me-2"></i>Despesas
                    </button>
                </li>
                <!-- Aba 3: Receitas -->
                <li class="nav-item">
                    <button class="texts-mono nav-link border-top-0" id="receitas-tab" data-bs-toggle="tab" data-bs-target="#tab-receitas" type="button">
                        <i class="fas fa-chart-pie text-success me-2"></i>Receitas
                    </button>
                </li>
            </ul>
        </div>
        
        <div class="card-body" style="height: 320px;">
            <div class="tab-content h-100">
                <!-- Conteúdo Aba 1 -->
                <div class="tab-pane fade h-100" id="tab-comparativo">
                    <?php if(empty($despesas_por_cat) && empty($receitas_por_cat)): ?>
                        <div class="texts-mono d-flex align-items-center justify-content-center h-100 text-muted-theme">Sem registros</div>
                    <?php endif; ?>
                    <canvas id="chartComparativo"></canvas>
                </div>
                <!-- Conteúdo Aba 2 -->
                <div class="tab-pane fade show active h-100" id="tab-despesas">
                    <?php if(empty($despesas_por_cat)): ?>
                        <div class="texts-mono d-flex align-items-center justify-content-center h-100 text-muted-theme">Sem despesas registradas</div>
                    <?php endif; ?>
                    <canvas id="chartDespesas"></canvas>
                </div>
                <!-- Conteúdo Aba 3 -->
                <div class="tab-pane fade h-100" id="tab-receitas">
                    <?php if(empty($receitas_por_cat)): ?>
                        <div class="texts-mono d-flex align-items-center justify-content-center h-100 text-muted-theme">Sem receitas registradas</div>
                    <?php endif; ?>
                    <canvas id="chartReceitas"></canvas>
                </div>
            </div>
        </div>
    </div>

    </div>
</div>

<button class="btn btn-primary btn-fab" data-bs-toggle="modal" data-bs-target="#modalTransacao" style="bottom: 90px;">
    <i class="fas fa-plus"></i>
</button>

<nav class="bottom-nav">
    <a href="index.php" class="nav-item-link active">
        <i class="fas fa-home"></i> Início
    </a>
    <a href="index.php?page=metas" class="nav-item-link">
        <i class="fas fa-bullseye"></i> Metas
    </a>
    <a href="index.php?page=historico" class="nav-item-link">
        <i class="fas fa-history"></i> Extrato
    </a>
    <a href="index.php?page=config" class="nav-item-link">
        <i class="fas fa-cog"></i> Config
    </a>
</nav>

<!-- MODAL DE NOVA TRANSAÇÃO -->
<div class="modal fade" id="modalTransacao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-coins me-2"></i>Nova Transação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="nova_transacao" value="1">
                    
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <input type="text" name="descricao" class="form-control" placeholder="Ex: Padaria" required>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Valor (R$)</label>
                            <input type="number" step="0.01" name="valor" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Data</label>
                            <input type="date" name="data" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Categoria</label>
                        <select name="categoria" class="form-select">
                            <option value="Alimentacao">Alimentação</option>
                            <option value="Transporte">Transporte</option>
                            <option value="Moradia">Moradia</option>
                            <option value="Lazer">Lazer</option>
                            <option value="Saude">Saúde</option>
                            <option value="Salario">Salário / Renda</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block">Tipo de Lançamento</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="tipo" id="modalDesp" value="despesa" checked>
                            <label class="btn btn-outline-danger" for="modalDesp">Despesa</label>

                            <input type="radio" class="btn-check" name="tipo" id="modalRec" value="receita">
                            <label class="btn btn-outline-success" for="modalRec">Receita</label>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Adicionar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="assets/script.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initDashboardCharts(
            // Despesas
            <?= $json_despesas_labels ?>, <?= $json_despesas_data ?>,
            // Receitas
            <?= $json_receitas_labels ?>, <?= $json_receitas_data ?>,
            // Comparativo
            <?= $json_comparativo_labels ?>, <?= $json_comparativo_data ?>
        );
    });
</script>
<script src="assets/darkmode.js"></script>


</body>
</html>