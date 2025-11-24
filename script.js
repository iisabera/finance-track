let chartInstanceDespesas = null;
let chartInstanceReceitas = null;
let chartInstanceComparativo = null;

function initDashboardCharts(
    despLabels, despData, 
    recLabels, recData, 
    compLabels, compData
) {
    // 1. Gráfico de Despesas (Donut)
    const ctxDesp = document.getElementById('chartDespesas');
    if (ctxDesp && despLabels.length > 0) {
        chartInstanceDespesas = new Chart(ctxDesp, {
            type: 'doughnut',
            data: {
                labels: despLabels,
                datasets: [{
                    data: despData,
                    backgroundColor: ['#ef4444', '#f87171', '#b91c1c', '#fee2e2', '#991b1b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right' } }
            }
        });
    }

    // 2. Gráfico de Receitas (Donut)
    const ctxRec = document.getElementById('chartReceitas');
    if (ctxRec && recLabels.length > 0) {
        chartInstanceReceitas = new Chart(ctxRec, {
            type: 'doughnut',
            data: {
                labels: recLabels,
                datasets: [{
                    data: recData,
                    backgroundColor: ['#10b981', '#34d399', '#059669', '#d1fae5', '#064e3b'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right' } }
            }
        });
    }

    // 3. Gráfico Comparativo (Barra Vertical)
    const ctxComp = document.getElementById('chartComparativo');
    if (ctxComp && recLabels.length > 0 || despLabels.length > 0) {
        chartInstanceComparativo = new Chart(ctxComp, {
            type: 'bar',
            data: {
                labels: compLabels,
                datasets: [{
                    label: 'Total (R$)',
                    data: compData,
                    backgroundColor: ['#10b981', '#ef4444'], // Verde para Receita, Vermelho para Despesa
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
}