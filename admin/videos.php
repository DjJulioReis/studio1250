<?php
// admin/videos.php - Manage YouTube Videos
include __DIR__ . '/header.php';

$msg = '';
$err = '';

if (isset($_GET['del'])) {
    $del_id = intval($_GET['del']);
    $pdo->prepare("DELETE FROM video WHERE video_id = ?")->execute([$del_id]);
    $msg = "Vídeo removido!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['video_id'] ?? 0);
    $title = trim($_POST['video_title'] ?? '');
    $cod = trim($_POST['video_cod'] ?? '');

    // Extract code if user pasted full YouTube URL
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $cod, $match)) {
        $cod = $match[1];
    }

    if (!empty($title) && !empty($cod)) {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE video SET video_title=?, video_cod=? WHERE video_id=?");
            $stmt->execute([$title, $cod, $id]);
            $msg = "Vídeo atualizado!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO video (video_title, video_cod, video_thumb) VALUES (?, ?, ?)");
            $stmt->execute([$title, $cod, '']);
            $msg = "Novo vídeo adicionado!";
        }
    } else {
        $err = "Título e Código/URL do vídeo do YouTube são obrigatórios.";
    }
}

$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM video WHERE video_id = ?");
    $stmt->execute([$edit_id]);
    $edit_item = $stmt->fetch();
}

$videos = $pdo->query("SELECT * FROM video ORDER BY video_id DESC")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="font-orbitron fw-bold text-white mb-1">Gerenciar Vídeos do YouTube</h2>
        <p class="text-muted small mb-0">Adicione vídeos promocionais e teasers das festas.</p>
    </div>
    <a href="?action=new" class="btn btn-primary font-orbitron rounded-pill btn-sm fw-bold"><i class="fa-solid fa-plus me-1"></i> Adicionar Vídeo</a>
</div>

<?php if(!empty($msg)): ?>
    <div class="alert alert-success py-2 small"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>
<?php if(!empty($err)): ?>
    <div class="alert alert-danger py-2 small"><?php echo htmlspecialchars($err); ?></div>
<?php endif; ?>

<?php if (isset($_GET['action']) && $_GET['action'] == 'new' || $edit_item): ?>
    <div class="p-4 admin-card mb-5">
        <h5 class="font-orbitron text-white mb-3"><?php echo $edit_item ? 'Editar Vídeo' : 'Cadastrar Novo Vídeo'; ?></h5>
        <form method="POST">
            <input type="hidden" name="video_id" value="<?php echo $edit_item['video_id'] ?? 0; ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted">Título do Vídeo *</label>
                    <input type="text" name="video_title" class="form-control bg-dark border-secondary text-white" value="<?php echo htmlspecialchars($edit_item['video_title'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted">Código do YouTube ou URL do Vídeo *</label>
                    <input type="text" name="video_cod" class="form-control bg-dark border-secondary text-white" placeholder="Ex: NZg9M30TO70 ou https://www.youtube.com/watch?v=NZg9M30TO70" value="<?php echo htmlspecialchars($edit_item['video_cod'] ?? ''); ?>" required>
                </div>
                <div class="col-12 mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary font-orbitron fw-bold rounded-pill px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Salvar Vídeo</button>
                    <a href="/admin/videos.php" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>

<div class="row g-3">
    <?php foreach ($videos as $v): ?>
        <div class="col-md-4">
            <div class="p-3 admin-card h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="ratio ratio-16x9 rounded overflow-hidden mb-2">
                        <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars($v['video_cod']); ?>/hqdefault.jpg" class="object-fit-cover w-100 h-100" alt="Thumb">
                    </div>
                    <h6 class="font-orbitron text-white text-truncate mb-1"><?php echo htmlspecialchars($v['video_title']); ?></h6>
                    <small class="text-muted d-block mb-3">ID YouTube: <?php echo htmlspecialchars($v['video_cod']); ?></small>
                </div>
                <div class="d-flex gap-2 pt-2 border-top border-secondary border-opacity-25">
                    <a href="?edit=<?php echo $v['video_id']; ?>" class="btn btn-sm btn-outline-info rounded-pill flex-grow-1"><i class="fa-solid fa-pen-to-square me-1"></i> Editar</a>
                    <a href="?del=<?php echo $v['video_id']; ?>" onclick="return confirm('Remover este vídeo?');" class="btn btn-sm btn-outline-danger rounded-pill"><i class="fa-solid fa-trash"></i></a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include __DIR__ . '/footer.php'; ?>
