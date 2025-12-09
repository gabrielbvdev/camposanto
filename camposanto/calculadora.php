<?php
// Armazenar as variáveis em arrays
$planosFunerarios = array(
    'amparo'        => array('valor' => 89.9,'idade' => 60),
    'acolhimento'   => array('valor' => 99.9,'idade' => 70),
    'tranquilidade' => array('valor' => 119.9,'idade' => 80),
    'paz'           => array('valor' => 149.9,'idade' => 'Sem limite de idade')
);

$servicos = array(
    1  => array('descricao' => 'Cobertura Funeral', 'valor' => 5000.0),
    2  => array('descricao' => 'Cobertura Cemiterial', 'valor' => 3500.0),
    3  => array('descricao' => 'Sala para velório', 'valor' => 1800.0),
    4  => array('descricao' => 'Cremação', 'valor' =>  5000.0),
    5  => array('descricao' => 'Urna funerária (Primeira linha)', 'valor' => 3000.0),
    6  => array('descricao' => 'Ornamentação com flores naturais', 'valor' => 300.0),
    7  => array('descricao' => 'Coroa de flores', 'valor' => 400.0),
    8  => array('descricao' => 'Preparação do corpo', 'valor' => 1000.0),
    9  => array('descricao' => 'Translado funebre(Até 200km)', 'valor' => 2500.0)
);

$link = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

$html = '';

if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] == 'somaServicos') {
    $servicosMarcados = explode(',', $_REQUEST['servicos']);
    $valor = 0;
    foreach ($servicosMarcados as $marcado) {
        $valor += $servicos[$marcado]['valor'];
    }
    echo $valor;
    exit;
    
}
if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] == 'selecionaPlano') {
    foreach ($planosFunerarios as $nome => $plano) {
        if (($_REQUEST['idade'] <= $plano['idade']) && is_numeric($plano['idade'])) {
            $planoEscolhido = $plano;
            $planoEscolhido['nome'] = $nome;
            break;
        }
    }
    if ($_REQUEST['idade'] > $planosFunerarios['tranquilidade']['idade']){
        $planoEscolhido = $planosFunerarios['paz'];
        $planoEscolhido['nome'] = 'paz';
    }
    echo json_encode($planoEscolhido);
    exit;
}


$html .= "
<style>
    /* VARIÁVEIS DE CORES */
    :root {
        --cor-azul: #16A19F;
        --cor-dourado-escuro: #BC9139;
        --cor-dourado-claro: #B69D63;
        --cor-destaque-vermelho: #C0392B;
        --cor-texto-principal: white;
    }

    /* ESTILO DO CONTAINER PRINCIPAL (AUMENTO DE LARGURA) */
    .calculadora-container {
        max-width: 650px; /* Aumento da largura para reduzir a compressão vertical */
        margin: 20px auto;
        background-color: var(--cor-azul);
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        color: var(--cor-texto-principal);
        font-family: Arial, sans-serif;
    }

    /* Estilos de Título */
    h1 {
        color: var(--cor-texto-principal); /* Título em branco */
        text-align: center;
        margin: -25px -25px 25px -25px; /* Estende para as bordas do container */
        padding: 15px 10px;
        font-size: 1.8em; /* Aumenta o tamanho */
        border-bottom: 3px solid var(--cor-dourado-claro);
        border-radius: 10px 10px 0 0; /* Apenas as bordas superiores arredondadas */
    }
    // h1 {
    //     color: var(--cor-dourado-claro); /* Texto dourado no fundo azul */
    //     text-align: center;
    //     margin-bottom: 25px; /* Espaço abaixo do título */
    //     padding-bottom: 10px;
    //     font-size: 1.8em; /* Mantém o tamanho grande */
    //     /* Retorna a linha abaixo do título (separador) */
    //     border-bottom: 3px solid var(--cor-dourado-escuro); 
    //     /* Removidas as regras de background-color e margin que estendiam o bloco */
    // }

    /* Container para os Formulários Lado a Lado (Flexbox) */
    .forms-wrapper {
        display: flex;
        flex-direction: column; /* Padrão em telas menores */
        gap: 20px;
        margin-bottom: 20px;
    }

    /* Configuração para o Layout Lado a Lado */
    @media (min-width: 500px) { /* Ajustei o breakpoint para funcionar bem na nova largura */
        .forms-wrapper {
            flex-direction: row;
        }
    }

    /* Garantir que cada formulário ocupe o espaço */
    #formUsuarios, #formServicos {
        flex: 1;
        min-width: 0;
    }

    /* Estilo dos Formulários (Inputs e Serviços) */
    form {
        background-color: rgba(255, 255, 255, 0.1);
        padding: 15px;
        border-radius: 6px;
        /* Ajuste para que o formulário de serviços tenha um scroll vertical caso a altura exceda */
        max-height: 400px;
        overflow-y: auto;
    }

    /* Estilo dos Inputs e Labels */
    label {
        display: block;
        font-weight: bold;
        color: var(--cor-texto-principal);
        margin-top: 10px;
        margin-bottom: 5px;
    }

    input[type='text'], input[type='number'] {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 4px;
        box-sizing: border-box;
        background-color: white;
        color: #333;
    }

    /* Estilo dos Checkboxes */
    #formServicos label {
        font-weight: normal;
        display: inline-block;
        margin-left: 8px;
        margin-bottom: 8px;
    }

    /* Estilo do Botão Calcular */
    #buttonCalcular {
        background-color: var(--cor-dourado-escuro);
        color: var(--cor-texto-principal);
        padding: 12px 25px;
        border: none;
        border-radius: 6px;
        font-size: 1.1em;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s;
        display: block;
        width: 100%;
        margin-top: 30px;
        text-transform: uppercase;
    }

    #buttonCalcular:hover:not(:disabled) {
        background-color: var(--cor-dourado-claro);
    }

    #buttonCalcular:disabled {
        background-color: #777;
        cursor: not-allowed;
    }

    /* --- ESTILOS DE RESULTADO (LOADER E EXIBIÇÃO) --- */
    #resultado-container {
        display: none;
        text-align: center;
        margin-top: 30px;
        padding: 15px;
        border-radius: 6px;
        background-color: var(--cor-azul);
        color: var(--cor-texto-principal);
        
        /* NOVO: Flexbox para alinhar resultados lado a lado */
        display: flex; 
        flex-direction: column; /* Padrão em telas menores ou loader */
        gap: 15px;
    }

    /* O loader deve ficar centralizado, forçando o flex-direction column quando ativo */
    #loader-circle {
        display: none; /* Inicia escondido, a função JS controla */
    }

    /* Container de resultados após o loader (após o display ser 'flex') */
    .resultado-container-flex {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    /* Media query para colocar resultados lado a lado quando a largura for suficiente */
    @media (min-width: 500px) {
        .resultado-container-flex {
            flex-direction: row; /* Resultados lado a lado */
        }
    }

    /* Estilos dos Resultados (Aplicado a ambos os boxes de resultado) */
    .resultado-box {
        padding: 10px; /* Reduz o padding para dar mais espaço ao valor */
        border-radius: 6px;
        font-size: 1.0em; /* Fonte base um pouco menor */
        font-weight: bold;
        text-align: center;
        flex: 1;
        min-height: 100px; /* Garante que os blocos tenham altura mínima similar */
        display: flex; /* Flexbox para alinhar conteúdo verticalmente */
        flex-direction: column;
        justify-content: center; /* Centraliza o conteúdo verticalmente */
    }

    /* 1. Estilo para o Plano (Dourado - Planejado) */
    #planoEscolhido {
        background-color: var(--cor-dourado-escuro);
        color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    #planoEscolhido strong {
        font-size: 1.8em; /* Valor em destaque (ex: R$ 1.8) */
        display: block;
        margin-top: 5px;
        color: #e8e8e8; /* Cor ligeiramente mais clara para o valor */
    }

    /* 2. Estilo para os Serviços Privados (Vermelho - Inesperado) */
    #somaServicos {
        background-color: var(--cor-destaque-vermelho);
        color: white;
        text-transform: uppercase;
        border: 1px solid white;
    }

    #somaServicos strong {
        font-size: 2.2em; /* Valor bem maior (ex: R$ 5.000,00) */
        display: block;
        margin-top: 5px;
        font-weight: 900;
    }

    /* Estilo do Loader */
    .loader {
        border: 6px solid rgba(255, 255, 255, 0.3);
        border-top: 6px solid var(--cor-dourado-claro);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
";

$html .= "<div class='calculadora-container'>";
$html .= "<h1>Compare e comprove!</h1>";

$html .= "<div class='forms-wrapper'>";

// Form usuários
$html .= "
    <form action='' method='post' id='formUsuarios'>
            <label for='nome'>Digite seu nome:</label>
            <input type='text' id='nome' name='nome'>
            <br><br>
            <label for='telefone'>Digite seu telefone:</label>
            <input type='text' id='telefone' name='telefone'>
            <br><br>
            <label for='pessoas'>Quantas pessoas você quer proteger?</label>
            <input type='number' id='pessoas' name='pessoas'>
            <br><br>
            <label for='idade'>Qual é a idade da pessoa mais velha?</label>
            <input type='number' id='idade' name='idade'>
    </form>
";

// Form servicos
$html .= "<form action='' method='post' id='formServicos'>";
$html .= "<label><strong>Serviços Privados:</strong></label><br>"; // Novo Título para o formulário de serviços
foreach ($servicos as $key=>$servico) {
    $html .= "
        <input type='checkbox' id='servico_".$key."' name='servicos' value=".$key.">
        <label for='servico_".$key."'>".$servico['descricao']."</label>
        <br>
    ";
}
$html .= "</form>";
$html .= "</div>";

$html .= "<button type='button' id='buttonCalcular' onclick='calcular()'>Calcular</button>";

$html .= "
    <div id='resultado-container'>
        <div id='loader-circle' class='loader'></div>
        
        <div id='boxes-resultado' class='resultado-container-flex' style='display: none;'> 
            <h4 id='planoEscolhido' class='resultado-box'></h4>
            <h4 id='somaServicos' class='resultado-box'></h4>
        </div>
    </div>
";

$html .= "</div>";

$html .= "
<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
<script>
    function calcular() {
        var checkboxes = document.querySelectorAll(\"input[type='checkbox']\");
        var idade = document.getElementById('idade').value;
        var dados = '';
        
        // 1. Coleta os dados dos serviços marcados
        for (let i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                dados = dados + (dados == '' ? '' : ',') + checkboxes[i].value;
            }
        }
        
        // --- Controle do Loading ---
        const resultadoContainer = document.getElementById('resultado-container');
        const loader = document.getElementById('loader-circle');
        const boxesResultado = document.getElementById('boxes-resultado');
        const planoElem = document.getElementById('planoEscolhido');
        const servicosElem = document.getElementById('somaServicos');
        const btnCalcular = document.getElementById('buttonCalcular');

        // Mostra o container, esconde os resultados, mostra o loader
        resultadoContainer.style.display = 'flex';
        loader.style.display = 'block';
        boxesResultado.style.display = 'none';
        planoElem.innerHTML = '';
        servicosElem.innerHTML = '';
        btnCalcular.disabled = true;

        // 2. Aplica o delay de 3 segundos
        setTimeout(function() {
            
            loader.style.display = 'none';
            boxesResultado.style.display = 'flex';
            
            // --- Chamada 1: Soma dos Serviços (Box Vermelho) ---
            $.ajax({
                url: 'calculadora.php?ajax=somaServicos&servicos=' + dados,
                method: 'POST',
                success: function(data) {
                    const valorFormatado = parseFloat(data).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    // Texto em duas linhas: rótulo e valor destacado
                    servicosElem.innerHTML = 'VALOR INESPERADO DOS SERVIÇOS PRIVADOS: <br><strong>R$ ' + valorFormatado + '</strong>';
                },
                error: function() {
                    servicosElem.innerHTML = '⚠️ Erro ao calcular serviços.';
                }
            });

            // --- Chamada 2: Seleção do Plano (Box Dourado) ---
            $.ajax({
                url: 'calculadora.php?ajax=selecionaPlano&idade=' + idade,
                method: 'POST',
                success: function(data) {
                    var plano = JSON.parse(data);
                    const valorPlanoFormatado = parseFloat(plano['valor']).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    // CORREÇÃO AQUI: Usando planoElem (o box dourado)
                    planoElem.innerHTML = 'PLANO ' + plano['nome'].toUpperCase() + ': <br><strong>R$ ' + valorPlanoFormatado + '</strong>';
                },
                error: function() {
                    planoElem.innerHTML = '⚠️ Erro ao selecionar plano.';
                },
                complete: function() {
                    btnCalcular.disabled = false;
                }
            });

        }, 3000);
    }
</script>
";

echo $html;
?>