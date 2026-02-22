// Função para carregar HTML externo
async function carregarComponente(id, arquivo) {
    try {
        const response = await fetch(arquivo);
        const html = await response.text();
        document.getElementById(id).innerHTML = html;
        
        // IMPORTANTE: Recriar os ícones Lucide pois o HTML acabou de chegar
        lucide.createIcons();

        // Se carregou o cabeçalho, ativar a funcionalidade do menu mobile
        if(id === 'header-placeholder') {
            iniciarMenuMobile();
        }
    } catch (error) {
        console.error('Erro ao carregar ' + arquivo, error);
    }
}

// Função para ativar o botão mobile do cabeçalho
function iniciarMenuMobile() {
    const btn = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    if(btn && menu) {
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            const icon = btn.querySelector('svg');
            if (menu.classList.contains('hidden')) {
                icon.setAttribute('data-lucide', 'menu');
            } else {
                icon.setAttribute('data-lucide', 'x');
            }
            lucide.createIcons();
        });
    }
}

// Chama a função para puxar os arquivos
document.addEventListener('DOMContentLoaded', () => {
    carregarComponente('header-placeholder', 'cabecalho.html');
    carregarComponente('footer-placeholder', 'rodape.html');
});