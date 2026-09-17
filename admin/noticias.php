<?php
// admin/noticias.php - Manage News & Blog Posts
include __DIR__ . '/header.php';

$msg = '';
$err = '';

// Delete handler
if (isset($_GET['del'])) {
    $del_id = intval($_GET['del']);
    $pdo->prepare("DELETE FROM noticia WHERE noticia_id = ?")->execute([$del_id]);
    $msg = "Notícia excluída com sucesso!";
}

// Add / Edit Handler
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['noticia_id'] ?? 0);
    $title = trim($_POST['noticia_title'] ?? '');
    $data = trim($_POST['noticia_data'] ?? '');
    $content = trim($_POST['noticia_content'] ?? '');

    // Handle Image Upload
    $noticia_foto = $_POST['existing_foto'] ?? '';
    $uploaded = upload_file('foto_file', __DIR__ . '/../uploads/noticias');
    if ($uploaded) {
        $noticia_foto = $uploaded;
    }

    if (!empty($title) && !empty($content)) {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE noticia SET noticia_title=?, noticia_data=?, noticia_content=?, noticia_foto=? WHERE noticia_id=?");
            $stmt->execute([$title, $data, $content, $noticia_foto, $id]);
            $msg = "Notícia atualizada com sucesso!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO noticia (noticia_title, noticia_data, noticia_content, noticia_foto) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $data, $content, $noticia_foto]);
            $msg = "Nova notícia publicada!";
        }
    } else {
        $err = "Título e Conteúdo da notícia são obrigatórios.";
    }
}

// Edit item fetch
$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM noticia WHERE noticia_id = ?");
    $stmt->execute([$edit_id]);
    $edit_item = $stmt->fetch();
}

$noticias = $pdo->query("SELECT * FROM noticia ORDER BY noticia_id DESC")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="font-orbitron fw-bold text-white mb-1">Gerenciar Notícias & Matérias</h2>
        <p class="text-muted small mb-0">Publique conteúdos e comunicados oficiais no blog do Studio 1250.</p>
    </div>
    <a href="?action=new" class="btn btn-success font-orbitron rounded-pill btn-sm fw-bold"><i class="fa-solid fa-plus me-1"></i> Publicar Notícia</a>
</div>

<?php if(!empty($msg)): ?>
    <div class="alert alert-success py-2 small"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>
<?php if(!empty($err)): ?>
    <div class="alert alert-danger py-2 small"><?php echo htmlspecialchars($err); ?></div>
<?php endif; ?>

<!-- Form Section -->
<?php if (isset($_GET['action']) && $_GET['action'] == 'new' || $edit_item): ?>
    <div class="p-4 admin-card mb-5">
        <h5 class="font-orbitron text-white mb-3"><?php echo $edit_item ? 'Editar Notícia' : 'Publicar Nova Notícia'; ?></h5>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="noticia_id" value="<?php echo $edit_item['noticia_id'] ?? 0; ?>">
            <input type="hidden" name="existing_foto" value="<?php echo htmlspecialchars($edit_item['noticia_foto'] ?? ''); ?>">

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label small text-muted">Título da Notícia *</label>
                    <input type="text" name="noticia_title" class="form-control bg-dark border-secondary text-white" value="<?php echo htmlspecialchars($edit_item['noticia_title'] ?? ''); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Data da Publicação</label>
                    <input type="text" name="noticia_data" class="form-control bg-dark border-secondary text-white" placeholder="DD/MM/AAAA" value="<?php echo htmlspecialchars($edit_item['noticia_data'] ?? date('d/m/Y')); ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label small text-muted">Imagem da Capa (Upload)</label>
                    <input type="file" name="foto_file" class="form-control bg-dark border-secondary text-white" accept="image/*">
                    <?php if(!empty($edit_item['noticia_foto'])): ?>
                        <small class="text-info d-block mt-1">Imagem atual: <?php echo htmlspecialchars($edit_item['noticia_foto']); ?></small>
                    <?php endif; ?>
                </div>
                <div class="col-md-12">
                    <label class="form-label small text-muted">Conteúdo da Notícia *</label>
                    <textarea name="noticia_content" rows="8" class="form-control bg-dark border-secondary text-white" required><?php echo htmlspecialchars($edit_item['noticia_content'] ?? ''); ?></textarea>
                </div>
                <div class="col-12 mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success font-orbitron fw-bold rounded-pill px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Publicar Notícia</button>
                    <a href="/admin/noticias.php" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>

<!-- News Table -->
<div class="p-4 admin-card">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr class="text-muted small">
                    <th>Imagem</th>
                    <th>Título</th>
                    <th>Data</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($noticias as $n): ?>
                    <tr>
                        <td style="width: 70px;">
                            <?php $img = !empty($n['noticia_foto']) ? "/uploads/noticias/".$n['noticia_foto'] : "https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=200&auto=format&fit=crop"; ?>
                            <img src="<?php echo htmlspecialchars($img); ?>" class="rounded" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=200&auto=format&fit=crop'">
                        </td>
                        <td>
                            <strong class="text-white d-block"><?php echo htmlspecialchars($n['noticia_title']); ?></strong>
                            <small class="text-muted text-truncate d-block" style="max-width: 400px;"><?php echo strip_tags($n['noticia_content']); ?></small>
                        </td>
                        <td class="small text-muted"><span class="badge bg-secondary"><?php echo htmlspecialchars($n['noticia_data'] ?: '-'); ?></span></td>
                        <td class="text-end">
                            <a href="?edit=<?php echo $n['noticia_id']; ?>" class="btn btn-sm btn-outline-info rounded-pill"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="?del=<?php echo $n['noticia_id']; ?>" onclick="return confirm('Excluir esta notícia?');" class="btn btn-sm btn-outline-danger rounded-pill"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
