<?php
require '_auth.php';

// Somente admin pode acessar esta página
if ($_SESSION['adm_perfil'] !== 'admin') {
  header('Location: index.php');
  exit;
}

$db = get_db();
$msg = '';
$tipo_msg = '';

// ── CRIAR ──────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'criar') {
  $nome = trim($_POST['nome'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $senha = trim($_POST['senha'] ?? '');
  $perfil = in_array($_POST['perfil'] ?? '', ['admin', 'editor']) ? $_POST['perfil'] : 'editor';

  if ($nome === '' || $email === '' || $senha === '') {
    $msg = 'Preencha todos os campos obrigatórios.';
    $tipo_msg = 'erro';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $msg = 'E-mail inválido.';
    $tipo_msg = 'erro';
  } elseif (strlen($senha) < 6) {
    $msg = 'A senha deve ter pelo menos 6 caracteres.';
    $tipo_msg = 'erro';
  } else {
    $chk = $db->prepare('SELECT id FROM usuarios WHERE email = ? LIMIT 1');
    $chk->execute([$email]);
    if ($chk->fetch()) {
      $msg = 'Já existe um usuário com este e-mail.';
      $tipo_msg = 'erro';
    } else {
      $hash = password_hash($senha, PASSWORD_DEFAULT);
      $ins = $db->prepare('INSERT INTO usuarios (nome, email, senha, perfil) VALUES (?, ?, ?, ?)');
      $ins->execute([$nome, $email, $hash, $perfil]);
      $msg = 'Usuário criado com sucesso.';
      $tipo_msg = 'sucesso';
    }
  }
}

// ── ATIVAR / DESATIVAR ──────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'toggle_ativo') {
  $id = (int) ($_POST['id'] ?? 0);
  // Impede desativar a si mesmo
  if ($id > 0 && $id !== (int) $_SESSION['adm_id']) {
    $db->prepare('UPDATE usuarios SET ativo = NOT ativo WHERE id = ?')->execute([$id]);
  }
  header('Location: usuarios.php');
  exit;
}

// ── EXCLUIR ─────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'excluir') {
  $id = (int) ($_POST['id'] ?? 0);
  if ($id > 0 && $id !== (int) $_SESSION['adm_id']) {
    $db->prepare('DELETE FROM usuarios WHERE id = ?')->execute([$id]);
  }
  header('Location: usuarios.php');
  exit;
}

// ── REDEFINIR SENHA ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'reset_senha') {
  $id = (int) ($_POST['id'] ?? 0);
  $nova_senha = trim($_POST['nova_senha'] ?? '');
  if ($id > 0 && strlen($nova_senha) >= 6) {
    $hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    $db->prepare('UPDATE usuarios SET senha = ? WHERE id = ?')->execute([$hash, $id]);
    $msg = 'Senha redefinida com sucesso.';
    $tipo_msg = 'sucesso';
  } else {
    $msg = 'A nova senha deve ter pelo menos 6 caracteres.';
    $tipo_msg = 'erro';
  }
}

// ── LISTAR ───────────────────────────────────────────────────────────────────
$usuarios = $db->query('SELECT id, nome, email, perfil, ativo, criado_em FROM usuarios ORDER BY criado_em DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuários – Admin Pé de Manga</title>
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
</head>

<body>
  <div class="admin-layout">
    <?php require '_sidebar.php'; ?>
    <div class="admin-main">
      <div class="admin-topbar">
        <h3><span class="material-symbols-outlined">manage_accounts</span> Usuários</h3>
        <h3><span class="material-symbols-outlined pi-icon">person</span> Usuários</h3>
        <div class="topbar-actions">
          <span class="topbar-user">Olá, <?= htmlspecialchars($_SESSION['adm_user']) ?></span>
          <button class="btn-adm btn-adm-primary" onclick="abrirModal('modalCriar')"><span
              class="material-symbols-outlined">add</span> Novo usuário</button>
          <a href="logout.php" class="btn-adm btn-adm-danger">Sair</a>
        </div>
      </div>

      <div class="admin-content">
        <?php if ($msg): ?>
          <div class="alert alert-<?= $tipo_msg === 'sucesso' ? 'sucesso' : 'erro' ?>"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <div class="admin-card">
          <div class="admin-card-header">
            <h4><span class="material-symbols-outlined">manage_accounts</span> Usuários cadastrados
              (<?= count($usuarios) ?>)</h4>
            <button class="btn-adm btn-adm-primary" onclick="abrirModal('modalCriar')"><span
                class="material-symbols-outlined">add</span> Novo usuário</button>
          </div>
          <div class="admin-card-body" style="padding:0;">
            <table>
              <thead>
                <tr>
                  <th class="th-hide-mobile">#</th>
                  <th>Nome</th>
                  <th>E-mail</th>
                  <th>Perfil</th>
                  <th>Status</th>
                  <th class="th-hide-mobile">Criado em</th>
                  <th>Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($usuarios as $u): ?>
                  <tr>
                    <td class="td-hide-mobile"><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['nome']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                      <span class="badge <?= $u['perfil'] === 'admin' ? 'badge-amarelo' : 'badge-verde' ?>">
                        <?= $u['perfil'] ?>
                      </span>
                    </td>
                    <td>
                      <span class="badge <?= $u['ativo'] ? 'badge-verde' : '' ?>"
                        style="<?= !$u['ativo'] ? 'background:#fde8e8;color:#c0392b;' : '' ?>">
                        <?= $u['ativo'] ? 'Ativo' : 'Inativo' ?>
                      </span>
                    </td>
                    <td class="td-hide-mobile"><?= date('d/m/Y', strtotime($u['criado_em'])) ?></td>
                    <td>
                      <div class="td-actions">
                        <!-- Redefinir senha -->
                        <button class="btn-adm btn-adm-outline"
                          onclick="abrirModalSenha(<?= $u['id'] ?>, '<?= htmlspecialchars(addslashes($u['nome'])) ?>')"
                          title="Redefinir senha"><span class="material-symbols-outlined">lock_reset</span></button>
                        <?php if ((int) $u['id'] !== (int) $_SESSION['adm_id']): ?>
                          <!-- Ativar / Desativar -->
                          <form method="POST" style="display:inline;">
                            <input type="hidden" name="acao" value="toggle_ativo">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <button type="submit" class="btn-adm <?= $u['ativo'] ? 'btn-adm-outline' : 'btn-adm-verde' ?>"
                              title="<?= $u['ativo'] ? 'Desativar' : 'Ativar' ?>">
                              <span class="material-symbols-outlined"><?= $u['ativo'] ? 'block' : 'check_circle' ?></span>
                            </button>
                          </form>
                          <!-- Excluir -->
                          <form method="POST" style="display:inline;"
                            onsubmit="return confirm('Excluir o usuário <?= htmlspecialchars(addslashes($u['nome'])) ?>?')">
                            <input type="hidden" name="acao" value="excluir">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <button type="submit" class="btn-adm btn-adm-danger" title="Excluir"><span
                                class="material-symbols-outlined">delete</span></button>
                          </form>
                        <?php else: ?>
                          <span style="font-size:.72rem;color:rgba(136,105,46,.4);">(você)</span>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: Criar usuário -->
  <div class="modal-overlay" id="modalCriar">
    <div class="modal-box">
      <button class="modal-close" onclick="fecharModal('modalCriar')">&times;</button>
      <h3>Novo usuário</h3>
      <p class="modal-sub">Preencha os dados do novo usuário. O login será feito por e-mail e senha.</p>
      <form method="POST">
        <input type="hidden" name="acao" value="criar">
        <div class="form-group">
          <label>Nome completo *</label>
          <input type="text" name="nome" required placeholder="Ex: Maria Silva">
        </div>
        <div class="form-group">
          <label>E-mail *</label>
          <input type="email" name="email" required placeholder="maria@exemplo.com">
        </div>
        <div class="form-group">
          <label>Senha *</label>
          <input type="password" name="senha" required placeholder="Mínimo 6 caracteres" minlength="6">
        </div>
        <div class="form-group">
          <label>Perfil</label>
          <select name="perfil">
            <option value="editor">Editor — acesso ao painel</option>
            <option value="admin">Admin — gerencia usuários</option>
          </select>
        </div>
        <div class="form-actions">
          <button type="button" class="btn-adm btn-adm-outline" onclick="fecharModal('modalCriar')">Cancelar</button>
          <button type="submit" class="btn-adm btn-adm-primary">Criar usuário</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal: Redefinir senha -->
  <div class="modal-overlay" id="modalSenha">
    <div class="modal-box">
      <button class="modal-close" onclick="fecharModal('modalSenha')">&times;</button>
      <h3>Redefinir senha</h3>
      <p class="modal-sub" id="modalSenhaSub"></p>
      <form method="POST">
        <input type="hidden" name="acao" value="reset_senha">
        <input type="hidden" name="id" id="modalSenhaId">
        <div class="form-group">
          <label>Nova senha *</label>
          <input type="password" name="nova_senha" required placeholder="Mínimo 6 caracteres" minlength="6">
        </div>
        <div class="form-actions">
          <button type="button" class="btn-adm btn-adm-outline" onclick="fecharModal('modalSenha')">Cancelar</button>
          <button type="submit" class="btn-adm btn-adm-primary">Salvar senha</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function abrirModal(id) {
      document.getElementById(id).classList.add('open');
    }
    function fecharModal(id) {
      document.getElementById(id).classList.remove('open');
    }
    function abrirModalSenha(id, nome) {
      document.getElementById('modalSenhaId').value = id;
      document.getElementById('modalSenhaSub').textContent = 'Redefinindo senha de: ' + nome;
      abrirModal('modalSenha');
    }
    // Fechar modal ao clicar fora
    document.querySelectorAll('.modal-overlay').forEach(function (el) {
      el.addEventListener('click', function (e) {
        if (e.target === el) el.classList.remove('open');
      });
    });
    <?php if ($msg && $tipo_msg === 'sucesso'): ?>
      // Reabre modal de criação em caso de erro (não fecha se sucesso)
    <?php endif; ?>
  </script>
</body>

</html>