<?php
// index.php - Studio 1250 Main Homepage
require_once __DIR__ . '/config/db.php';

// Fetch Banners
$stmtBanner = $pdo->query("SELECT * FROM banner ORDER BY banner_pos ASC LIMIT 5");
$banners = $stmtBanner->fetchAll();

// Fetch Upcoming / Recent Events (Agenda)
$stmtAgenda = $pdo->query("SELECT * FROM agenda ORDER BY agenda_data DESC LIMIT 6");
$agendas = $stmtAgenda->fetchAll();

// Fetch Latest News
$stmtNoticia = $pdo->query("SELECT * FROM noticia ORDER BY noticia_id DESC LIMIT 3");
$noticias = $stmtNoticia->fetchAll();

// Fetch Featured Albums
$stmtAlbuns = $pdo->query("SELECT a.*, (SELECT foto_url FROM fotos f WHERE f.foto_album = a.album_id ORDER BY foto_id ASC LIMIT 1) as cover_photo FROM albuns a ORDER BY a.album_id DESC LIMIT 4");
$albuns = $stmtAlbuns->fetchAll();

// Fetch Featured Videos
$stmtVideos = $pdo->query("SELECT * FROM video ORDER BY video_id DESC LIMIT 6");
$videos = $stmtVideos->fetchAll();

// Fetch Featured Music
$stmtMusicas = $pdo->query("SELECT * FROM musicas ORDER BY musica_id DESC LIMIT 4");
$musicas = $stmtMusicas->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<div class="container my-4">
    <!-- Hero Slider Section -->
    <div class="hero-slider mb-5">
        <?php if (!empty($banners)): ?>
            <?php foreach ($banners as $b): ?>
                <div class="hero-slider-item" style="background-image: url('/uploads/banners/<?php echo htmlspecialchars($b['banner_url'] ?? ''); ?>'), url('https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=1200&auto=format&fit=crop');">
                    <div class="hero-overlay">
                        <div>
                            <span class="badge bg-neon-pink text-white uppercase tracking-widest mb-2 px-3 py-2">Destaque Studio 1250</span>
                            <h1 class="display-4 fw-bold text-white font-orbitron">REVIVA OS MELHORES MOMENTOS</h1>
                            <p class="lead text-light mb-3">O templo absoluto do Flashback 70s, 80s, 90s & Rock em Curitiba.</p>
                            <a href="/agenda.php" class="btn btn-neon-pink rounded-pill px-4 py-2 fw-bold"><i class="fa-solid fa-calendar-alt me-2"></i>Ver Agenda Completa</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="hero-slider-item" style="background-image: url('https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?q=80&w=1200&auto=format&fit=crop');">
                <div class="hero-overlay">
                    <div>
                        <span class="badge bg-neon-pink text-white uppercase tracking-widest mb-2 px-3 py-2">BEM-VINDO AO STUDIO 1250</span>
                        <h1 class="display-4 fw-bold text-white font-orbitron">NOITES INESQUECÍVEIS & FLASHBACK</h1>
                        <p class="lead text-light mb-3">Confira nossa programação, fotos de festas anteriores e playlists exclusivas.</p>
                        <a href="/agenda.php" class="btn btn-neon-pink rounded-pill px-4 py-2 fw-bold"><i class="fa-solid fa-calendar-alt me-2"></i>Ver Agenda Completa</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quick Stats Bar -->
    <div class="row g-3 text-center mb-5">
        <div class="col-6 col-md-3">
            <div class="p-3 bg-card border border-secondary border-opacity-25 rounded-3">
                <i class="fa-solid fa-calendar-check text-neon-pink fs-2 mb-2"></i>
                <h3 class="font-orbitron fw-bold mb-0 text-white">28+</h3>
                <small class="text-muted text-uppercase">Grandes Eventos</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 bg-card border border-secondary border-opacity-25 rounded-3">
                <i class="fa-solid fa-camera text-neon-cyan fs-2 mb-2"></i>
                <h3 class="font-orbitron fw-bold mb-0 text-white">10.000+</h3>
                <small class="text-muted text-uppercase">Fotos na Galeria</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 bg-card border border-secondary border-opacity-25 rounded-3">
                <i class="fa-solid fa-record-vinyl text-warning fs-2 mb-2"></i>
                <h3 class="font-orbitron fw-bold mb-0 text-white">40+</h3>
                <small class="text-muted text-uppercase">Álbuns Históricos</small>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-3 bg-card border border-secondary border-opacity-25 rounded-3">
                <i class="fa-solid fa-sliders text-danger fs-2 mb-2"></i>
                <h3 class="font-orbitron fw-bold mb-0 text-white">DJs</h3>
                <small class="text-muted text-uppercase">Sets Exclusivos</small>
            </div>
        </div>
    </div>

    <!-- Agenda / Próximos Eventos Section -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="section-title font-orbitron text-white mb-0">Agenda & Festas</h2>
            <p class="text-muted small">Fique por dentro das próximas noites e eventos do Studio 1250</p>
        </div>
        <a href="/agenda.php" class="btn btn-sm btn-outline-neon rounded-pill"><i class="fa-solid fa-arrow-right me-1"></i> Ver Todos</a>
    </div>

    <div class="row g-4 mb-5">
        <?php foreach ($agendas as $ag): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card card-studio h-100">
                    <div class="card-img-wrapper">
                        <?php
                            $img_src = !empty($ag['foto_url']) ? "/uploads/agenda/" . $ag['foto_url'] : "https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=600&auto=format&fit=crop";
                        ?>
                        <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($ag['agenda_title'] ?? 'Evento'); ?>" onerror="this.src='https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=600&auto=format&fit=crop'">
                        <span class="card-badge"><i class="fa-solid fa-clock text-neon-cyan me-1"></i><?php echo htmlspecialchars($ag['agenda_hora'] ?? '23:00'); ?></span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="text-neon-pink fw-semibold small mb-1">
                            <i class="fa-solid fa-calendar me-1"></i><?php echo date('d/m/Y', strtotime($ag['agenda_data'] ?? 'now')); ?>
                        </div>
                        <h5 class="card-title font-orbitron text-white text-truncate"><?php echo htmlspecialchars($ag['agenda_title'] ?? 'Festa Studio 1250'); ?></h5>
                        <p class="card-text text-muted small flex-grow-1">
                            <strong>Atração:</strong> <?php echo htmlspecialchars($ag['agenda_atracao'] ?: 'DJs Studio 1250'); ?><br>
                            <strong>Local:</strong> <?php echo htmlspecialchars($ag['agenda_local'] ?: 'Studio 1250'); ?>
                        </p>
                        <a href="/agenda.php#event-<?php echo $ag['agenda_id']; ?>" class="btn btn-sm btn-neon-pink w-100 rounded-pill mt-2"><i class="fa-solid fa-circle-info me-1"></i> Mais Detalhes</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Music Player Feature Section -->
    <div class="p-4 p-md-5 rounded-4 bg-gradient border border-secondary border-opacity-25 mb-5" style="background: linear-gradient(135deg, #150826 0%, #0d1326 100%);">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <span class="badge bg-neon-cyan text-dark fw-bold mb-2">SETS & SETLISTS</span>
                <h2 class="font-orbitron text-white mb-3">Aumente o Som com o Studio 1250</h2>
                <p class="text-muted">Ouça agora as principais faixas de Flashback, Synthwave e sets gravados ao vivo nas nossas festas lendárias.</p>
                <a href="/musicas.php" class="btn btn-outline-neon rounded-pill px-4"><i class="fa-solid fa-compact-disc me-2"></i> Ver Biblioteca de Músicas</a>
            </div>
            <div class="col-lg-6">
                <div class="list-group list-group-flush bg-transparent">
                    <?php foreach ($musicas as $m): ?>
                        <div class="list-group-item bg-dark bg-opacity-50 text-white border-secondary border-opacity-25 d-flex align-items-center justify-content-between rounded mb-2">
                            <div class="d-flex align-items-center text-truncate me-3">
                                <i class="fa-solid fa-music text-neon-pink fs-4 me-3"></i>
                                <div class="text-truncate">
                                    <div class="fw-bold small text-truncate"><?php echo htmlspecialchars($m['musica_title']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($m['musica_artist'] ?: 'Studio 1250 DJ'); ?></small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary me-3 d-none d-sm-inline-block"><?php echo htmlspecialchars($m['musica_duration'] ?: '04:00'); ?></span>
                                <button class="btn btn-sm btn-neon-pink rounded-circle btn-play-track" data-url="<?php echo htmlspecialchars($m['musica_url']); ?>" data-title="<?php echo htmlspecialchars($m['musica_title']); ?>" data-artist="<?php echo htmlspecialchars($m['musica_artist']); ?>">
                                    <i class="fa-solid fa-play"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Photo Albums -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="section-title font-orbitron text-white mb-0">Galeria de Fotos</h2>
            <p class="text-muted small">Registros fotográficos dos eventos e da galera que faz a festa</p>
        </div>
        <a href="/galeria.php" class="btn btn-sm btn-outline-neon rounded-pill"><i class="fa-solid fa-camera me-1"></i> Todas as Fotos</a>
    </div>

    <div class="row g-4 mb-5">
        <?php foreach ($albuns as $alb): ?>
            <div class="col-lg-3 col-md-6">
                <a href="/album.php?id=<?php echo $alb['album_id']; ?>" class="text-decoration-none">
                    <div class="card card-studio h-100 text-white">
                        <div class="card-img-wrapper">
                            <?php
                                $cover = !empty($alb['cover_photo']) ? "/uploads/fotos/" . $alb['cover_photo'] : "https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600&auto=format&fit=crop";
                            ?>
                            <img src="<?php echo htmlspecialchars($cover); ?>" alt="<?php echo htmlspecialchars($alb['album_name']); ?>" onerror="this.src='https://images.unsplash.com/photo-1514525253161-7a46d19cd819?q=80&w=600&auto=format&fit=crop'">
                            <span class="card-badge"><i class="fa-solid fa-images me-1"></i> Ver Álbum</span>
                        </div>
                        <div class="card-body p-3">
                            <h6 class="font-orbitron mb-1 text-truncate text-white"><?php echo htmlspecialchars($alb['album_name']); ?></h6>
                            <small class="text-muted d-block"><i class="fa-solid fa-location-dot me-1 text-danger"></i><?php echo htmlspecialchars($alb['album_local'] ?: 'Studio 1250'); ?></small>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Video Carousel -->
    <?php if (!empty($videos)): ?>
        <div class="mb-5">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="section-title font-orbitron text-white mb-0">Vídeos & Clipes</h2>
                    <p class="text-muted small">Assista momentos marcantes e teasers das nossas festas</p>
                </div>
                <a href="/videos.php" class="btn btn-sm btn-outline-neon rounded-pill"><i class="fa-solid fa-film me-1"></i> Ver Todos os Vídeos</a>
            </div>

            <div class="video-slider row">
                <?php foreach ($videos as $v): ?>
                    <div class="px-2">
                        <div class="card card-studio">
                            <div class="card-img-wrapper">
                                <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars($v['video_cod']); ?>/hqdefault.jpg" alt="<?php echo htmlspecialchars($v['video_title']); ?>">
                                <a href="https://www.youtube.com/watch?v=<?php echo htmlspecialchars($v['video_cod']); ?>" data-fancybox class="position-absolute top-50 start-50 translate-middle btn btn-neon-pink rounded-circle btn-lg p-3">
                                    <i class="fa-solid fa-play fs-4 ms-1"></i>
                                </a>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="font-orbitron text-white text-truncate mb-0"><?php echo htmlspecialchars($v['video_title']); ?></h6>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
