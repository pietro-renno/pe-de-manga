<?php
session_start();

if (!empty($_SESSION['adm_logado'])) {
  header('Location: index.php');
  exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_once __DIR__ . '/../includes/db.php';
  $email = trim($_POST['email'] ?? '');
  $senha = trim($_POST['senha'] ?? '');

  if ($email !== '' && $senha !== '') {
    $db   = get_db();
    $stmt = $db->prepare('SELECT id, nome, senha, perfil FROM usuarios WHERE email = ? AND ativo = 1 LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
      $_SESSION['adm_logado'] = true;
      $_SESSION['adm_id']     = $user['id'];
      $_SESSION['adm_user']   = $user['nome'];
      $_SESSION['adm_perfil'] = $user['perfil'];
      header('Location: index.php');
      exit;
    }
  }
  $erro = 'E-mail ou senha incorretos.';
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
        <div class="alert alert-erro"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>
      <form method="POST">
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" required autocomplete="username"
            placeholder="seu@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" required autocomplete="current-password"
            placeholder="••••••••">
        </div>
        <button type="submit" class="btn-login">Entrar no painel</button>
      </form>
    </div>
  </div>
</body>

</html>
