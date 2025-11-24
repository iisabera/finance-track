document.addEventListener('DOMContentLoaded', function() {
    const body = document.body;
    const btn = document.getElementById('btn-toggle-dark');

    // Checa se já existe preferência salva
    if(localStorage.getItem('dark-mode') === 'true') {
        body.classList.add('dark-mode');
        if(btn) btn.innerHTML = '<i class="fas fa-sun"></i>';
    }

    if(btn) {
        btn.addEventListener('click', function() {
            body.classList.toggle('dark-mode');
            const isDark = body.classList.contains('dark-mode');
            localStorage.setItem('dark-mode', isDark);
            btn.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
        });
    }
});
