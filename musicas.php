<?php
// musicas.php - Audio Player & Setlists Library
require_once __DIR__ . '/config/db.php';

$stmt = $pdo->query("SELECT * FROM musicas ORDER BY musica_id DESC");
$musicas = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <!-- Header -->
    <div class="text-center mb-5">
        <span class="badge bg-neon-pink text-white uppercase tracking-wider mb-2 px-3 py-2 fs-6">AUDIO & SETLISTS</span>
        <h1 class="display-5 fw-bold font-orbitron text-white">MÚSICAS & SETS EXCLUSIVOS</h1>
        <p class="text-muted max-w-2xl mx-auto">Ouça a seleção oficial de músicas e sets gravados pelos DJs do Studio 1250.</p>
    </div>

    <div class="row g-4">
        <!-- Main Tracks List -->
        <div class="col-lg-8">
            <div class="p-4 rounded-4 bg-card border border-secondary border-opacity-25">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="font-orbitron text-white mb-0"><i class="fa-solid fa-list-ul text-neon-pink me-2"></i> Playlist da Casa</h4>
                    <span class="badge bg-dark border border-neon-cyan text-neon-cyan"><?php echo count($musicas); ?> faixas</span>
                </div>

                <?php if (!empty($musicas)): ?>
                    <div class="list-group list-group-flush bg-transparent">
                        <?php foreach ($musicas as $index => $m): ?>
                            <div class="list-group-item bg-dark bg-opacity-50 text-white border-secondary border-opacity-25 d-flex align-items-center justify-content-between p-3 rounded mb-2">
                                <div class="d-flex align-items-center me-3 text-truncate">
                                    <span class="font-orbitron text-muted fw-bold me-3" style="width: 25px;"><?php echo sprintf("%02d", $index + 1); ?></span>
                                    <div class="me-3">
                                        <i class="fa-solid fa-compact-disc text-neon-pink fs-3"></i>
                                    </div>
                                    <div class="text-truncate">
                                        <h6 class="mb-0 text-white text-truncate font-orbitron fs-6"><?php echo htmlspecialchars($m['musica_title']); ?></h6>
                                        <small class="text-muted"><i class="fa-solid fa-user-ninja me-1 text-neon-cyan"></i><?php echo htmlspecialchars($m['musica_artist'] ?: 'DJ Studio 1250'); ?> <?php if(!empty($m['musica_album'])): ?>| <i class="fa-solid fa-compact-disc me-1"></i><?php echo htmlspecialchars($m['musica_album']); ?><?php endif; ?></small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary me-3 d-none d-sm-inline-block"><?php echo htmlspecialchars($m['musica_duration'] ?: '05:00'); ?></span>
                                    <button class="btn btn-neon-pink rounded-circle btn-play-track" data-url="<?php echo htmlspecialchars($m['musica_url']); ?>" data-title="<?php echo htmlspecialchars($m['musica_title']); ?>" data-artist="<?php echo htmlspecialchars($m['musica_artist']); ?>">
                                        <i class="fa-solid fa-play"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">Nenhuma música cadastrada no momento.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar Player Info -->
        <div class="col-lg-4">
            <div class="p-4 rounded-4 bg-card border border-secondary border-opacity-25 sticky-top" style="top: 100px;">
                <div class="text-center mb-4">
                    <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?q=80&w=600&auto=format&fit=crop" class="img-fluid rounded-4 shadow mb-3 border border-secondary" alt="Disc Jockey">
                    <h5 class="font-orbitron text-white">Rádio Studio 1250</h5>
                    <p class="text-muted small">Música de qualidade 24h por dia para relembrar os clássicos inesquecíveis.</p>
                </div>
                <div class="p-3 bg-dark rounded border border-secondary text-center">
                    <i class="fa-solid fa-headphones text-neon-cyan fs-1 mb-2"></i>
                    <p class="small text-muted mb-0">Clique no botão <span class="text-neon-pink fw-bold"><i class="fa-solid fa-play"></i> Play</span> de qualquer faixa para ouvir direto no player no rodapé da página.</p>
                </div>
                <div class="mt-4">
                    <a href="/contato.php" class="btn btn-outline-neon w-100 rounded-pill"><i class="fa-solid fa-comment-dots me-2"></i> Pedir Música ou DJ Set</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
