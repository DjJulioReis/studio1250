<?php
// admin/banners.php - Manage Slider Banners
include __DIR__ . '/header.php';

$msg = '';
$err = '';

if (isset($_GET['del'])) {
    $del_id = intval($_GET['del']);
    $pdo->prepare("DELETE FROM banner WHERE banner_id = ?")->execute([$del_id]);
    $msg = "Banner removido com sucesso!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['banner_id'] ?? 0);
    $link = trim($_POST['banner_link'] ?? '');
    $pos = intval($_POST['banner_pos'] ?? 1);

    // Upload
    $banner_url = $_POST['existing_url'] ?? '';
    $uploaded = upload_file('banner_file', __DIR__ . '/../uploads/banners');
    if ($uploaded) {
        $banner_url = $uploaded;
    }

    if (!empty($banner_url)) {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE banner SET banner_url=?, banner_link=?, banner_pos=? WHERE banner_id=?");
            $stmt->execute([$banner_url, $link, $pos, $id]);
            $msg = "Banner atualizado!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO banner (banner_url, banner_link, banner_pos) VALUES (?, ?, ?)");
            $stmt->execute([$banner_url, $link, $pos]);
            $msg = "Novo banner adicionado!";
        }
    } else {
        $err = "Selecione uma imagem para o banner.";
    }
}

$banners = $pdo->query("SELECT * FROM banner ORDER BY banner_pos ASC")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="font-orbitron fw-bold text-white mb-1">Gerenciar Banners do Carrossel</h2>
        <p class="text-muted small mb-0">Gerencie as imagens de destaque da página inicial.</p>
    </div>
    <a href="?action=new" class="btn btn-warning text-dark font-orbitron rounded-pill btn-sm fw-bold"><i class="fa-solid fa-plus me-1"></i> Adicionar Banner</a>
</div>

<?php if(!empty($msg)): ?>
    <div class="alert alert-success py-2 small"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>
<?php if(!empty($err)): ?>
    <div class="alert alert-danger py-2 small"><?php echo htmlspecialchars($err); ?></div>
<?php endif; ?>

<?php if (isset($_GET['action']) && $_GET['action'] == 'new'): ?>
    <div class="p-4 admin-card mb-5">
        <h5 class="font-orbitron text-white mb-3">Adicionar Novo Banner</h5>
        <form method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted">Imagem do Banner *</label>
                    <input type="file" name="banner_file" class="form-control bg-dark border-secondary text-white" accept="image/*" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Link de Destino (Opcional)</label>
                    <input type="text" name="banner_link" class="form-control bg-dark border-secondary text-white" placeholder="/agenda.php">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Ordem / Posição</label>
                    <input type="number" name="banner_pos" class="form-control bg-dark border-secondary text-white" value="1">
                </div>
                <div class="col-12 mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-warning text-dark font-orbitron fw-bold rounded-pill px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Salvar Banner</button>
                    <a href="/admin/banners.php" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>

<div class="row g-3">
    <?php foreach ($banners as $b): ?>
        <div class="col-md-4">
            <div class="p-3 admin-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <?php $img = !empty($b['banner_url']) ? "/uploads/banners/".$b['banner_url'] : "https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=600&auto=format&fit=crop"; ?>
                    <img src="<?php echo htmlspecialchars($img); ?>" class="img-fluid rounded mb-2 border border-secondary" style="height: 160px; width: 100%; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=600&auto=format&fit=crop'">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge bg-secondary">Posição: <?php echo $b['banner_pos']; ?></span>
                        <small class="text-muted text-truncate ms-2"><?php echo htmlspecialchars($b['banner_link'] ?: 'Sem link'); ?></small>
                    </div>
                </div>
                <div class="mt-3 border-top border-secondary border-opacity-25 pt-2 text-end">
                    <a href="?del=<?php echo $b['banner_id']; ?>" onclick="return confirm('Excluir este banner?');" class="btn btn-sm btn-outline-danger rounded-pill"><i class="fa-solid fa-trash me-1"></i> Excluir</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>
