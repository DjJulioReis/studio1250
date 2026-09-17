<?php
// noticias.php - News Articles List
require_once __DIR__ . '/config/db.php';

$stmt = $pdo->query("SELECT * FROM noticia ORDER BY noticia_id DESC");
$noticias = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <span class="badge bg-neon-pink text-white uppercase tracking-wider mb-2 px-3 py-2 fs-6">NOVIDADES & DESTAQUES</span>
        <h1 class="display-5 fw-bold font-orbitron text-white">NOTÍCIAS DO STUDIO 1250</h1>
        <p class="text-muted max-w-2xl mx-auto">Fique por dentro das novidades, matérias especiais e informes sobre a casa noturna.</p>
    </div>

    <?php if (!empty($noticias)): ?>
        <div class="row g-4">
            <?php foreach ($noticias as $n): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card card-studio h-100">
                        <div class="card-img-wrapper">
                            <?php
                                $img_src = !empty($n['noticia_foto']) ? "/uploads/noticias/" . $n['noticia_foto'] : "https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=600&auto=format&fit=crop";
                            ?>
                            <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($n['noticia_title']); ?>" onerror="this.src='https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=600&auto=format&fit=crop'">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <span class="text-neon-cyan small fw-bold mb-1"><i class="fa-solid fa-calendar me-1"></i><?php echo htmlspecialchars($n['noticia_data'] ?: 'Studio 1250'); ?></span>
                            <h5 class="card-title font-orbitron text-white text-truncate mb-2"><?php echo htmlspecialchars($n['noticia_title']); ?></h5>
                            <p class="card-text text-muted small flex-grow-1">
                                <?php
                                    $clean_text = strip_tags($n['noticia_content']);
                                    echo mb_strimwidth($clean_text, 0, 140, "...");
                                ?>
                            </p>
                            <a href="/noticia.php?id=<?php echo $n['noticia_id']; ?>" class="btn btn-sm btn-outline-neon rounded-pill mt-3 w-100"><i class="fa-solid fa-newspaper me-1"></i> Ler Notícia Completa</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fa-solid fa-newspaper text-muted fs-1 mb-3"></i>
            <h4 class="text-white">Nenhuma notícia cadastrada</h4>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
