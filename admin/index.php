<?php require '_auth.php'; ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard – Admin Pé de Manga</title>
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta name="description"
    content="O Pé de Manga e um Ponto de Cultura dedicado a arte como caminho de cuidado, pertencimento e transformação social.">
  <meta name="keywords"
    content="Pé de Manga, Cultura Viva, Ponto de Cultura, arte e cultura, saúde mental, sustentabilidade, responsabilidade social, oficinas culturais, vivências artísticas, impacto comunitário, coletivo cultural, transformação social, cultura acessível, arte como cuidado">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
  <div class="admin-layout">
    <?php require '_sidebar.php'; ?>
    <div class="admin-main">
      <div class="admin-topbar">
        <h3>&#127968; Dashboard</h3>
        <div class="topbar-actions">
          <span class="topbar-user">Olá, <?= htmlspecialchars($_SESSION['adm_user']) ?></span>
          <a href="../index.php" target="_blank" class="btn-adm btn-adm-outline">Ver site</a>
          <a href="logout.php" class="btn-adm btn-adm-danger">Sair</a>
        </div>
      </div>
      <div class="admin-content">
        <?php
        $db = get_db();
        $n_colabs    = (int) $db->query('SELECT COUNT(*) FROM colaboradores')->fetchColumn();
        $n_parceiros = (int) $db->query('SELECT COUNT(*) FROM parceiros')->fetchColumn();
        $n_galeria   = (int) $db->query('SELECT COUNT(*) FROM galeria')->fetchColumn();
        ?>
        <div class="stats-grid">
          <div class="stat-card sc-amarelo">
            <div class="sc-icon">&#128101;</div>
            <div class="sc-num"><?= $n_colabs ?></div>
            <div class="sc-label">Colaboradores</div>
          </div>
          <div class="stat-card sc-verde">
            <div class="sc-icon">&#129309;</div>
            <div class="sc-num"><?= $n_parceiros ?></div>
            <div class="sc-label">Parceiros</div>
          </div>
          <div class="stat-card sc-marrom">
            <div class="sc-icon">&#128247;</div>
            <div class="sc-num"><?= $n_galeria ?></div>
            <div class="sc-label">Fotos na Galeria</div>
          </div>
          <div class="stat-card sc-coral">
            <div class="sc-icon">&#127758;</div>
            <div class="sc-num">9</div>
            <div class="sc-label">Paginas do Site</div>
          </div>
        </div>

        <div class="admin-card">
          <div class="admin-card-header">
            <h4>&#9881; Acesso rápido</h4>
          </div>
          <div class="admin-card-body" style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
            <a href="colaboradores.php" class="btn-adm btn-adm-primary"
              style="padding:14px;text-align:center;justify-content:center;">&#128101; Gerenciar Colaboradores</a>
            <a href="parceiros.php" class="btn-adm btn-adm-verde"
              style="padding:14px;text-align:center;justify-content:center;">&#129309; Gerenciar Parceiros</a>
            <a href="galeria.php" class="btn-adm btn-adm-outline"
              style="padding:14px;text-align:center;justify-content:center;">&#128247; Gerenciar Galeria</a>
          </div>
        </div>

        <div class="admin-card">
          <div class="admin-card-header">
            <h4>&#128203; Informações de acesso</h4>
          </div>
          <div class="admin-card-body">
            <p style="font-size:.86rem;color:var(--marrom);line-height:1.7;margin-bottom:12px;">
              <strong>Usuário:</strong> admin &nbsp;|&nbsp;
              <strong>Senha padrão:</strong> pedemanga2025<br>
              Recomendamos alterar a senha no arquivo <code>admin/login.php</code> usando
              <code>password_hash('nova_senha', PASSWORD_DEFAULT)</code>.
            </p>
            <p style="font-size:.82rem;color:rgba(136,105,46,.6);">Os dados (colaboradores, parceiros, galeria) são salvos no banco de dados MySQL. As imagens ficam em <code>data/uploads/</code>. Configure a conexão em <code>includes/db.php</code>.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.getElementById('adminSidebar');
  </script>
</body>

</html>