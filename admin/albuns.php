<?php
// admin/albuns.php - Manage Albums
include __DIR__ . '/header.php';

$msg = '';
$err = '';

// Delete album and its photos
if (isset($_GET['del'])) {
    $del_id = intval($_GET['del']);
    $pdo->prepare("DELETE FROM fotos WHERE foto_album = ?")->execute([$del_id]);
    $pdo->prepare("DELETE FROM albuns WHERE album_id = ?")->execute([$del_id]);
    $msg = "Álbum e todas as suas fotos foram excluídos!";
}

// Save / Update Album
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['album_id'] ?? 0);
    $name = trim($_POST['album_name'] ?? '');
    $data = trim($_POST['album_data'] ?? '');
    $local = trim($_POST['album_local'] ?? '');
    $desc = trim($_POST['album_desc'] ?? '');

    if (!empty($name)) {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE albuns SET album_name=?, album_data=?, album_local=?, album_desc=? WHERE album_id=?");
            $stmt->execute([$name, $data, $local, $desc, $id]);
            $msg = "Álbum atualizado com sucesso!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO albuns (album_name, album_data, album_local, album_desc) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $data, $local, $desc]);
            $msg = "Novo álbum criado!";
        }
    } else {
        $err = "O nome do álbum é obrigatório.";
    }
}

// Fetch edit item
$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM albuns WHERE album_id = ?");
    $stmt->execute([$edit_id]);
    $edit_item = $stmt->fetch();
}

$albuns = $pdo->query("
    SELECT a.*, (SELECT COUNT(*) FROM fotos f WHERE f.foto_album = a.album_id) as total_fotos
    FROM albuns a
    ORDER BY a.album_id DESC
")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="font-orbitron fw-bold text-white mb-1">Gerenciar Álbuns de Fotos</h2>
        <p class="text-muted small mb-0">Crie álbuns e faça a gestão das fotos dos eventos.</p>
    </div>
    <a href="?action=new" class="btn btn-warning font-orbitron rounded-pill btn-sm text-dark fw-bold"><i class="fa-solid fa-folder-plus me-1"></i> Criar Álbum</a>
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
        <h5 class="font-orbitron text-white mb-3"><?php echo $edit_item ? 'Editar Álbum' : 'Criar Novo Álbum'; ?></h5>
        <form method="POST">
            <input type="hidden" name="album_id" value="<?php echo $edit_item['album_id'] ?? 0; ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted">Nome do Álbum *</label>
                    <input type="text" name="album_name" class="form-control bg-dark border-secondary text-white" value="<?php echo htmlspecialchars($edit_item['album_name'] ?? ''); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Data do Evento / Foto</label>
                    <input type="text" name="album_data" class="form-control bg-dark border-secondary text-white" placeholder="Ex: 15/11/2024" value="<?php echo htmlspecialchars($edit_item['album_data'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Local</label>
                    <input type="text" name="album_local" class="form-control bg-dark border-secondary text-white" value="<?php echo htmlspecialchars($edit_item['album_local'] ?? 'Studio 1250'); ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label small text-muted">Descrição</label>
                    <textarea name="album_desc" rows="2" class="form-control bg-dark border-secondary text-white"><?php echo htmlspecialchars($edit_item['album_desc'] ?? ''); ?></textarea>
                </div>
                <div class="col-12 mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-warning text-dark font-orbitron fw-bold rounded-pill px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Salvar Álbum</button>
                    <a href="/admin/albuns.php" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>

<!-- Albums Cards List -->
<div class="row g-3">
    <?php foreach ($albuns as $alb): ?>
        <div class="col-md-6 col-lg-4">
            <div class="p-3 admin-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="badge bg-warning text-dark fw-bold"><i class="fa-solid fa-camera me-1"></i> <?php echo $alb['total_fotos']; ?> fotos</span>
                        <small class="text-muted"><?php echo htmlspecialchars($alb['album_data'] ?: ''); ?></small>
                    </div>
                    <h5 class="font-orbitron text-white text-truncate mb-2"><?php echo htmlspecialchars($alb['album_name']); ?></h5>
                    <p class="small text-muted mb-3"><i class="fa-solid fa-location-dot me-1 text-danger"></i> <?php echo htmlspecialchars($alb['album_local'] ?: 'Studio 1250'); ?></p>
                </div>
                <div class="d-flex gap-2 pt-2 border-top border-secondary border-opacity-25">
                    <a href="/admin/fotos.php?album_id=<?php echo $alb['album_id']; ?>" class="btn btn-sm btn-outline-warning rounded-pill flex-grow-1"><i class="fa-solid fa-images me-1"></i> Fotos (<?php echo $alb['total_fotos']; ?>)</a>
                    <a href="?edit=<?php echo $alb['album_id']; ?>" class="btn btn-sm btn-outline-info rounded-pill"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="?del=<?php echo $alb['album_id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este álbum e TODAS as suas fotos?');" class="btn btn-sm btn-outline-danger rounded-pill"><i class="fa-solid fa-trash"></i></a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>
