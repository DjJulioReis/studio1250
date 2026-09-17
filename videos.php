<?php
// videos.php - Videos & Teasers Page
require_once __DIR__ . '/config/db.php';

$stmt = $pdo->query("SELECT * FROM video ORDER BY video_id DESC");
$videos = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <span class="badge bg-neon-pink text-white uppercase tracking-wider mb-2 px-3 py-2 fs-6">CANAL OFICIAL</span>
        <h1 class="display-5 fw-bold font-orbitron text-white">VÍDEOS & CLIPES</h1>
        <p class="text-muted max-w-2xl mx-auto">Teasers, gravações de festas históricas e vídeos promocionais do Studio 1250.</p>
    </div>

    <?php if (!empty($videos)): ?>
        <div class="row g-4">
            <?php foreach ($videos as $v): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card card-studio h-100">
                        <div class="card-img-wrapper">
                            <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars($v['video_cod']); ?>/hqdefault.jpg" alt="<?php echo htmlspecialchars($v['video_title']); ?>">
                            <a href="https://www.youtube.com/watch?v=<?php echo htmlspecialchars($v['video_cod']); ?>" data-fancybox class="position-absolute top-50 start-50 translate-middle btn btn-neon-pink rounded-circle p-3">
                                <i class="fa-solid fa-play fs-4 ms-1"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title font-orbitron text-white fs-6 mb-0"><?php echo htmlspecialchars($v['video_title']); ?></h5>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fa-solid fa-film text-muted fs-1 mb-3"></i>
            <h4 class="text-white">Nenhum vídeo cadastrado</h4>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
