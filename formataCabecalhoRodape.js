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

const transicaoLuzQueAmpara = document.createElement('style');
transicaoLuzQueAmpara.textContent = `
    @media (prefers-reduced-motion: no-preference) {
        body { transition: opacity 360ms cubic-bezier(.4, 0, 1, 1); }
        html.page-leaving body { opacity: 0; }
    }
`;
document.head.appendChild(transicaoLuzQueAmpara);
window.addEventListener('pageshow', () => {
    document.documentElement.classList.remove('page-leaving');
});

document.addEventListener('click', event => {
    if (!(event.target instanceof Element)
        || event.defaultPrevented
        || event.button !== 0
        || event.metaKey
        || event.ctrlKey
        || event.shiftKey
        || event.altKey
        || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const link = event.target.closest('a[href="./luz-que-ampara.html"]');
    if (!link || link.target && link.target !== '_self' || link.hasAttribute('download')) {
        return;
    }
    event.preventDefault();

    if (document.documentElement.classList.contains('page-leaving')) {
        return;
    }
    document.documentElement.classList.add('page-leaving');
    window.setTimeout(() => window.location.assign(link.href), 380);
});

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