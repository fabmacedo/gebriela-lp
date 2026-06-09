<?php
require_once __DIR__ . '/includes/site.php';
require_once __DIR__ . '/includes/security.php';
require_once __DIR__ . '/includes/front-header.php';

security_headers(false);
csrf_token();

$settings = get_site_settings();
$settings['hero_image_url'] = 'image/foto-hero.png';
if (in_array(trim($settings['seo_og_image'] ?? ''), ['', 'image/foto1.png'], true)) {
    $settings['seo_og_image'] = 'image/foto-hero.png';
}

$whatsapp_friendly = $settings['whatsapp_friendly'];
$email_contato = $settings['email_contato'];
$oab_registro = $settings['oab_registro'];
$nome_escritorio = $settings['nome_escritorio'];
$endereco_local = $settings['endereco_local'];
$google_reviews_url = $settings['google_reviews_url'];
$hero_image_url = $settings['hero_image_url'];
$whatsapp_link = $settings['whatsapp_link'];

$situations = [
    ['Acidente durante o trabalho', 'Quedas, cortes, fraturas, queimaduras ou outros acontecimentos durante a atividade profissional.', 'first-aid-kit'],
    ['Acidente de trajeto ou a serviço', 'Ocorrências no percurso entre casa e trabalho, em viagens ou no cumprimento de ordens da empresa.', 'map-trifold'],
    ['LER/DORT, dores e limitações', 'Lesões por esforço repetitivo, dores persistentes e perda de mobilidade que afetam sua rotina.', 'hand'],
    ['Problemas de coluna e lesões', 'Condições relacionadas a peso, postura, esforço físico ou movimentos repetidos.', 'person-simple'],
    ['Perda auditiva e exposições', 'Adoecimento relacionado a ruído, produtos químicos ou outros agentes presentes no trabalho.', 'ear'],
    ['Burnout e saúde emocional', 'Ansiedade, depressão, esgotamento ou outros adoecimentos possivelmente ligados ao ambiente profissional.', 'brain'],
    ['Doença agravada pelo trabalho', 'Uma condição anterior também pode merecer análise quando o trabalho contribui para seu agravamento.', 'heartbeat'],
    ['Dispensa após adoecimento', 'Situações em que a demissão ocorreu durante ou depois de afastamento, acidente ou descoberta da doença.', 'user-minus'],
];

$rights = [
    ['Relação com o trabalho', 'Análise do vínculo entre a atividade exercida, as condições de trabalho e o adoecimento.', 'link'],
    ['CAT', 'Avaliação sobre a emissão ou regularização da Comunicação de Acidente de Trabalho.', 'file-text'],
    ['Benefício por incapacidade', 'Verificação da natureza do afastamento e dos requisitos previdenciários aplicáveis.', 'identification-card'],
    ['Possível estabilidade', 'Análise das condições legais relacionadas à manutenção do emprego após afastamento acidentário.', 'shield-check'],
    ['Auxílio-acidente', 'Avaliação nas hipóteses legais em que permanecem sequelas com redução definitiva da capacidade.', 'hand-coins'],
    ['Retorno e readaptação', 'Orientação sobre retorno ao trabalho, reabilitação profissional e compatibilidade das atividades.', 'arrows-counter-clockwise'],
    ['Responsabilidade da empresa', 'Análise das medidas de segurança adotadas e de eventual reparação de danos.', 'scales'],
    ['Doença descoberta após a dispensa', 'A descoberta posterior não impede, por si só, que a relação com o trabalho seja analisada.', 'magnifying-glass'],
];

$reviewCards = [
    ['name' => 'Gilvonete Felix', 'quote' => 'Uma experiência maravilhosa! Uma excelente advogada! Eu super indico.', 'date' => '3 meses atrás', 'initial' => 'G', 'avatar' => null, 'avatarClass' => 'bg-[#F27A1A] text-white'],
    ['name' => 'Ana Carolina', 'quote' => 'Ambiente agradável, advogados habilidosos, prestativos e Dra. Gabriela muito competente!', 'date' => '3 meses atrás', 'initial' => 'A', 'avatar' => 'image/reviews/ana-carolina-pedreira.png', 'avatarClass' => 'bg-wine text-cream'],
    ['name' => 'Carlos Alberto', 'quote' => 'Excelente profissional... trabalho feito com muita dedicação e compromisso, parabéns!', 'date' => '3 meses atrás', 'initial' => 'C', 'avatar' => null, 'avatarClass' => 'bg-wine text-cream'],
];

$faqs = [
    ['O que pode ser considerado acidente de trabalho?', 'A legislação considera acidente de trabalho o acontecimento ligado ao exercício da atividade que provoque lesão, perda ou redução temporária ou permanente da capacidade. Algumas situações ocorridas fora do local de trabalho, como acidente de trajeto ou a serviço da empresa, também podem ser equiparadas. Cada caso precisa ser analisado conforme os fatos.'],
    ['Doença ocupacional também pode ser considerada acidente de trabalho?', 'Pode. A doença profissional ou a doença desenvolvida em razão das condições especiais em que o trabalho é realizado pode ser equiparada a acidente de trabalho. A análise considera função, ambiente, exposição, histórico médico e relação entre o adoecimento e a atividade.'],
    ['Burnout, ansiedade ou depressão podem ter relação com o trabalho?', 'Podem ter, quando as condições e a organização do trabalho contribuíram para o surgimento ou agravamento do quadro. Metas, jornadas, assédio, sobrecarga e ambiente profissional podem ser relevantes, mas o diagnóstico isolado não comprova automaticamente a relação.'],
    ['A doença precisa ter sido causada somente pelo trabalho?', 'Não necessariamente. Em determinadas situações, o trabalho pode ter contribuído diretamente para o adoecimento ou para o agravamento de uma condição anterior. Essa contribuição precisa ser demonstrada por meio da análise médica, documental e das condições reais de trabalho.'],
    ['O que é CAT e quem pode emitir?', 'A CAT comunica ao INSS um acidente de trabalho, de trajeto ou uma doença ocupacional. A empresa tem o dever de comunicar a ocorrência no prazo legal. Se isso não acontecer, a legislação permite a comunicação pelo próprio trabalhador, dependentes, sindicato, médico ou autoridade pública.'],
    ['Fui dispensado e descobri a doença depois. Ainda posso buscar orientação?', 'Sim. A descoberta da doença após a dispensa não impede, por si só, a avaliação da relação com o trabalho. Documentos médicos, histórico profissional e provas das condições da atividade ajudam a compreender a situação e os possíveis caminhos.'],
    ['Acidente ou doença ocupacional sempre gera estabilidade?', 'Não. A estabilidade depende do preenchimento de requisitos legais e das circunstâncias do caso. Em regra, a lei prevê manutenção do contrato por pelo menos 12 meses após a cessação do benefício acidentário, e existem situações específicas reconhecidas pela Justiça do Trabalho.'],
    ['Quais documentos ajudam na análise?', 'Laudos, exames, atestados, prontuários, CAT, documentos do INSS, carteira de trabalho, holerites, mensagens, fotos e uma linha do tempo dos fatos são úteis. Mesmo sem todos eles, é possível iniciar uma conversa para entender o que pode ser organizado.'],
    ['O INSS negou ou classificou meu benefício como comum. O que fazer?', 'A decisão pode ser analisada junto com os documentos médicos e profissionais para verificar se existem elementos que indiquem natureza acidentária ou necessidade de revisão. O caminho adequado depende do motivo da decisão e das provas disponíveis.'],
    ['Posso buscar orientação mesmo sem ter todos os documentos?', 'Sim. O atendimento inicial também serve para identificar quais documentos existem, quais podem ser obtidos e o que é importante preservar. Evite adiar o cuidado com sua saúde enquanto organiza as informações.'],
];

$areas = [
    'Acidente de trabalho',
    'Doença física relacionada ao trabalho',
    'Adoecimento emocional relacionado ao trabalho',
    'Benefício do INSS ou CAT',
    'Dispensa após acidente ou adoecimento',
    'Outra situação relacionada à saúde no trabalho',
];
?>
<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php render_seo_meta($settings, ['canonical' => '']); ?>
<?php render_favicon_links($settings); ?>
<?php render_legal_schema($settings); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bellefair&family=Jost:wght@300;400;500&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
<?php render_phosphor_icons(); ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { wine:'#5A0707', wineDark:'#2F130D', cream:'#F4EDE4', paper:'#FFF8EF', bordo:'#3F070A', bordoDeep:'#260305', sand:'#D7C2A8', ink:'#2A1713', muted:'#8B766D' },
            fontFamily: { sans:['Jost','sans-serif'], serif:['Playfair Display','serif'], bellefair:['Bellefair','serif'] }
        } } };
    </script>
    <style>
<?php render_front_header_styles(); ?>
        .soft-radius { border-radius: 10px; }
        main > section + section, main + footer { border-top: 1px solid #6B181D; }
        .hero-overlay { background: linear-gradient(90deg, rgba(38,3,5,.94) 0%, rgba(63,7,10,.78) 38%, rgba(63,7,10,.18) 74%, rgba(38,3,5,.12) 100%), linear-gradient(0deg, rgba(18,7,5,.66), transparent 55%); }
        .faq-content { display:grid; grid-template-rows:0fr; opacity:0; transition:grid-template-rows .32s ease,opacity .24s ease; }
        details[open] .faq-content { grid-template-rows:1fr; opacity:1; }
        .faq-content-inner { overflow:hidden; }
        .faq-icon { transition:transform .24s ease; }
        details[open] .faq-icon { transform:rotate(180deg); }
        body.font-sans { font-size:17px; line-height:1.42; }
        body.font-sans p, body.font-sans li, body.font-sans a, body.font-sans button, body.font-sans input, body.font-sans textarea, body.font-sans select, body.font-sans label, body.font-sans summary { line-height:1.42; }
        .reveal { opacity:0; translate:0 24px; transition:opacity .8s ease,translate .8s ease; }
        .reveal.active { opacity:1; translate:0 0; }
        @media (max-width:639px) {
            .hero-photo { object-position:78% center; }
            .hero-overlay {
                background:
                    linear-gradient(90deg,rgba(38,3,5,.96) 0%,rgba(63,7,10,.82) 42%,rgba(63,7,10,.2) 72%,rgba(38,3,5,.04) 100%),
                    linear-gradient(0deg,rgba(18,7,5,.76),transparent 58%);
            }
        }
        @media (max-width:479px) {
            .hero-photo { object-position:72% center; }
            .hero-copy.hero-copy,
            .hero-copy .hero-copy-description { max-width:16rem; }
            .hero-copy .hero-title { font-size:2.25rem; }
            .hero-overlay {
                background:
                    linear-gradient(90deg,rgba(38,3,5,.98) 0%,rgba(63,7,10,.88) 48%,rgba(63,7,10,.16) 74%,rgba(38,3,5,.02) 100%),
                    linear-gradient(0deg,rgba(18,7,5,.8),transparent 58%);
            }
        }
        @media (max-width:359px) {
            .hero-photo { object-position:76% center; }
        }
        @media (min-width:640px) and (max-width:1023px) {
            .hero-photo { object-position:72% center; }
            .hero-overlay {
                background:
                    linear-gradient(90deg,rgba(38,3,5,.96) 0%,rgba(63,7,10,.82) 34%,rgba(63,7,10,.14) 58%,rgba(38,3,5,0) 78%),
                    linear-gradient(0deg,rgba(18,7,5,.72),transparent 55%);
            }
        }
        @media (min-width:1024px) and (max-width:1279px) {
            .hero-photo { object-position:68% center; }
        }
    </style>
<?php render_jost_weight_cap_styles(); ?>
</head>
<body class="bg-bordo text-cream font-sans antialiased overflow-x-hidden">
<?php render_front_header($settings, true); ?>
    <main>
        <section id="inicio" class="relative min-h-[780px] overflow-hidden text-cream sm:min-h-screen">
            <img src="<?php echo e($hero_image_url); ?>" alt="" aria-hidden="true" class="hero-photo absolute inset-0 h-full w-full object-cover">
            <div class="hero-overlay absolute inset-0"></div>
            <div class="relative mx-auto flex min-h-[780px] max-w-7xl items-center px-5 pb-16 pt-32 sm:min-h-screen lg:px-8">
                <div class="hero-copy max-w-[20rem] pt-10 reveal sm:max-w-[23rem] md:max-w-[26rem] lg:max-w-[36rem] xl:max-w-[42rem]">
                    <p class="soft-radius inline-flex bg-cream/10 px-4 py-2 text-[11px] font-bold uppercase tracking-[0.18em] text-sand">Acidente de trabalho e doença ocupacional</p>
                    <h1 class="hero-title mt-7 font-serif text-[2.55rem] font-semibold leading-[1.02] sm:text-5xl lg:text-[4.25rem]">Seu trabalho deixou marcas na sua saúde?</h1>
                    <p class="hero-copy-description mt-6 max-w-[20rem] text-base leading-8 text-cream/85 sm:max-w-[23rem] md:max-w-[26rem] lg:max-w-[34rem] xl:max-w-[39rem] lg:text-lg">Quando uma lesão, uma dor persistente ou um adoecimento começa a afetar sua rotina, entender o que aconteceu é o primeiro passo para cuidar da sua saúde e compreender seus direitos.</p>
                    <div class="mt-9 flex flex-col items-start gap-4 md:flex-row md:items-center">
                        <a href="<?php echo e($whatsapp_link); ?>" target="_blank" rel="noopener" class="soft-radius inline-flex min-h-14 items-center justify-center gap-2 border border-white bg-white px-7 py-4 text-xs font-bold uppercase tracking-[0.14em] text-wineDark transition hover:bg-paper"><?php echo ph_icon('whatsapp-logo', 'text-xl'); ?> Conversar sobre meu caso</a>
                        <a href="#situacoes" class="text-xs font-bold uppercase tracking-[0.15em] text-cream/80 transition hover:text-sand">Entender melhor <?php echo ph_icon('arrow-down', 'ml-2 inline text-base'); ?></a>
                    </div>
                    <p class="mt-6 flex max-w-xl items-center gap-2 text-sm text-cream/65"><?php echo ph_icon('check-circle', 'shrink-0 text-lg leading-none text-sand'); ?> Atendimento online e presencial, com análise individual e orientação clara.</p>
                </div>
            </div>
        </section>

        <section id="situacoes" class="bg-paper py-20 text-wineDark md:py-28">
            <div class="mx-auto max-w-7xl px-5 lg:px-8">
                <div class="max-w-3xl reveal">
                    <p class="mb-4 text-[11px] font-bold uppercase tracking-[0.2em] text-wine/65">Situações atendidas</p>
                    <h2 class="font-serif text-4xl leading-tight md:text-6xl">Você pode estar vivendo uma situação relacionada ao trabalho.</h2>
                    <p class="mt-6 max-w-2xl text-base leading-8 text-ink/65">O adoecimento pode surgir de repente ou se desenvolver aos poucos. Reconhecer os sinais ajuda a preservar sua saúde, sua história e as informações do caso.</p>
                </div>
                <div class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <?php foreach ($situations as $item): ?>
                        <article class="soft-radius border border-wine/10 bg-white p-6 shadow-[0_15px_45px_rgba(63,7,10,.07)] transition hover:-translate-y-1 hover:border-wine/30 reveal">
                            <span class="grid h-11 w-11 place-items-center rounded-full bg-wine/7 text-wine"><?php echo ph_icon($item[2], 'text-xl'); ?></span>
                            <h3 class="mt-7 font-serif text-2xl leading-tight"><?php echo e($item[0]); ?></h3>
                            <p class="mt-3 text-sm leading-7 text-ink/62"><?php echo e($item[1]); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
                <p class="soft-radius mt-8 border border-wine/10 bg-wine/5 p-5 text-sm leading-7 text-ink/65 reveal"><strong class="text-wineDark">Importante:</strong> a existência de uma doença ou de um acidente não comprova automaticamente sua relação com o trabalho. Cada situação precisa ser analisada individualmente.</p>
            </div>
        </section>

        <section class="bg-bordoDeep py-20 text-cream md:py-28">
            <div class="mx-auto grid max-w-7xl gap-14 px-5 lg:grid-cols-[.9fr_1.1fr] lg:items-center lg:px-8">
                <div class="relative reveal">
                    <div class="soft-radius overflow-hidden shadow-2xl shadow-black/25"><img src="image/foto4.webp" alt="Atendimento jurídico humanizado" class="aspect-[4/5] w-full object-cover object-[50%_42%]"></div>
                    <div class="soft-radius absolute -bottom-6 right-4 max-w-[18rem] bg-paper p-5 text-wineDark shadow-2xl sm:right-8">
                        <p class="font-serif text-2xl leading-tight">Sua saúde vem primeiro.</p>
                        <p class="mt-2 text-xs leading-6 text-ink/65">Busque atendimento médico e preserve os documentos desde o início.</p>
                    </div>
                </div>
                <div class="reveal">
                    <p class="mb-4 text-[11px] font-bold uppercase tracking-[0.2em] text-sand">Entenda o que aconteceu</p>
                    <h2 class="font-serif text-4xl leading-tight md:text-6xl">Nem todo adoecimento acontece de uma vez.</h2>
                    <div class="mt-8 space-y-5 text-base leading-8 text-cream/72">
                        <p>Um acidente pode ser um acontecimento repentino. Já uma doença ocupacional pode aparecer silenciosamente, depois de meses ou anos de esforço, exposição, sobrecarga ou pressão emocional.</p>
                        <p>Em alguns casos, o trabalho causa o adoecimento. Em outros, contribui para agravar uma condição que já existia. Por isso, o trabalho não precisa ser necessariamente a única causa para que a situação mereça uma análise cuidadosa.</p>
                        <p>A função exercida, o ambiente, a jornada, os riscos, os documentos médicos e a evolução dos sintomas ajudam a compreender se existe relação com o trabalho.</p>
                    </div>
                    <a href="#direitos" class="mt-9 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.16em] text-sand">Veja o que pode ser analisado <?php echo ph_icon('arrow-down', 'text-base'); ?></a>
                </div>
            </div>
        </section>

        <section id="direitos" class="bg-bordo py-20 text-cream md:py-28">
            <div class="mx-auto max-w-7xl px-5 lg:px-8">
                <div class="grid gap-10 lg:grid-cols-[.76fr_1.24fr] lg:gap-16">
                    <aside class="reveal lg:sticky lg:top-28 lg:self-start">
                        <p class="mb-4 text-[11px] font-bold uppercase tracking-[0.2em] text-sand">Entenda seus direitos</p>
                        <h2 class="font-serif text-4xl leading-tight md:text-6xl">O que pode ser analisado no seu caso.</h2>
                        <p class="mt-6 max-w-md text-sm leading-7 text-cream/68">Os direitos não são automáticos. A orientação jurídica considera documentos, fatos, requisitos legais e a relação entre o trabalho e o dano à saúde.</p>
                    </aside>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <?php foreach ($rights as $item): ?>
                            <article class="soft-radius border border-cream/20 bg-white p-6 text-wineDark shadow-[0_18px_50px_rgba(38,3,5,.16)] reveal">
                                <span class="grid h-10 w-10 place-items-center rounded-full bg-wine/7 text-wine"><?php echo ph_icon($item[2], 'text-xl'); ?></span>
                                <h3 class="mt-6 font-serif text-2xl leading-tight"><?php echo e($item[0]); ?></h3>
                                <p class="mt-3 text-sm leading-7 text-ink/65"><?php echo e($item[1]); ?></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <section id="avaliacoes" class="bg-paper py-20 text-wineDark md:py-28">
            <div class="mx-auto max-w-7xl px-5 lg:px-8">
                <div class="mb-12 flex flex-col justify-between gap-6 md:flex-row md:items-end reveal">
                    <div><p class="mb-4 text-[11px] font-bold uppercase tracking-[0.2em] text-wine/65">Avaliações no Google</p><h2 class="max-w-2xl font-serif text-4xl leading-tight md:text-6xl">Confiança construída em cada atendimento.</h2></div>
                    <a href="<?php echo e($google_reviews_url); ?>" target="_blank" rel="noopener" class="soft-radius inline-flex items-center justify-center gap-2 border border-wine bg-white px-6 py-3 text-xs font-bold uppercase tracking-[0.16em] text-wine transition hover:bg-paper">Ver no Google <?php echo ph_icon('arrow-up-right', 'text-base'); ?></a>
                </div>
                <div class="grid gap-5 md:grid-cols-3">
                    <?php foreach ($reviewCards as $review): ?>
                        <article class="soft-radius border border-wine/10 bg-white p-7 shadow-[0_18px_50px_rgba(63,7,10,.08)] reveal">
                            <div class="flex items-center gap-3">
                                <?php if ($review['avatar']): ?><img src="<?php echo e($review['avatar']); ?>" alt="<?php echo e($review['name']); ?>" class="h-12 w-12 rounded-full object-cover"><?php else: ?><span class="flex h-12 w-12 items-center justify-center rounded-full text-lg <?php echo e($review['avatarClass']); ?>"><?php echo e($review['initial']); ?></span><?php endif; ?>
                                <div><h3 class="font-serif text-2xl"><?php echo e($review['name']); ?></h3><p class="mt-1 text-[10px] uppercase tracking-[0.16em] text-wine/50"><?php echo e($review['date']); ?></p></div>
                            </div>
                            <div class="my-5 flex gap-1 text-wine" aria-label="Cinco estrelas"><?php for ($star = 0; $star < 5; $star++): ?><i class="ph-fill ph-star text-lg" aria-hidden="true"></i><?php endfor; ?></div>
                            <p class="text-sm leading-7 text-ink/68">“<?php echo e($review['quote']); ?>”</p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section id="duvidas" class="bg-bordoDeep py-20 text-cream md:py-28">
            <div class="mx-auto grid max-w-7xl gap-12 px-5 lg:grid-cols-[.76fr_1.24fr] lg:gap-20 lg:px-8">
                <aside class="reveal lg:sticky lg:top-28 lg:self-start"><p class="mb-4 text-[11px] font-bold uppercase tracking-[0.2em] text-sand">Dúvidas frequentes</p><h2 class="font-serif text-4xl leading-tight md:text-6xl">Informação para decidir com mais segurança.</h2><p class="mt-6 max-w-sm text-sm leading-7 text-cream/62">As respostas abaixo são gerais. A orientação adequada depende da análise individual da sua situação.</p></aside>
                <div class="reveal">
                    <?php foreach ($faqs as $index => $faq): ?>
                        <details class="border-b border-cream/10 py-5 first:border-t" data-faq>
                            <summary class="grid cursor-pointer list-none grid-cols-[1fr_28px] items-center gap-5 outline-none"><span class="font-serif text-xl leading-tight md:text-2xl"><span class="mr-2 text-base text-cream/70"><?php echo str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT); ?>.</span><?php echo e($faq[0]); ?></span><span class="faq-icon grid h-7 w-7 place-items-center"><?php echo ph_icon('caret-down', 'text-xl'); ?></span></summary>
                            <div class="faq-content"><div class="faq-content-inner"><p class="max-w-2xl pt-4 text-sm leading-7 text-cream/62"><?php echo e($faq[1]); ?></p></div></div>
                        </details>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section id="contato" class="bg-bordo py-20 text-cream md:py-28">
            <div class="mx-auto grid max-w-7xl gap-12 px-5 lg:grid-cols-[.9fr_1.1fr] lg:items-start lg:px-8">
                <div class="reveal">
                    <p class="mb-4 text-[11px] font-bold uppercase tracking-[0.2em] text-sand">Converse com nossa equipe</p>
                    <h2 class="font-serif text-4xl leading-tight md:text-6xl">Você não precisa organizar tudo sozinho.</h2>
                    <p class="mt-6 max-w-xl text-base leading-8 text-cream/72">Conte brevemente o que aconteceu. Nossa equipe fará uma escuta inicial para compreender sua situação, identificar os documentos disponíveis e orientar a forma adequada de atendimento.</p>
                    <div class="mt-9 grid gap-4 text-sm text-cream/75">
                        <a href="<?php echo e($whatsapp_link); ?>" target="_blank" rel="noopener" class="flex items-center gap-3"><?php echo ph_icon('whatsapp-logo', 'text-xl text-sand'); ?> <?php echo e($whatsapp_friendly); ?></a>
                        <a href="mailto:<?php echo e($email_contato); ?>" class="flex items-center gap-3"><?php echo ph_icon('envelope', 'text-xl text-sand'); ?> <?php echo e($email_contato); ?></a>
                        <p class="flex items-center gap-3"><?php echo ph_icon('map-pin', 'text-xl text-sand'); ?> <?php echo e($endereco_local); ?></p>
                    </div>
                </div>
                <form id="lead-form" class="soft-radius border border-wine/12 bg-white p-7 text-wineDark shadow-[0_24px_70px_rgba(38,3,5,.2)] reveal">
                    <?php echo csrf_field(); ?><input type="text" name="website" id="form-website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
                    <h3 class="font-serif text-3xl">Conte o que aconteceu</h3>
                    <p class="mt-2 text-xs leading-6 text-ink/60">Preencha os dados para iniciarmos uma conversa. O envio não cria contratação ou garantia de resultado.</p>
                    <div class="mt-6 grid gap-4">
                        <div><label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.16em] text-wine/60">Nome completo</label><input id="form-name" type="text" required class="soft-radius w-full border border-wine/15 bg-paper/65 px-4 py-3 text-sm outline-none focus:border-wine/45" placeholder="Seu nome"></div>
                        <div><label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.16em] text-wine/60">WhatsApp</label><input id="form-phone" type="tel" required class="soft-radius w-full border border-wine/15 bg-paper/65 px-4 py-3 text-sm outline-none focus:border-wine/45" placeholder="(00) 00000-0000"></div>
                        <div><label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.16em] text-wine/60">Qual situação mais se aproxima do seu caso?</label><select id="form-area" class="soft-radius w-full border border-wine/15 bg-paper/65 px-4 py-3 text-sm outline-none focus:border-wine/45"><?php foreach ($areas as $area): ?><option><?php echo e($area); ?></option><?php endforeach; ?></select></div>
                        <div><label class="mb-2 block text-[10px] font-bold uppercase tracking-[0.16em] text-wine/60">Relato breve</label><textarea id="form-message" rows="5" class="soft-radius w-full border border-wine/15 bg-paper/65 px-4 py-3 text-sm outline-none focus:border-wine/45" placeholder="Quando os sintomas ou o acidente começaram? Como isso afetou sua rotina?"></textarea></div>
                        <button type="submit" class="soft-radius border border-wine bg-wine px-6 py-4 text-xs font-bold uppercase tracking-[0.16em] text-cream transition hover:bg-wineDark">Enviar solicitação de atendimento</button>
                        <p id="form-status" class="hidden text-sm leading-relaxed"></p>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <a href="<?php echo e($whatsapp_link); ?>" target="_blank" rel="noopener" aria-label="Conversar no WhatsApp" class="fixed bottom-5 right-5 z-50 inline-flex h-14 items-center justify-center gap-3 rounded-[10px] border border-white bg-white px-4 text-wineDark shadow-[0_18px_48px_rgba(38,3,5,.24)] transition hover:bg-paper sm:px-5"><?php echo ph_icon('whatsapp-logo', 'text-2xl'); ?><span class="hidden text-[11px] font-bold uppercase tracking-[0.16em] sm:inline">Conversar</span></a>

    <footer class="bg-bordoDeep py-12 text-cream">
        <div class="mx-auto grid max-w-7xl gap-10 px-5 md:grid-cols-3 lg:px-8">
            <div class="reveal"><p class="site-logo-name">Gabriela Pita</p><p class="site-logo-subtitle text-cream/55">Advogados Associados</p><p class="mt-5 text-xs text-cream/60"><?php echo e($oab_registro); ?></p></div>
            <nav class="grid gap-2 text-xs uppercase tracking-[0.16em] text-cream/60 reveal"><a href="#situacoes" class="hover:text-cream">Situações atendidas</a><a href="#direitos" class="hover:text-cream">Entenda seus direitos</a><a href="#duvidas" class="hover:text-cream">Dúvidas</a></nav>
            <div class="grid content-start gap-3 text-sm text-cream/70 reveal"><a href="<?php echo e($whatsapp_link); ?>" target="_blank" rel="noopener">WhatsApp: <?php echo e($whatsapp_friendly); ?></a><a href="mailto:<?php echo e($email_contato); ?>"><?php echo e($email_contato); ?></a><p><?php echo e($endereco_local); ?></p></div>
        </div>
        <div class="mx-auto mt-10 max-w-7xl px-5 text-xs text-cream/45 lg:px-8 reveal">© <?php echo date('Y'); ?> <?php echo e($nome_escritorio); ?>. Todos os direitos reservados.</div>
    </footer>

<?php render_front_header_script(); ?>
    <script>
        document.querySelectorAll('[data-faq]').forEach((item) => item.addEventListener('toggle', () => {
            if (!item.open) return;
            document.querySelectorAll('[data-faq]').forEach((other) => { if (other !== item) other.open = false; });
        }));
        const phoneInput = document.getElementById('form-phone');
        phoneInput?.addEventListener('input', (event) => {
            let value = event.target.value.replace(/\D/g, '').slice(0, 11);
            if (value.length > 2) value = value.replace(/^(\d{2})(\d)/, '($1) $2');
            if (value.length > 7) value = value.replace(/(\d)(\d{4})$/, '$1-$2');
            event.target.value = value;
        });
        const leadForm = document.getElementById('lead-form');
        const formStatus = document.getElementById('form-status');
        leadForm?.addEventListener('submit', async (event) => {
            event.preventDefault();
            const button = leadForm.querySelector('button[type="submit"]');
            const payload = new FormData();
            ['name','phone','area','message','website'].forEach((field) => payload.append(field, document.getElementById('form-' + field).value));
            payload.append('csrf_token', leadForm.querySelector('input[name="csrf_token"]')?.value || '');
            button.disabled = true; button.textContent = 'Enviando...'; formStatus.className = 'text-sm text-ink/60'; formStatus.textContent = 'Enviando sua solicitação.';
            try {
                const response = await fetch('contact-submit.php', { method:'POST', body:payload });
                const result = await response.json();
                if (!response.ok || !result.ok) throw new Error(result.message || 'Erro ao enviar.');
                formStatus.className = 'text-sm text-wine'; formStatus.textContent = result.message; leadForm.reset();
            } catch (error) {
                formStatus.className = 'text-sm text-red-700'; formStatus.textContent = error.message || 'Não foi possível enviar agora.';
            } finally { button.disabled = false; button.textContent = 'Enviar solicitação de atendimento'; }
        });
        const revealItems = Array.prototype.slice.call(document.querySelectorAll('.reveal'));
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => entries.forEach((entry) => { if (entry.isIntersecting) { entry.target.classList.add('active'); observer.unobserve(entry.target); } }), { threshold:.12 });
            revealItems.forEach((item) => observer.observe(item));
        } else { revealItems.forEach((item) => item.classList.add('active')); }
    </script>
</body>
</html>
