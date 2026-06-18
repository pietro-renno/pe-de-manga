<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta name="description"
    content="O Pé de Manga é um Ponto de Cultura dedicado à arte como caminho de cuidado, pertencimento e transformação social.">
  <meta name="keywords"
    content="Pé de Manga, Cultura Viva, Ponto de Cultura, arte e cultura, saúde mental, sustentabilidade, responsabilidade social, oficinas culturais, vivências artísticas, impacto comunitário, coletivo cultural, transformação social, cultura acessível, arte como cuidado">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>Doações - Pé de Manga</title>
  <link rel="stylesheet" href="./assets/css/style.css?v=3">
  <link
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    rel="stylesheet" />
</head>

<body>
  <?php require 'includes/nav.php'; ?>
  <div class="page-wrap">
    <div class="page-hero">
      <p class="ph-tag">Faça parte</p>
      <h1>Apoie o Pé de <em>Manga</em></h1>
      <p>Cada doação transforma cultura em cuidado e cuidado em comunidade.</p>
    </div>
    <section style="background:var(--fundo-claro);">
      <div class="section-inner">
        <div class="doacoes-grid">
          <div class="doacoes-why">
            <p class="reveal">Sua contribuição fortalece diretamente as ações culturais gratuitas que realizamos para a
              comunidade. A cada atividade remunerada, oferecemos uma atividade gratuita &#x2014; e isso só é possível
              com o apoio de pessoas que acreditam na cultura como direito.</p>
            <p class="reveal">Toda doação de qualquer valor é transformada em arte, cuidado e pertencimento.</p>
            <blockquote class="quote-block reveal" style="margin-top:28px;">
              "O que importa, realmente, ao ajudar-se o homem é ajudá-lo a ajudar-se."
              <cite>Paulo Freire</cite>
            </blockquote>
            <div
              style="margin-top:30px;padding:22px;background:var(--branco);border-radius:12px;border-left:4px solid var(--verde);"
              class="reveal">
              <h4
                style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--marrom-escuro);margin-bottom:8px;">
                Impacto da sua doação</h4>
              <ul style="list-style:none;display:flex;flex-direction:column;gap:8px;">
                <li style="display:flex;align-items: center;font-size:.85rem;color:var(--marrom-escuro);"><span
                    style="margin-right:4px;" class="material-symbols-outlined">construction</span> Manutenção do espaço
                  e jardim</li>
                <li style="display:flex;align-items: center;font-size:.85rem;color:var(--marrom-escuro);"><span
                    style="margin-right:4px;" class="material-symbols-outlined">comedy_mask</span> Materiais para
                  oficinas gratuitas</li>
                <li style="display:flex;align-items: center;font-size:.85rem;color:var(--marrom-escuro);"><span
                    style="margin-right:4px;" class="material-symbols-outlined">school</span> Atividades para grupos
                  escolares</li>
                <li style="display:flex;align-items: center;font-size:.85rem;color:var(--marrom-escuro);"><span
                    style="margin-right:4px;" class="material-symbols-outlined">sweep</span> Fortalecimento do Ponto de
                  Cultura</li>
              </ul>
            </div>
          </div>
          <div class="pix-card reveal">
            <h3>Doe agora</h3>
            <p class="pix-sub">Escolha a forma de pagamento que preferir</p>
            <div class="pix-method">
              <p class="pm-label">&#10022; Chave Pix (e-mail)</p>
              <p class="pm-value">financasdopedemanga@gmail.com</p>
              <button class="pix-copy" onclick="copyText('financasdopedemanga@gmail.com')"><span
                  class="material-symbols-outlined">content_copy</span> Copiar chave</button>
            </div>
            <div class="pix-divider">&#x2014; ou &#x2014;</div>
            <div class="pix-method">
              <p class="pm-label"><span class="material-symbols-outlined">account_balance</span> Transferência bancária
              </p>
              <p class="pm-value">Banco do Brasil (001)</p>
              <p class="pm-sub">Ag 1683-7 &nbsp;|&nbsp; CC 224087-4</p>
              <p class="pm-sub">CNPJ: 23.456.588/0001-98</p>
              <p class="pm-sub">Pé de Manga &amp; Amora Produções &#x2013; Talita S Domingos</p>
            </div>
            <p style="font-size:.75rem;color:var(--marrom-escuro);margin-top:16px;line-height:1.6;">Sua doação é
              destinada
              inteiramente às ações do coletivo. <br> Veja <a href="transparencia.php"
                style="color:var(--verde);font-weight:600;">Transparência</a>.</p>
          </div>
        </div>
      </div>
    </section>
    <section class="container">
      <div class="mvv-grid">
        <div class="mvv-card" data-reveal="up">
          <i class="fas fa-seedling" style="font-size: 2rem; color: var(--verde);"></i>
          <h3>Mutirão Semanal</h3>
          <p>Toda semana abrimos nossas portas para cuidados coletivos com o jardim e o espaço. Sua mão de obra é
            uma doação de afeto!</p>
        </div>
        <div class="mvv-card" data-reveal="up" data-delay="100">
          <i class="fas fa-box-open" style="font-size: 2rem; color: var(--verde);"></i>
          <h3>Materiais</h3>
          <p>Aceitamos doação de materiais artísticos, tintas, papelaria e ferramentas de jardinagem em bom estado.
          </p>
        </div>
        <div class="mvv-card" data-reveal="up" data-delay="200">
          <i class="fas fa-share-alt" style="font-size: 2rem; color: var(--verde);"></i>
          <h3>Divulgação</h3>
          <p>Compartilhar nosso trabalho nas redes sociais ajuda a alcançar novos parceiros e expandir o impacto do
            Ponto de Cultura.</p>
        </div>
      </div>
    </section>
  </div>
  <?php require 'includes/footer.php'; ?>
</body>

</html>