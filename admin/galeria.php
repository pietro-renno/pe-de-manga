<?php
require '_auth.php';

$msg = '';
$db = get_db();

// UPLOAD de multiplas imagens
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'upload') {
  $files = $_FILES['fotos'];
  $desc = trim($_POST['descricao'] ?? '');
  $adicionadas = 0;
  if (is_array($files['name'])) {
    $st = $db->prepare('INSERT INTO galeria (arquivo, descricao) VALUES (?, ?)');
    foreach ($files['name'] as $i => $fname) {
      if ($files['error'][$i] !== 0)
        continue;
      $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
      if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
        continue;
      if ($files['size'][$i] > 5 * 1024 * 1024)
        continue;
      $nome = 'galeria_' . uniqid() . '.' . $ext;
      $dest = __DIR__ . '/../data/uploads/' . $nome;
      if (move_uploaded_file($files['tmp_name'][$i], $dest)) {
        $st->execute([$nome, $desc ?: null]);
        $adicionadas++;
      }
    }
  }
  $msg = "sucesso:{$adicionadas} foto(s) adicionada(s)!";
}

// REMOVER foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'remover') {
  $id = (int) ($_POST['id'] ?? 0);
  $foto = $db->prepare('SELECT arquivo FROM galeria WHERE id=?');
  $foto->execute([$id]);
  $row = $foto->fetch();
  if ($row) {
    $path = __DIR__ . '/../data/uploads/' . $row['arquivo'];
    if (file_exists($path))
      unlink($path);
  }
  $db->prepare('DELETE FROM galeria WHERE id=?')->execute([$id]);
  $msg = 'sucesso:Foto removida.';
}

$galeria = get_galeria();
[$tipo_msg, $texto_msg] = $msg ? explode(':', $msg, 2) : ['', ''];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Galeria – Admin Pé de Manga</title>
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <meta name="description"
    content="O Pé de Manga e um Ponto de Cultura dedicado a arte como caminho de cuidado, pertencimento e transformação social.">
  <meta name="keywords"
    content="Pé de Manga, Cultura Viva, Ponto de Cultura, arte e cultura, saúde mental, sustentabilidade, responsabilidade social, oficinas culturais, vivências artísticas, impacto comunitário, coletivo cultural, transformação social, cultura acessível, arte como cuidado">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
</head>

<body>
  <div class="admin-layout">
    <?php require '_sidebar.php'; ?>
    <div class="admin-main">
      <div class="admin-topbar">
        <h3><span class="material-symbols-outlined pi-icon">photo_camera</span> Galeria de Fotos</h3>
        <h3><span class="material-symbols-outlined">photo_camera</span> Galeria de Fotos</h3>
        <div class="topbar-actions">
          <button class="btn-adm btn-adm-primary" onclick="openModal('upload')"><span
              class="material-symbols-outlined">add</span> Adicionar fotos</button>
          <a href="logout.php" class="btn-adm btn-adm-danger">Sair</a>
        </div>
      </div>
      <div class="admin-content">
        <?php if ($texto_msg): ?>
          <div class="alert alert-<?= $tipo_msg ?>" style="margin-bottom:20px;"><?= htmlspecialchars($texto_msg) ?></div>
        <?php endif; ?>

        <div class="admin-card">
          <div class="admin-card-header">
            <h4>Fotos na galeria (<?= count($galeria) ?>)</h4>
            <span style="font-size:.78rem;color:var(--marrom);opacity:.6;">Clique para remover</span>
          </div>
          <div class="admin-card-body">
            <?php if (empty($galeria)): ?>
              <p style="text-align:center;padding:50px;color:var(--marrom);opacity:.6;">Nenhuma foto na galeria ainda.
                Adicione a primeira!</p>
            <?php else: ?>
              <div class="galeria-adm-grid">
                <?php foreach ($galeria as $foto): ?>
                  <div class="gal-thumb">
                    <img src="../data/uploads/<?= htmlspecialchars($foto['arquivo']) ?>"
                      alt="<?= htmlspecialchars($foto['descricao'] ?? '') ?>">
                    <div class="gal-overlay">
                      <form method="POST" onsubmit="return confirm('Remover esta foto?')" style="display:contents;">
                        <input type="hidden" name="acao" value="remover">
                        <input type="hidden" name="id" value="<?= (int) $foto['id'] ?>">
                        <button type="submit" title="Remover"><span class="material-symbols-outlined">delete</span></button>
                      </form>
                    </div>
                    <?php if (!empty($foto['descricao'])): ?>
                      <div
                        style="position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.5);color:#fff;font-size:.65rem;padding:4px 6px;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;">
                        <?= htmlspecialchars($foto['descricao']) ?>
                      </div>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Upload -->
  <div class="modal-overlay" id="modal-upload" onclick="closeModal('upload')">
    <div class="modal-box" onclick="event.stopPropagation()" style="max-width:580px;">
      <button class="modal-close" onclick="closeModal('upload')">&times;</button>
      <h3>Adicionar Fotos</h3>
      <p class="modal-sub">Selecione uma ou várias imagens para adicionar à galeria</p>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="acao" value="upload">
        <div class="form-group">
          <label>Descrição (opcional)</label>
          <input type="text" name="descricao" placeholder="ex: Oficina de artesanato - marco 2025">
          <label>Descrição (opcional)</label>
          <input type="text" name="descricao" placeholder="ex: Oficina de artesanato - março 2025">
        </div>
        <div class="form-group">
          <label>Imagens *</label>
          <div class="upload-area" id="uploadArea" onclick="document.getElementById('fotosInput').click()">
            <div class="ua-icon"><span class="material-symbols-outlined mi-xl">upload</span></div>
            <p>Clique para selecionar ou arraste as imagens aqui</p>
            <small>JPG, PNG, WEBP – max 5MB por arquivo</small>
          </div>
          <input type="file" id="fotosInput" name="fotos[]" multiple accept="image/*" style="display:none"
            onchange="previewImages(this)">
          <div class="upload-preview" id="uploadPreview"></div>
        </div>
        <div class="form-actions">
          <button type="button" class="btn-adm btn-adm-outline" onclick="closeModal('upload')">Cancelar</button>
          <button type="submit" class="btn-adm btn-adm-primary">Enviar fotos</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function openModal(id) { document.getElementById('modal-' + id).classList.add('open'); }
    function closeModal(id) { document.getElementById('modal-' + id).classList.remove('open'); }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open')); });

    function previewImages(input) {
      const preview = document.getElementById('uploadPreview');
      preview.innerHTML = '';
      Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
          const div = document.createElement('div');
          div.className = 'up-thumb';
          div.innerHTML = '<img src="' + e.target.result + '" alt="">';
          preview.appendChild(div);
        };
        reader.readAsDataURL(file);
      });
    }

    const area = document.getElementById('uploadArea');
    const inp = document.getElementById('fotosInput');
    if (area && inp) {
      area.addEventListener('dragover', e => { e.preventDefault(); area.classList.add('drag'); });
      area.addEventListener('dragleave', () => area.classList.remove('drag'));
      area.addEventListener('drop', e => { e.preventDefault(); area.classList.remove('drag'); inp.files = e.dataTransfer.files; previewImages(inp); });
    }
  </script>
</body>

</html>