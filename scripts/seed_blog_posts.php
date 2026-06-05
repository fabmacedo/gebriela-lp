<?php

require_once __DIR__ . '/../includes/db.php';

$pdo = db();
if (!$pdo) {
    fwrite(STDERR, "Banco não conectado.\n");
    exit(1);
}

$posts = [
    [
        'title' => 'Rescisão trabalhista: o que observar antes de assinar documentos',
        'slug' => 'rescisao-trabalhista-o-que-observar-antes-de-assinar-documentos',
        'excerpt' => 'Entenda pontos de atenção na rescisão do contrato de trabalho e por que uma análise jurídica pode evitar prejuízos.',
        'content' => "A rescisão trabalhista é um momento que exige atenção. Antes de assinar recibos, termos ou acordos, é importante conferir se as verbas foram calculadas corretamente e se todos os valores prometidos constam nos documentos.\n\nEntre os pontos que merecem cuidado estão saldo de salário, aviso-prévio, férias vencidas ou proporcionais, décimo terceiro, FGTS, multa rescisória e eventuais descontos. Também é importante guardar comprovantes, mensagens, contracheques e documentos recebidos da empresa.\n\nCada caso precisa ser analisado individualmente, pois o tipo de desligamento altera os direitos envolvidos. Uma orientação jurídica prévia ajuda o trabalhador a compreender o cenário antes de tomar decisões definitivas.",
        'published_at' => '2026-06-02 09:00:00',
    ],
    [
        'title' => 'Assédio moral no trabalho: sinais que merecem atenção',
        'slug' => 'assedio-moral-no-trabalho-sinais-que-merecem-atencao',
        'excerpt' => 'Situações repetidas de humilhação, perseguição ou constrangimento podem afetar a saúde e gerar consequências jurídicas.',
        'content' => "O assédio moral pode ocorrer quando o trabalhador é exposto, de forma repetida, a situações humilhantes, constrangedoras ou abusivas no ambiente de trabalho. Ele pode aparecer em cobranças excessivas, isolamento, ameaças, comentários depreciativos ou perseguições.\n\nNem toda cobrança configura assédio, mas condutas abusivas e recorrentes merecem atenção. É recomendável registrar datas, horários, testemunhas, mensagens e qualquer documento que ajude a demonstrar o ocorrido.\n\nAlém do impacto emocional, essas situações podem gerar responsabilização. A análise jurídica é importante para avaliar provas, contexto e caminhos possíveis.",
        'published_at' => '2026-06-02 09:10:00',
    ],
    [
        'title' => 'Trabalho sem carteira assinada: quais cuidados tomar',
        'slug' => 'trabalho-sem-carteira-assinada-quais-cuidados-tomar',
        'excerpt' => 'Mesmo sem registro formal, uma relação de trabalho pode gerar direitos quando presentes os requisitos legais.',
        'content' => "Muitas pessoas trabalham sem carteira assinada e acreditam que, por isso, não possuem direitos. Em diversas situações, porém, a realidade da prestação de serviços pode demonstrar vínculo de emprego.\n\nElementos como pessoalidade, habitualidade, subordinação e pagamento são relevantes para a análise. Provas como mensagens, comprovantes de pagamento, escala de horários, uniformes, crachás, fotos e testemunhas podem ajudar.\n\nCada caso depende das circunstâncias concretas. Por isso, antes de tomar qualquer medida, é indicado organizar documentos e buscar orientação para entender as possibilidades.",
        'published_at' => '2026-06-02 09:20:00',
    ],
    [
        'title' => 'Acidente de trabalho: documentos que ajudam na análise do caso',
        'slug' => 'acidente-de-trabalho-documentos-que-ajudam-na-analise-do-caso',
        'excerpt' => 'Após um acidente laboral, reunir documentos desde o início pode fazer diferença na proteção dos direitos.',
        'content' => "O acidente de trabalho exige atenção imediata à saúde do trabalhador e também à documentação do ocorrido. Registros médicos, atestados, exames, fotos, comunicações internas e relatos de testemunhas podem ser relevantes.\n\nTambém é importante guardar informações sobre o local, horário, atividade realizada e eventuais equipamentos de proteção fornecidos. Quando houver afastamento, documentos do INSS e da empresa devem ser preservados.\n\nA análise jurídica considera o contexto do acidente, as responsabilidades envolvidas e os impactos na vida profissional e pessoal do trabalhador.",
        'published_at' => '2026-06-02 09:30:00',
    ],
    [
        'title' => 'Doença ocupacional: quando o trabalho pode estar relacionado ao adoecimento',
        'slug' => 'doenca-ocupacional-quando-o-trabalho-pode-estar-relacionado-ao-adoecimento',
        'excerpt' => 'Algumas doenças podem ter relação com a atividade profissional, exigindo análise médica e jurídica cuidadosa.',
        'content' => "Doenças ocupacionais são aquelas que podem estar relacionadas às condições de trabalho ou à forma como a atividade é desempenhada. Lesões por esforço repetitivo, problemas de coluna, adoecimentos emocionais e outras condições podem exigir investigação.\n\nA comprovação costuma depender de documentos médicos, histórico profissional, exames, laudos e informações sobre a rotina de trabalho. Não basta apenas a existência da doença; é necessário avaliar o nexo com a atividade.\n\nPor isso, a atuação técnica é importante para organizar documentos, compreender o caso e identificar os caminhos adequados.",
        'published_at' => '2026-06-02 09:40:00',
    ],
    [
        'title' => 'Horas extras: por que controlar a jornada é importante',
        'slug' => 'horas-extras-por-que-controlar-a-jornada-e-importante',
        'excerpt' => 'Registros de jornada, mensagens e rotinas de trabalho podem ser essenciais para avaliar horas extras.',
        'content' => "Horas extras são um tema frequente nas relações de trabalho. Para analisar se há valores devidos, é necessário compreender a jornada praticada, intervalos, folgas, banco de horas e forma de controle utilizada pela empresa.\n\nO trabalhador pode guardar escalas, prints de sistemas, mensagens sobre horários, comprovantes de deslocamento e outros registros que indiquem a rotina. Testemunhas também podem ser relevantes.\n\nComo cada contrato possui particularidades, a análise deve considerar documentos, função exercida e dinâmica real do trabalho.",
        'published_at' => '2026-06-02 09:50:00',
    ],
    [
        'title' => 'Justa causa: a importância de analisar a proporcionalidade da medida',
        'slug' => 'justa-causa-a-importancia-de-analisar-a-proporcionalidade-da-medida',
        'excerpt' => 'A dispensa por justa causa é uma medida grave e deve ser avaliada com cuidado em cada situação.',
        'content' => "A justa causa traz consequências importantes para o trabalhador, pois reduz verbas rescisórias e marca o encerramento do contrato por falta grave. Por isso, sua aplicação precisa ser analisada com atenção.\n\nÉ importante verificar o motivo alegado, a existência de provas, a proporcionalidade da punição, o histórico funcional e a forma como a empresa conduziu o desligamento. Nem toda falta autoriza automaticamente a medida mais severa.\n\nQuando houver dúvida, o ideal é reunir documentos e buscar orientação para avaliar se existem fundamentos para contestação.",
        'published_at' => '2026-06-02 10:00:00',
    ],
    [
        'title' => 'Acordos trabalhistas: segurança jurídica começa com informação',
        'slug' => 'acordos-trabalhistas-seguranca-juridica-comeca-com-informacao',
        'excerpt' => 'Acordos podem ser úteis, mas precisam ser avaliados com clareza para evitar renúncias indevidas.',
        'content' => "Acordos trabalhistas podem representar uma solução eficiente quando construídos com transparência e segurança. Antes de aceitar qualquer proposta, é importante entender quais direitos estão envolvidos e quais valores estão sendo negociados.\n\nUm acordo deve considerar documentos, riscos, provas, tempo de tramitação e interesse real das partes. A pressa, sem orientação adequada, pode levar a decisões pouco vantajosas.\n\nA atuação jurídica ajuda a traduzir o cenário, avaliar os termos e buscar uma solução equilibrada.",
        'published_at' => '2026-06-02 10:10:00',
    ],
    [
        'title' => 'Assessoria trabalhista para empresas: prevenção também é estratégia',
        'slug' => 'assessoria-trabalhista-para-empresas-prevencao-tambem-e-estrategia',
        'excerpt' => 'Empresas podem reduzir riscos com contratos, rotinas internas e orientação preventiva adequada.',
        'content' => "A assessoria trabalhista empresarial não se limita à defesa em processos. A prevenção é uma ferramenta estratégica para organizar relações de trabalho, reduzir riscos e melhorar a tomada de decisão.\n\nContratos, controles de jornada, políticas internas, procedimentos disciplinares, documentação e treinamento de lideranças são pontos que merecem cuidado. Pequenas falhas de rotina podem gerar conflitos maiores no futuro.\n\nCom acompanhamento jurídico, a empresa ganha mais segurança para crescer, contratar e gerir equipes com responsabilidade.",
        'published_at' => '2026-06-02 10:20:00',
    ],
    [
        'title' => 'Atendimento jurídico online: praticidade sem perder a proximidade',
        'slug' => 'atendimento-juridico-online-praticidade-sem-perder-a-proximidade',
        'excerpt' => 'O atendimento remoto pode facilitar o acesso à orientação jurídica com organização e segurança.',
        'content' => "O atendimento jurídico online se tornou uma alternativa prática para clientes que precisam de orientação sem deslocamento imediato. Ele permite envio de documentos, reuniões por vídeo e acompanhamento mais ágil.\n\nPara funcionar bem, é importante que o cliente organize informações básicas, documentos e uma linha do tempo dos fatos. Isso ajuda o advogado a compreender melhor o caso desde o primeiro contato.\n\nMesmo à distância, o atendimento deve preservar clareza, sigilo, transparência e proximidade humana.",
        'published_at' => '2026-06-02 10:30:00',
    ],
    [
        'title' => 'Documentos importantes para uma primeira análise trabalhista',
        'slug' => 'documentos-importantes-para-uma-primeira-analise-trabalhista',
        'excerpt' => 'Uma boa organização documental permite uma avaliação mais clara dos direitos e riscos envolvidos.',
        'content' => "A primeira análise trabalhista fica mais eficiente quando o cliente reúne documentos relevantes. Contrato, carteira de trabalho, contracheques, extratos de FGTS, cartões de ponto, mensagens, termos de rescisão e comprovantes de pagamento podem ajudar.\n\nTambém é útil escrever um resumo com datas importantes: admissão, desligamento, mudanças de função, afastamentos, acidentes, conversas relevantes e pagamentos pendentes.\n\nQuanto mais organizado estiver o material, melhor será a compreensão jurídica do caso e das alternativas possíveis.",
        'published_at' => '2026-06-02 10:40:00',
    ],
    [
        'title' => 'Advocacia humanizada: técnica jurídica e escuta caminham juntas',
        'slug' => 'advocacia-humanizada-tecnica-juridica-e-escuta-caminham-juntas',
        'excerpt' => 'Um atendimento humanizado une estratégia, clareza e respeito à história de cada cliente.',
        'content' => "A advocacia humanizada não significa abrir mão da técnica. Pelo contrário: ela une conhecimento jurídico, estratégia e escuta cuidadosa para compreender a realidade de cada cliente.\n\nProcessos envolvem documentos, prazos e provas, mas também envolvem histórias, expectativas e decisões importantes. Um atendimento claro ajuda o cliente a entender riscos, possibilidades e próximos passos.\n\nEssa postura fortalece a confiança e torna a atuação jurídica mais responsável, transparente e eficiente.",
        'published_at' => '2026-06-02 10:50:00',
    ],
];

$slugs = array_column($posts, 'slug');
$placeholders = implode(',', array_fill(0, count($slugs), '?'));
$pdo->prepare("DELETE FROM blog_posts WHERE slug = 'post-de-teste-docker' OR slug IN ($placeholders)")->execute($slugs);

$stmt = $pdo->prepare(
    'INSERT INTO blog_posts (title, slug, excerpt, content, status, published_at)
     VALUES (:title, :slug, :excerpt, :content, "published", :published_at)'
);

foreach ($posts as $post) {
    $stmt->execute($post);
}

echo count($posts) . " posts publicados.\n";
