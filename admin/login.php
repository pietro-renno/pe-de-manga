<?php
session_start();
define('ADM_USER', 'admin');
define('ADM_PASS', '$2y$10$dg02UFPZhM/8HEit7pvFe.7/RbHpz1Q6wOpuq..HB84SXYQiCEdDS'); // senha: pedemanga2025

if (isset($_SESSION['adm_logado']) && $_SESSION['adm_logado']) {
  header('Location: index.php');
  exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = trim($_POST['usuario'] ?? '');
  $senha = trim($_POST['senha'] ?? '');
  if ($user === ADM_USER && password_verify($senha, ADM_PASS)) {
    $_SESSION['adm_logado'] = true;
    $_SESSION['adm_user'] = $user;
    header('Location: index.php');
    exit;
  } else {
    $erro = 'Usuario ou senha incorretos.';
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta name="description"
    content="O Pé de Manga e um Ponto de Cultura dedicado a arte como caminho de cuidado, pertencimento e transformação social.">
  <meta name="keywords"
    content="Pé de Manga, Cultura Viva, Ponto de Cultura, arte e cultura, saúde mental, sustentabilidade, responsabilidade social, oficinas culturais, vivências artísticas, impacto comunitário, coletivo cultural, transformação social, cultura acessível, arte como cuidado">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>Login – Painel Pé de Manga</title>
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
  <div class="login-page">
    <div class="login-card">
      <div class="login-logo">
        <div class="ll-mango">&#x1F96D;</div>
        <h1>Pé de Manga</h1>
        <p>Painel Administrativo</p>
      </div>
      <p class="login-sub">Entre com suas credenciais para acessar o painel.</p>
      <?php if ($erro): ?>
        <div class="alert alert-erro"><?php echo htmlspecialchars($erro); ?></div>
      <?php endif; ?>
      <form method="POST">
        <div class="form-group">
          <label for="usuario">E-mail</label>
          <input type="text" id="usuario" name="usuario" required autocomplete="username" placeholder="admin">
        </div>
        <div class="form-group">
          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" required autocomplete="current-password"
            placeholder="••••••••">
        </div>
        <button type="submit" class="btn-login">Entrar no painel</button>
      </form>
      <p style="margin-top:20px;text-align:center;font-size:.76rem;color:rgba(136,105,46,.5);">
        Senha padrao: <code>pedemanga2025</code> — altere após o primeiro acesso.
      </p>
    </div>
  </div>
</body>

</html>