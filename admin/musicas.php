<?php
// admin/musicas.php - Manage Music Tracks & Sets
include __DIR__ . '/header.php';

$msg = '';
$err = '';

// Delete handler
if (isset($_GET['del'])) {
    $del_id = intval($_GET['del']);
    $pdo->prepare("DELETE FROM musicas WHERE musica_id = ?")->execute([$del_id]);
    $msg = "Música removida com sucesso!";
}

// Add / Edit Handler
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['musica_id'] ?? 0);
    $title = trim($_POST['musica_title'] ?? '');
    $artist = trim($_POST['musica_artist'] ?? '');
    $album = trim($_POST['musica_album'] ?? '');
    $url = trim($_POST['musica_url'] ?? '');
    $duration = trim($_POST['musica_duration'] ?? '');

    // Handle File Upload if local MP3 file provided
    $file_upload = upload_file('musica_file', __DIR__ . '/../uploads/musicas', ['mp3', 'wav', 'ogg', 'm4a']);
    if ($file_upload) {
        $url = "/uploads/musicas/" . $file_upload;
    }

    if (!empty($title) && !empty($url)) {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE musicas SET musica_title=?, musica_artist=?, musica_album=?, musica_url=?, musica_duration=? WHERE musica_id=?");
            $stmt->execute([$title, $artist, $album, $url, $duration, $id]);
            $msg = "Música atualizada com sucesso!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO musicas (musica_title, musica_artist, musica_album, musica_url, musica_duration) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $artist, $album, $url, $duration]);
            $msg = "Nova música cadastrada com sucesso!";
        }
    } else {
        $err = "Título e URL/Arquivo da música são obrigatórios.";
    }
}

// Fetch single item for edit
$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM musicas WHERE musica_id = ?");
    $stmt->execute([$edit_id]);
    $edit_item = $stmt->fetch();
}

$musicas = $pdo->query("SELECT * FROM musicas ORDER BY musica_id DESC")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="font-orbitron fw-bold text-white mb-1">Gerenciar Músicas & Sets</h2>
        <p class="text-muted small mb-0">Cadastre arquivos de áudio MP3 ou URLs de transmissão para o player.</p>
    </div>
    <a href="?action=new" class="btn btn-info font-orbitron rounded-pill btn-sm text-dark fw-bold"><i class="fa-solid fa-plus me-1"></i> Adicionar Música</a>
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
        <h5 class="font-orbitron text-white mb-3"><?php echo $edit_item ? 'Editar Música' : 'Cadastrar Nova Música'; ?></h5>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="musica_id" value="<?php echo $edit_item['musica_id'] ?? 0; ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted">Título da Faixa / Set *</label>
                    <input type="text" name="musica_title" class="form-control bg-dark border-secondary text-white" value="<?php echo htmlspecialchars($edit_item['musica_title'] ?? ''); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Artista / DJ</label>
                    <input type="text" name="musica_artist" class="form-control bg-dark border-secondary text-white" value="<?php echo htmlspecialchars($edit_item['musica_artist'] ?? 'Studio 1250 DJ'); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Duração</label>
                    <input type="text" name="musica_duration" class="form-control bg-dark border-secondary text-white" placeholder="Ex: 05:30" value="<?php echo htmlspecialchars($edit_item['musica_duration'] ?? '05:00'); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted">Álbum / Playlist</label>
                    <input type="text" name="musica_album" class="form-control bg-dark border-secondary text-white" value="<?php echo htmlspecialchars($edit_item['musica_album'] ?? 'Studio 1250 Volume 1'); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted">URL Externa do Áudio (MP3 / Stream)</label>
                    <input type="text" name="musica_url" class="form-control bg-dark border-secondary text-white" placeholder="https://..." value="<?php echo htmlspecialchars($edit_item['musica_url'] ?? ''); ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label small text-muted">OU Faça Upload do Arquivo MP3</label>
                    <input type="file" name="musica_file" class="form-control bg-dark border-secondary text-white" accept="audio/*">
                </div>
                <div class="col-12 mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-info text-dark font-orbitron fw-bold rounded-pill px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Salvar Música</button>
                    <a href="/admin/musicas.php" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>

<!-- Music List Table -->
<div class="p-4 admin-card">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr class="text-muted small">
                    <th>#</th>
                    <th>Título / Faixa</th>
                    <th>Artista / DJ</th>
                    <th>Álbum</th>
                    <th>Duração</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($musicas as $m): ?>
                    <tr>
                        <td><i class="fa-solid fa-compact-disc text-info"></i></td>
                        <td>
                            <strong class="text-white d-block"><?php echo htmlspecialchars($m['musica_title']); ?></strong>
                            <small class="text-muted text-truncate d-block" style="max-width: 250px;"><?php echo htmlspecialchars($m['musica_url']); ?></small>
                        </td>
                        <td class="small text-muted"><?php echo htmlspecialchars($m['musica_artist'] ?: 'Studio 1250'); ?></td>
                        <td class="small text-muted"><?php echo htmlspecialchars($m['musica_album'] ?: '-'); ?></td>
                        <td class="small text-muted"><span class="badge bg-secondary"><?php echo htmlspecialchars($m['musica_duration'] ?: '04:00'); ?></span></td>
                        <td class="text-end">
                            <a href="?edit=<?php echo $m['musica_id']; ?>" class="btn btn-sm btn-outline-info rounded-pill"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="?del=<?php echo $m['musica_id']; ?>" onclick="return confirm('Remover esta música?');" class="btn btn-sm btn-outline-danger rounded-pill"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
