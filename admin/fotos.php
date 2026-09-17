<?php
// admin/fotos.php - Manage photos inside an album
include __DIR__ . '/header.php';

$album_id = intval($_GET['album_id'] ?? 0);
if ($album_id <= 0) {
    header("Location: /admin/albuns.php");
    exit;
}

$stmtAlbum = $pdo->prepare("SELECT * FROM albuns WHERE album_id = ?");
$stmtAlbum->execute([$album_id]);
$album = $stmtAlbum->fetch();

if (!$album) {
    header("Location: /admin/albuns.php");
    exit;
}

$msg = '';
$err = '';

// Single photo delete
if (isset($_GET['del_photo'])) {
    $del_id = intval($_GET['del_photo']);
    $pdo->prepare("DELETE FROM fotos WHERE foto_id = ? AND foto_album = ?")->execute([$del_id, $album_id]);
    $msg = "Foto excluída com sucesso!";
}

// Upload multiple photos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photos'])) {
    $files = $_FILES['photos'];
    $success_count = 0;

    $upload_dir = __DIR__ . '/../uploads/fotos';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $filename = md5(uniqid(microtime(), true)) . '.' . $ext;
                if (move_uploaded_file($files['tmp_name'][$i], $upload_dir . '/' . $filename)) {
                    $stmtIns = $pdo->prepare("INSERT INTO fotos (foto_url, foto_album, foto_data) VALUES (?, ?, ?)");
                    $stmtIns->execute([$filename, $album_id, date('Y-m-d H:i:s')]);
                    $success_count++;
                }
            }
        }
    }

    if ($success_count > 0) {
        $msg = "Enviada(s) {$success_count} foto(s) para o álbum!";
    } else {
        $err = "Nenhuma foto pôde ser enviada. Verifique os formatos selecionados.";
    }
}

// Fetch album photos with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 30;
$offset = ($page - 1) * $limit;

$stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM fotos WHERE foto_album = ?");
$stmtTotal->execute([$album_id]);
$totalPhotos = $stmtTotal->fetchColumn();
$totalPages = ceil($totalPhotos / $limit);

$stmtPhotos = $pdo->prepare("SELECT * FROM fotos WHERE foto_album = :album_id ORDER BY foto_id DESC LIMIT :limit OFFSET :offset");
$stmtPhotos->bindValue(':album_id', $album_id, PDO::PARAM_INT);
$stmtPhotos->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmtPhotos->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtPhotos->execute();
$photos = $stmtPhotos->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="font-orbitron fw-bold text-white mb-1">Fotos do Álbum: <?php echo htmlspecialchars($album['album_name']); ?></h2>
        <p class="text-muted small mb-0">Gerencie e envie novas fotos para este álbum.</p>
    </div>
    <a href="/admin/albuns.php" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Voltar aos Álbuns</a>
</div>

<?php if(!empty($msg)): ?>
    <div class="alert alert-success py-2 small"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>
<?php if(!empty($err)): ?>
    <div class="alert alert-danger py-2 small"><?php echo htmlspecialchars($err); ?></div>
<?php endif; ?>

<!-- Multiple Photo Upload Box -->
<div class="p-4 admin-card mb-5">
    <h5 class="font-orbitron text-white mb-3"><i class="fa-solid fa-upload text-warning me-2"></i> Enviar Novas Fotos (Múltiplos arquivos)</h5>
    <form method="POST" enctype="multipart/form-data">
        <div class="row g-3 align-items-center">
            <div class="col-md-9">
                <input type="file" name="photos[]" multiple class="form-control bg-dark border-secondary text-white" accept="image/*" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-warning text-dark font-orbitron fw-bold w-100 rounded-pill"><i class="fa-solid fa-cloud-arrow-up me-1"></i> Enviar Fotos</button>
            </div>
        </div>
    </form>
</div>

<!-- Photos Grid -->
<div class="p-4 admin-card">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h5 class="font-orbitron text-white mb-0">Total: <?php echo $totalPhotos; ?> Fotos</h5>
    </div>

    <?php if(!empty($photos)): ?>
        <div class="row g-3">
            <?php foreach($photos as $p): ?>
                <?php $img_path = "/uploads/fotos/" . $p['foto_url']; ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="position-relative rounded overflow-hidden border border-secondary border-opacity-25 bg-dark">
                        <div class="ratio ratio-1x1">
                            <img src="<?php echo htmlspecialchars($img_path); ?>" class="object-fit-cover w-100 h-100" alt="Foto" onerror="this.src='https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=200&auto=format&fit=crop'">
                        </div>
                        <a href="?album_id=<?php echo $album_id; ?>&del_photo=<?php echo $p['foto_id']; ?>&page=<?php echo $page; ?>" onclick="return confirm('Excluir esta foto?');" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 py-0 px-2 rounded-circle" title="Excluir Foto">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link <?php echo ($page == $i) ? 'bg-warning text-dark border-warning' : 'bg-dark text-white border-secondary'; ?>" href="?album_id=<?php echo $album_id; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <p class="text-muted text-center py-4">Nenhuma foto cadastrada neste álbum ainda.</p>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>
