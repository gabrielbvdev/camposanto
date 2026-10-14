<?php
require_once __DIR__ . '/config.php';

// Função para gerar UUID v4
function gerarUUID() {
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // versão 4
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // variante RFC 4122
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$novo_uuid = null;
$erro = null;

// Processar formulário de nova homenagem
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome_usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $de_quem = trim($_POST['de_quem'] ?? '');
    $para_quem = trim($_POST['para_quem'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    if ($nome && $email && $telefone && $de_quem && $para_quem && $mensagem) {
        $uuid = gerarUUID();
        try {
            $stmt = $pdo->prepare("INSERT INTO homenagens_velario (uuid, nome_usuario, email, telefone, de_quem, para_quem, mensagem) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$uuid, $nome,$email, $telefone,$de_quem, $para_quem,$mensagem]);
            $novo_uuid =$uuid;
        } catch (PDOException $e) {$erro = "Ocorreu um erro ao acender sua vela. Tente novamente.";
        }
    } else {
        $erro = "Por favor, preencha todos os campos obrigatórios.";
    }
}

// Consultar todas as homenagens (mais recentes primeiro)
$stmt =$pdo->query("SELECT * FROM homenagens_velario ORDER BY data_criacao DESC");
$homenagens =$stmt->fetchAll();

// UUID passado via GET para destacar o registro
$uuid_destaque = isset($_GET['uuid']) ? trim($_GET['uuid']) : '';
?>
<!DOCTYPE html>
<html lang="pt-br" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Luz que ampara - Velário Virtual Campo Santo Familiar. Acenda uma vela e preste uma homenagem especial a quem partiu.">
    <title>Luz que Ampara | Velário Virtual - Campo Santo Familiar</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="./assets/img/favicon-verde.png" media="(prefers-color-scheme: light)">
    <link rel="icon" href="./assets/img/favicon-branco.png" media="(prefers-color-scheme: dark)">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Montserrat:wght@300;400;500;600;700&family=Sora:wght@100..800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Tailwind Config do Campo Santo -->
    <script src="./twconfig.js"></script>

    <style>
        /* Card em Destaque no meio do velário */
        .card-homenagem-destaque {
            border: 3px solid #2FB17C !important; /* Verde vibrante institucional */
            box-shadow: 0 0 35px rgba(47, 177, 124, 0.45), 0 20px 25px -5px rgba(0, 0, 0, 0.5) !important;
            transform: translateY(-4px);
        }

        /* Card padrão (branco) */
        .card-homenagem {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        /* Card associado ao link (tom verde institucional) */
        .card-homenagem-verde {
            background: linear-gradient(145deg, #F0F7F4, #E2EFE9) !important;
            border: 2px solid #2FB17C !important; /* brand-secondary */
            box-shadow: 0 10px 25px -5px rgba(47, 177, 124, 0.25), 0 8px 10px -6px rgba(47, 177, 124, 0.2) !important;
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="font-sans text-brand-neutral bg-[#FDFDFD] antialiased">

    <!-- HEADER / NAVBAR -->
    <div id="header-placeholder"></div>

    <!-- HERO DO VELÁRIO -->
    <section class="relative pt-36 pb-16 lg:pt-44 lg:pb-24 overflow-hidden bg-brand-primary/20">
        <div class="container mx-auto px-4 relative z-10 text-center max-w-4xl">
            <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-brand-secondary/15 text-brand-secondary text-xs font-bold mb-6 tracking-widest uppercase font-sans">
                <i data-lucide="flame" class="w-4 h-4 fill-current"></i> Velário Virtual
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-medium text-brand-tertiary leading-tight mb-6">
                Luz que ampara: <br>
                <span class="text-brand-secondary italic">um tributo de amor e memória.</span>
            </h1>
            <p class="text-base md:text-lg text-brand-neutral max-w-2xl mx-auto font-light leading-relaxed">
                Acenda uma vela virtual em homenagem à memória daqueles que continuam iluminando seus passos e mantenha viva a sua história.
            </p>
        </div>
    </section>

    <!-- SEÇÃO DO FORMULÁRIO -->
    <section class="py-12 bg-white relative border-b border-gray-100">
        <div class="container mx-auto px-4 max-w-3xl">
            <div class="bg-[#F8F9FA] rounded-[32px] p-8 md:p-12 border border-gray-200/80 shadow-sm">
                
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-brand-secondary/10 flex items-center justify-center text-brand-secondary">
                        <i data-lucide="flame" class="w-5 h-5 fill-current"></i>
                    </div>
                    <h2 class="text-2xl font-serif font-medium text-brand-tertiary">Acenda uma vela</h2>
                </div>

                <?php if ($erro): ?>
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
                        <span><?= htmlspecialchars($erro) ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="luz-que-ampara.php" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-tertiary mb-2">Seu Nome *</label>
                            <input type="text" name="nome_usuario" required placeholder="Ex: Maria Santos" class="w-full p-3.5 rounded-xl border border-gray-200 bg-white focus:border-brand-secondary focus:ring-2 focus:ring-brand-secondary/20 outline-none text-sm text-brand-tertiary">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-tertiary mb-2">Seu E-mail *</label>
                            <input type="email" name="email" required placeholder="maria@exemplo.com" class="w-full p-3.5 rounded-xl border border-gray-200 bg-white focus:border-brand-secondary focus:ring-2 focus:ring-brand-secondary/20 outline-none text-sm text-brand-tertiary">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-tertiary mb-2">Seu Telefone / WhatsApp *</label>
                            <input type="tel" name="telefone" required placeholder="(71) 99999-9999" class="w-full p-3.5 rounded-xl border border-gray-200 bg-white focus:border-brand-secondary focus:ring-2 focus:ring-brand-secondary/20 outline-none text-sm text-brand-tertiary">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2 border-t border-gray-200/60">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-tertiary mb-2">De quem? (Como aparecerá) *</label>
                            <input type="text" name="de_quem" required placeholder="Ex: David, Filhos e Netos..." class="w-full p-3.5 rounded-xl border border-gray-200 bg-white focus:border-brand-secondary focus:ring-2 focus:ring-brand-secondary/20 outline-none text-sm text-brand-tertiary">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-tertiary mb-2">Para quem? (Em memória de) *</label>
                            <input type="text" name="para_quem" required placeholder="Nome do ente querido" class="w-full p-3.5 rounded-xl border border-gray-200 bg-white focus:border-brand-secondary focus:ring-2 focus:ring-brand-secondary/20 outline-none text-sm text-brand-tertiary">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-brand-tertiary mb-2">Mensagem Especial *</label>
                        <textarea name="mensagem" rows="4" required placeholder="Escreva sua mensagem de carinho, saudade ou oração..." class="w-full p-4 rounded-xl border border-gray-200 bg-white focus:border-brand-secondary focus:ring-2 focus:ring-brand-secondary/20 outline-none text-sm text-brand-tertiary"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-brand-secondary hover:bg-brand-dark-green text-white font-bold py-4 rounded-2xl shadow-lg transition duration-300 flex items-center justify-center gap-2 text-base">
                        <i data-lucide="flame" class="w-5 h-5 fill-current"></i> Acender Vela e Registrar Homenagem
                    </button>
                </form>

            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- ÁREA DO VELÁRIO COM O FUNDO TODO EM VERDE -->
    <!-- ========================================== -->
    <section class="py-24 bg-brand-tertiary text-white relative overflow-hidden border-t-4 border-brand-secondary">
        <!-- Textura de fundo sutil em verde pontilhado -->
        <div class="absolute inset-0 opacity-[0.06] pointer-events-none" style="background-image: radial-gradient(#2FB17C 2px, transparent 2px); background-size: 32px 32px;"></div>

        <div class="container mx-auto px-4 max-w-7xl relative z-10">
            
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-brand-secondary font-bold uppercase tracking-widest text-xs mb-3 block font-sans">
                    Memorial de Oração e Saudade
                </span>
                <h2 class="text-3xl md:text-5xl font-serif font-medium text-white mb-4">
                    Velário Virtual
                </h2>
                <p class="text-brand-primary text-sm sm:text-base font-light opacity-80 max-w-xl mx-auto">
                    Cada chama representa o amor perpétuo, as lembranças queridas e a luz que nunca se apaga em nossos corações.
                </p>
            </div>

            <?php if (empty($homenagens)): ?>
                <div class="text-center py-16 text-brand-primary/80 bg-white/5 rounded-3xl border border-white/10 p-8 max-w-xl mx-auto backdrop-blur-sm">
                    <i data-lucide="flame" class="w-12 h-12 text-brand-secondary mx-auto mb-4 animate-pulse"></i>
                    <p class="text-base font-medium">Ainda não há velas acesas.</p>
                    <p class="text-xs opacity-75 mt-1">Seja o primeiro a acender uma vela no formulário acima.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($homenagens as$h): 
                        $is_destaque = ($uuid_destaque === $h['uuid']);$data_formatada = date('d/m/Y \à\s H\hi', strtotime($h['data_criacao']));
                    ?>
                        <!-- Card Padrão da Imagem de Referência (Branco sobre o fundo Verde do velário) -->
                        <div id="homenagem-<?= htmlspecialchars($h['uuid']) ?>" 
                             class="bg-white rounded-3xl p-6 sm:p-7 border border-gray-100 shadow-xl transition-all duration-300 flex flex-col justify-between relative text-left <?= $is_destaque ? 'card-homenagem-destaque' : 'hover:-translate-y-1' ?>">
                            
                            <?php if ($is_destaque): ?>
                                <!-- Selo de Homenagem Compartilhada -->
                                <div class="absolute -top-3.5 left-6 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-secondary text-white text-[11px] font-bold shadow-md uppercase tracking-wider font-sans">
                                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Vela acesa
                                </div>
                            <?php endif; ?>

                            <div>
                                <!-- Topo: De quem acendeu -->
                                <p class="text-xs text-brand-neutral mb-3 mt-1 font-sans">
                                    Vela acesa por <strong class="text-brand-tertiary font-semibold"><?= htmlspecialchars($h['de_quem']) ?></strong>
                                </p>

                                <!-- Container do GIF da Vela (fundo preto com cantos arredondados) -->
                                <div class="w-full h-36 bg-black rounded-2xl overflow-hidden mb-6 flex items-center justify-center">
                                    <img src="./assets/img/vela.gif" alt="Vela acesa" class="h-full w-auto object-contain">
                                </div>

                                <!-- Em memória de -->
                                <div class="mb-4">
                                    <span class="text-xs text-brand-neutral block font-sans">Em memória de</span>
                                    <h3 class="text-xl font-serif font-bold text-brand-tertiary leading-snug">
                                        <?= htmlspecialchars($h['para_quem']) ?>
                                    </h3>
                                </div>

                                <!-- Mensagem -->
                                <p class="text-xs sm:text-sm text-brand-neutral leading-relaxed whitespace-pre-line mb-6 font-sans font-light">
                                    <?= nl2br(htmlspecialchars($h['mensagem'])) ?>
                                </p>
                            </div>

                            <!-- Rodapé do Card: Data e Botão de Compartilhar -->
                            <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-2">
                                <span class="text-xs text-brand-neutral opacity-75 font-sans">
                                    em <?= $data_formatada ?>
                                </span>

                                <button type="button" 
                                        onclick="copiarLinkHomenagem('<?= htmlspecialchars($h['uuid']) ?>', this)"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-brand-secondary bg-brand-secondary/10 hover:bg-brand-secondary hover:text-white rounded-lg transition-colors font-sans">
                                    <i data-lucide="share-2" class="w-3.5 h-3.5"></i>
                                    <span class="btn-text">Compartilhe essa homenagem</span>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </section>

    <!-- MODAL DE CONFIRMAÇÃO APÓS SUBMISSÃO -->
    <div id="modalConfirmacao" class="fixed inset-0 bg-brand-tertiary/80 backdrop-blur-sm <?= $novo_uuid ? 'flex' : 'hidden' ?> items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-8 text-center shadow-2xl border border-gray-100 animate-fade-in">
            <div class="w-16 h-16 rounded-full bg-brand-secondary/10 text-brand-secondary flex items-center justify-center mx-auto mb-4">
                <i data-lucide="flame" class="w-8 h-8 fill-current"></i>
            </div>
            <h3 class="text-2xl font-serif font-bold text-brand-tertiary mb-2">Vela acesa com sucesso</h3>
            <p class="text-sm text-brand-neutral mb-6 leading-relaxed">
                Sua homenagem já foi registrada em nosso velário e permanecerá iluminando a memória do seu ente querido.
            </p>
            <button type="button" onclick="fecharModalEIr('<?= htmlspecialchars($novo_uuid) ?>')" class="w-full bg-brand-secondary hover:bg-brand-dark-green text-white font-bold py-3.5 rounded-full transition shadow-md">
                Ver Homenagem no Velário
            </button>
        </div>
    </div>

    <!-- FOOTER -->
    <div id="footer-placeholder"></div>

    <script src="./formataCabecalhoRodape.js"></script>

    <script>
        // Inicializar ícones
        lucide.createIcons();

        // Fechar Modal e ir para o UUID no velário
        function fecharModalEIr(uuid) {
            window.location.href = `luz-que-ampara.php?uuid=${uuid}#homenagem-${uuid}`;
        }

        // Se houver UUID na URL, rolar a página diretamente até a homenagem
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const uuid = urlParams.get('uuid');
            if (uuid) {
                const card = document.getElementById(`homenagem-${uuid}`);
                if (card) {
                    setTimeout(() => {
                        card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 400);
                }
            }
        });

        // Copiar Link e dar Feedback visual
        function copiarLinkHomenagem(uuid, btnElement) {
            const url = `${window.location.origin}${window.location.pathname}?uuid=${uuid}#homenagem-${uuid}`;
            const btnText = btnElement.querySelector('.btn-text');
            const originalText = btnText.innerText;

            navigator.clipboard.writeText(url).then(() => {
                btnText.innerText = 'Link copiado!';
                btnElement.classList.add('bg-brand-secondary', 'text-white');
                btnElement.classList.remove('bg-brand-secondary/10', 'text-brand-secondary');

                setTimeout(() => {
                    btnText.innerText = originalText;
                    btnElement.classList.remove('bg-brand-secondary', 'text-white');
                    btnElement.classList.add('bg-brand-secondary/10', 'text-brand-secondary');
                }, 2500);
            }).catch(err => {
                console.error('Erro ao copiar link:', err);
            });
        }
    </script>
</body>
</html>