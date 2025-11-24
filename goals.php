<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metas - FinanceTrack</title>
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

    <div class="row">
        <!-- Lista de Metas -->
        <div class="col-md-8">
            <h4 class="mb-3 text-muted-theme">Metas Ativas</h4>
            
            <?php if(empty($metas_processadas)): ?>
                <div class="card-body alert border text-center py-5">
                    <i class="fas fa-bullseye fa-3x text-muted-theme mb-3"></i>
                    <p>Nenhuma meta definida. Crie sua primeira meta ao lado!</p>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach($metas_processadas as $m): 
                        // Lógica visual da barra
                        $cor_barra = 'bg-primary';
                        $texto_status = 'do alvo';
                        
                        if ($m['categoria'] !== 'Geral') {
                            // Para despesas, se passar de 100% é ruim (Vermelho)
                            if ($m['porcentagem'] >= 100) $cor_barra = 'bg-danger';
                            else if ($m['porcentagem'] >= 80) $cor_barra = 'bg-warning';
                            else $cor_barra = 'bg-success';
                            $texto_status = 'do limite';
                        } else {
                            // Para poupança, 100% é sucesso (Verde)
                            if ($m['porcentagem'] >= 100) $cor_barra = 'bg-success';
                        }
                    ?>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <h5 class="card-title mb-0"><?= $m['nome'] ?></h5>
                                        <span class="badge bg-light text-dark border mt-1"><?= $m['categoria'] === 'Geral' ? 'Poupança' : 'Categoria: '.$m['categoria'] ?></span>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted-theme d-block">Progresso</small>
                                        <span class="fw-bold"><?= formatarMoeda($m['progresso']) ?></span> 
                                        <span class="text-muted-theme small">/ <?= formatarMoeda($m['valor_alvo']) ?></span>
                                    </div>
                                </div>
                                
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar <?= $cor_barra ?>" role="progressbar" style="width: <?= $m['porcentagem'] ?>%"></div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-2 align-items-center">
                                    <small class="text-muted-theme"><?= number_format($m['porcentagem'], 0) ?>% <?= $texto_status ?></small>
                                    <form method="POST" class="m-0">
                                        <input type="hidden" name="excluir_meta" value="1">
                                        <input type="hidden" name="id_meta" value="<?= $m['id'] ?>">
                                        <button type="submit" class="btn btn-sm text-danger p-0 border-0"><i class="fas fa-trash"></i> Remover</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<button class="btn btn-primary btn-fab" data-bs-toggle="modal" data-bs-target="#modalMetas" style="bottom: 90px;">
    <i class="fas fa-plus"></i>
</button>

<!-- Bottom Navigation -->
<nav class="bottom-nav">
    <a href="index.php" class="nav-item-link">
        <i class="fas fa-home"></i> Início
    </a>
    <a href="index.php?page=metas" class="nav-item-link active">
        <i class="fas fa-bullseye"></i> Metas
    </a>
    <a href="index.php?page=historico" class="nav-item-link">
        <i class="fas fa-history"></i> Extrato
    </a>
    <a href="index.php?page=config" class="nav-item-link">
        <i class="fas fa-cog"></i> Config
    </a>
</nav>

<!-- MODAL DE NOVA Meta -->
<div class="modal fade" id="modalMetas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-coins me-2"></i>Nova Meta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="nova_meta" value="1">
                        
                        <div class="mb-3">
                            <label class="form-label">Nome da Meta</label>
                            <input type="text" name="nome_meta" class="form-control" placeholder="Ex: Limite Mercado" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Categoria Monitorada</label>
                            <select name="categoria_meta" class="form-select">
                                <option value="Geral">Saldo Geral (Poupança)</option>
                                <option disabled>--- Orçamento por Categoria ---</option>
                                <option value="Alimentacao">Alimentação</option>
                                <option value="Transporte">Transporte</option>
                                <option value="Moradia">Moradia</option>
                                <option value="Lazer">Lazer</option>
                                <option value="Saude">Saúde</option>
                                <option value="Outros">Outros</option>
                            </select>
                            <div class="form-text small">Escolha "Geral" para metas de saldo ou uma categoria para limitar gastos.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Valor Alvo (R$)</label>
                            <input type="number" step="0.01" name="valor_meta" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Criar Meta</button>
                    </form>
                </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/darkmode.js"></script>
</body>
</html>