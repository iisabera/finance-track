<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações - FinanceTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <!-- Topo (Logo) -->
    <header class="top-header">
        <div class="container">
            <span class="fw-bold fs-4"><i class="fas fa-wallet me-2 text-primary"></i>FinanceTrack</span>
        </div>
    </header>

    <div class="container">
        <h4 class="mb-4">Configurações</h4>

        <!-- Modo Escuro -->
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 texts-mono"><i class="fas fa-moon me-2"></i>Modo Escuro</h6>
                    <small class="texts-mono">Alternar entre tema claro e escuro</small>
                </div>
                <button id="btn-toggle-dark" class="btn btn-outline-secondary rounded-pill">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
        </div>

        <!-- Resetar App -->
    <div class="card">
        <div class="card-body text-center">
            <form method="POST" onsubmit="return confirm('Esta ação irá apagar TODOS os dados salvos. Tem certeza?');">
                <button type="submit" name="limpar_dados" class="btn btn-danger btn-lg w-100 mb-2 d-flex align-items-center justify-content-center gap-2" style="font-size:1.1rem;">
                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                    Resetar Aplicativo
                </button>
            </form>
            <div class="small texts-mono mt-2">
                <i class="fas fa-info-circle"></i>
                Esta ação apaga <b>todos os seus lançamentos e metas</b>!
            </div>
        </div>
    </div>
        
        <div class="text-center mt-4 texts-mono small">
            Versão 1.0.0 (PoC)
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
        <a href="index.php?page=historico" class="nav-item-link">
            <i class="fas fa-history"></i> Extrato
        </a>
        <a href="index.php?page=config" class="nav-item-link active">
            <i class="fas fa-cog"></i> Config
        </a>
    </nav>

    <script src="assets/darkmode.js"></script>
</body>
</html>
