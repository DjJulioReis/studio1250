<?php
// album.php - Detailed Album Photos View
require_once __DIR__ . '/config/db.php';

$album_id = intval($_GET['id'] ?? 0);

if ($album_id <= 0) {
    header("Location: /galeria.php");
    exit;
}

// Fetch album details
$stmtAlbum = $pdo->prepare("SELECT * FROM albuns WHERE album_id = ?");
$stmtAlbum->execute([$album_id]);
$album = $stmtAlbum->fetch();

if (!$album) {
    header("Location: /galeria.php");
    exit;
}

// Pagination for photos
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 24;
$offset = ($page - 1) * $limit;

$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM fotos WHERE foto_album = ?");
$stmtCount->execute([$album_id]);
$totalPhotos = $stmtCount->fetchColumn();
$totalPages = ceil($totalPhotos / $limit);

$stmtPhotos = $pdo->prepare("
    SELECT * FROM fotos
    WHERE foto_album = :album_id
    ORDER BY foto_id ASC
    LIMIT :limit OFFSET :offset
");
$stmtPhotos->bindValue(':album_id', $album_id, PDO::PARAM_INT);
$stmtPhotos->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmtPhotos->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmtPhotos->execute();
$photos = $stmtPhotos->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/index.php" class="text-neon-cyan text-decoration-none">Início</a></li>
            <li class="breadcrumb-item"><a href="/galeria.php" class="text-neon-cyan text-decoration-none">Galeria</a></li>
            <li class="breadcrumb-item active text-white" aria-current="page"><?php echo htmlspecialchars($album['album_name']); ?></li>
        </ol>
    </nav>

    <!-- Album Header Info -->
    <div class="p-4 rounded-4 bg-card border border-secondary border-opacity-25 mb-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="badge bg-neon-pink text-white mb-2 px-3 py-1"><i class="fa-solid fa-camera me-1"></i> <?php echo $totalPhotos; ?> Fotos</span>
                <h1 class="display-6 fw-bold font-orbitron text-white mb-2"><?php echo htmlspecialchars($album['album_name']); ?></h1>
                <p class="text-muted small mb-0">
                    <i class="fa-solid fa-location-dot text-danger me-1"></i> <?php echo htmlspecialchars($album['album_local'] ?: 'Studio 1250'); ?>
                    <?php if (!empty($album['album_data'])): ?> | <i class="fa-solid fa-calendar me-1 text-neon-cyan"></i> <?php echo htmlspecialchars($album['album_data']); ?><?php endif; ?>
                </p>
            </div>
            <div>
                <a href="/galeria.php" class="btn btn-outline-neon rounded-pill"><i class="fa-solid fa-arrow-left me-1"></i> Voltar à Galeria</a>
            </div>
        </div>
        <?php if (!empty($album['album_desc'])): ?>
            <p class="text-muted small mt-3 mb-0"><?php echo nl2br(htmlspecialchars($album['album_desc'])); ?></p>
        <?php endif; ?>
    </div>

    <!-- Photos Grid -->
    <?php if (!empty($photos)): ?>
        <div class="row g-3">
            <?php foreach ($photos as $f): ?>
                <?php
                    $photo_url = "/uploads/fotos/" . $f['foto_url'];
                    $caption = !empty($f['foto_caption']) ? $f['foto_caption'] : $album['album_name'];
                ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <a href="<?php echo htmlspecialchars($photo_url); ?>" data-fancybox="gallery" data-caption="<?php echo htmlspecialchars($caption); ?>" class="d-block card-studio rounded-3 overflow-hidden position-relative group">
                        <div class="ratio ratio-1x1 bg-dark">
                            <img src="<?php echo htmlspecialchars($photo_url); ?>" loading="lazy" class="object-fit-cover w-100 h-100" alt="Foto <?php echo htmlspecialchars($caption); ?>" onerror="this.src='https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=400&auto=format&fit=crop'">
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-5">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link bg-dark text-white border-secondary" href="?id=<?php echo $album_id; ?>&page=<?php echo $page - 1; ?>">Anterior</a>
                    </li>
                    <?php
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);
                        for ($i = $startPage; $i <= $endPage; $i++):
                    ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link <?php echo ($page == $i) ? 'bg-neon-pink border-neon-pink text-white' : 'bg-dark text-white border-secondary'; ?>" href="?id=<?php echo $album_id; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link bg-dark text-white border-secondary" href="?id=<?php echo $album_id; ?>&page=<?php echo $page + 1; ?>">Próximo</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="text-center py-5">
            <i class="fa-solid fa-camera text-muted fs-1 mb-3"></i>
            <h4 class="text-white">Nenhuma foto neste álbum</h4>
            <a href="/galeria.php" class="btn btn-outline-neon rounded-pill mt-2">Voltar</a>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
