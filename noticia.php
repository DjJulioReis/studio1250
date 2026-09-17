<?php
// noticia.php - Article Single View
require_once __DIR__ . '/config/db.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: /noticias.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM noticia WHERE noticia_id = ?");
$stmt->execute([$id]);
$noticia = $stmt->fetch();

if (!$noticia) {
    header("Location: /noticias.php");
    exit;
}

include __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/index.php" class="text-neon-cyan text-decoration-none">Início</a></li>
            <li class="breadcrumb-item"><a href="/noticias.php" class="text-neon-cyan text-decoration-none">Notícias</a></li>
            <li class="breadcrumb-item active text-white" aria-current="page"><?php echo htmlspecialchars($noticia['noticia_title']); ?></li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="p-4 p-md-5 rounded-4 bg-card border border-secondary border-opacity-25">
                <span class="badge bg-neon-pink text-white mb-2 px-3 py-1"><i class="fa-solid fa-calendar me-1"></i> <?php echo htmlspecialchars($noticia['noticia_data'] ?: 'Studio 1250'); ?></span>
                <h1 class="display-6 fw-bold font-orbitron text-white mb-4"><?php echo htmlspecialchars($noticia['noticia_title']); ?></h1>

                <?php if (!empty($noticia['noticia_foto'])): ?>
                    <div class="mb-4 text-center">
                        <img src="/uploads/noticias/<?php echo htmlspecialchars($noticia['noticia_foto']); ?>" class="img-fluid rounded-4 border border-secondary shadow max-h-500" alt="<?php echo htmlspecialchars($noticia['noticia_title']); ?>" onerror="this.src='https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=800&auto=format&fit=crop'">
                    </div>
                <?php endif; ?>

                <div class="article-body text-light leading-relaxed">
                    <?php
                        // Clean legacy escaped backslashes from DB dump
                        $content = str_replace(['\\r\\n', '\\n', '\\r'], '<br>', $noticia['noticia_content']);
                        // Sanitize basic tags or render safely
                        echo $content;
                    ?>
                </div>

                <hr class="border-secondary border-opacity-25 my-4">

                <div class="d-flex justify-content-between align-items-center">
                    <a href="/noticias.php" class="btn btn-outline-neon rounded-pill"><i class="fa-solid fa-arrow-left me-1"></i> Voltar às Notícias</a>
                    <a href="/agenda.php" class="btn btn-neon-pink rounded-pill"><i class="fa-solid fa-calendar-alt me-1"></i> Ver Próximos Eventos</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
