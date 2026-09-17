<?php
// galeria.php - Albums & Photos Gallery
require_once __DIR__ . '/config/db.php';

$page = max(1, intval($_GET['page'] ?? 1));
$limit = 12;
$offset = ($page - 1) * $limit;

// Count total albums
$total = $pdo->query("SELECT COUNT(*) FROM albuns")->fetchColumn();
$totalPages = ceil($total / $limit);

// Fetch albums with total photo count and cover image
$stmt = $pdo->prepare("
    SELECT a.*,
           (SELECT COUNT(*) FROM fotos f WHERE f.foto_album = a.album_id) as total_fotos,
           (SELECT foto_url FROM fotos f WHERE f.foto_album = a.album_id ORDER BY foto_id ASC LIMIT 1) as cover_photo
    FROM albuns a
    ORDER BY a.album_id DESC
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$albuns = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <span class="badge bg-neon-pink text-white uppercase tracking-wider mb-2 px-3 py-2 fs-6">COBERTURA FOTOGRÁFICA</span>
        <h1 class="display-5 fw-bold font-orbitron text-white">GALERIA DE FOTOS</h1>
        <p class="text-muted max-w-2xl mx-auto">Confira os registros das melhores noites, públicos e festas que marcaram o Studio 1250.</p>
    </div>

    <!-- Album Cards Grid -->
    <?php if (!empty($albuns)): ?>
        <div class="row g-4">
            <?php foreach ($albuns as $alb): ?>
                <div class="col-lg-3 col-md-6">
                    <a href="/album.php?id=<?php echo $alb['album_id']; ?>" class="text-decoration-none">
                        <div class="card card-studio h-100 text-white">
                            <div class="card-img-wrapper">
                                <?php
                                    $cover = !empty($alb['cover_photo']) ? "/uploads/fotos/" . $alb['cover_photo'] : "https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600&auto=format&fit=crop";
                                ?>
                                <img src="<?php echo htmlspecialchars($cover); ?>" alt="<?php echo htmlspecialchars($alb['album_name']); ?>" onerror="this.src='https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600&auto=format&fit=crop'">
                                <span class="card-badge bg-neon-pink"><i class="fa-solid fa-camera me-1"></i> <?php echo $alb['total_fotos']; ?> fotos</span>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="font-orbitron mb-1 text-truncate text-white"><?php echo htmlspecialchars($alb['album_name']); ?></h6>
                                <div class="d-flex align-items-center justify-content-between small text-muted mt-2">
                                    <span><i class="fa-solid fa-location-dot me-1 text-danger"></i><?php echo htmlspecialchars($alb['album_local'] ?: 'Studio 1250'); ?></span>
                                    <span><?php echo htmlspecialchars($alb['album_data'] ?: ''); ?></span>
                                </div>
                            </div>
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
                        <a class="page-link bg-dark text-white border-secondary" href="?page=<?php echo $page - 1; ?>">Anterior</a>
                    </li>
                    <?php
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);
                        for ($i = $startPage; $i <= $endPage; $i++):
                    ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link <?php echo ($page == $i) ? 'bg-neon-pink border-neon-pink text-white' : 'bg-dark text-white border-secondary'; ?>" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link bg-dark text-white border-secondary" href="?page=<?php echo $page + 1; ?>">Próximo</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <div class="text-center py-5">
            <i class="fa-solid fa-images text-muted fs-1 mb-3"></i>
            <h4 class="text-white">Nenhum álbum disponível</h4>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
