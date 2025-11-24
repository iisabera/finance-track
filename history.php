<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico - FinanceTrack</title>
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

<div class="container">
    <?php if ($mensagem): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= $mensagem ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header texts-mono">
            <i class="fas fa-filter me-2"></i>Filtrar Lançamentos
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <input type="hidden" name="page" value="historico">
                
                <div class="col-md-3">
                    <label class="form-label small texts-mono">Categoria</label>
                    <select name="filtro_cat" class="form-select">
                        <option value="">Todas</option>
                        <option value="Alimentacao" <?= (isset($_GET['filtro_cat']) && $_GET['filtro_cat']=='Alimentacao')?'selected':'' ?>>Alimentação</option>
                        <option value="Transporte" <?= (isset($_GET['filtro_cat']) && $_GET['filtro_cat']=='Transporte')?'selected':'' ?>>Transporte</option>
                        <option value="Moradia" <?= (isset($_GET['filtro_cat']) && $_GET['filtro_cat']=='Moradia')?'selected':'' ?>>Moradia</option>
                        <option value="Lazer" <?= (isset($_GET['filtro_cat']) && $_GET['filtro_cat']=='Lazer')?'selected':'' ?>>Lazer</option>
                        <option value="Saude" <?= (isset($_GET['filtro_cat']) && $_GET['filtro_cat']=='Saude')?'selected':'' ?>>Saúde</option>
                        <option value="Salario" <?= (isset($_GET['filtro_cat']) && $_GET['filtro_cat']=='Salario')?'selected':'' ?>>Salário</option>
                        <option value="Outros" <?= (isset($_GET['filtro_cat']) && $_GET['filtro_cat']=='Outros')?'selected':'' ?>>Outros</option>
                    </select>
                </div>

                <div class="col-md-3 texts-mono">
                    <label class="form-label small text-muted-theme">Data Início</label>
                    <input type="date" name="data_ini" class="form-control" value="<?= $_GET['data_ini'] ?? '' ?>">
                </div>

                <div class="col-md-3 texts-mono">
                    <label class="form-label small text-muted-theme">Data Fim</label>
                    <input type="date" name="data_fim" class="form-control" value="<?= $_GET['data_fim'] ?? '' ?>">
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1"><i class="fas fa-search"></i></button>
                    <a href="index.php?page=historico" class="btn btn-outline-secondary" title="Limpar Filtros"><i class="fas fa-undo"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center texts-mono">
            <span>Resultados Encontrados</span>
            <span class="badge bg-primary rounded-pill"><?= count($historico_filtrado) ?></span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Data</th>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th class="text-end">Valor</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($historico_filtrado) > 0): ?>
                        <?php foreach ($historico_filtrado as $t): 
                             $cor = $t['valor'] >= 0 ? 'text-receita' : 'text-despesa';
                             $icone = $t['valor'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                        ?>
                        <tr>
                            <td class="ps-4"><?= date('d/m/Y', strtotime($t['data'])) ?></td>
                            <td class="fw-500"><?= $t['descricao'] ?></td>
                            <td><span class="badge bg-light text-dark border"><?= $t['categoria'] ?></span></td>
                            <td><i class="fas <?= $icone ?> <?= $cor ?> me-1 small"></i> <?= ucfirst($t['tipo']) ?></td>
                            <td class="text-end fw-bold <?= $cor ?>"><?= formatarMoeda($t['valor']) ?></td>
                            <td class="text-end pe-4">
                                <form method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este item?');">
                                    <input type="hidden" name="excluir_transacao" value="1">
                                    <input type="hidden" name="id_transacao" value="<?= $t['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                Nenhuma transação encontrada com esses filtros.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bottom Navigation -->
<nav class="bottom-nav">
    <a href="index.php" class="nav-item-link">
        <i class="fas fa-home"></i> Início
    </a>
    <a href="index.php?page=metas" class="nav-item-link">
        <i class="fas fa-bullseye"></i> Metas
    </a>
    <a href="index.php?page=historico" class="nav-item-link active">
        <i class="fas fa-history"></i> Extrato
    </a>
    <a href="index.php?page=config" class="nav-item-link">
        <i class="fas fa-cog"></i> Config
    </a>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/darkmode.js"></script>
</body>
</html>
