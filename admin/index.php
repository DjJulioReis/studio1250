<?php
// admin/index.php - Dashboard Overview
include __DIR__ . '/header.php';

// Counts
$total_agenda = $pdo->query("SELECT COUNT(*) FROM agenda")->fetchColumn();
$total_albuns = $pdo->query("SELECT COUNT(*) FROM albuns")->fetchColumn();
$total_fotos  = $pdo->query("SELECT COUNT(*) FROM fotos")->fetchColumn();
$total_musicas= $pdo->query("SELECT COUNT(*) FROM musicas")->fetchColumn();
$total_noticias=$pdo->query("SELECT COUNT(*) FROM noticia")->fetchColumn();
$total_videos = $pdo->query("SELECT COUNT(*) FROM video")->fetchColumn();
$total_contatos=$pdo->query("SELECT COUNT(*) FROM contato")->fetchColumn();

// Recent messages
$recent_contatos = $pdo->query("SELECT * FROM contato ORDER BY contato_id DESC LIMIT 5")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="font-orbitron fw-bold text-white mb-1">Painel Geral</h2>
        <p class="text-muted small mb-0">Bem-vindo ao sistema de gerenciamento de conteúdo do Studio 1250.</p>
    </div>
    <a href="/index.php" target="_blank" class="btn btn-outline-info rounded-pill btn-sm"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Visualizar Site</a>
</div>

<!-- Stats Cards Grid -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="p-3 admin-card d-flex align-items-center justify-content-between">
            <div>
                <span class="text-light fw-bold small d-block">Agenda / Festas</span>
                <h3 class="font-orbitron fw-bold text-danger mb-0"><?php echo $total_agenda; ?></h3>
            </div>
            <i class="fa-solid fa-calendar-days fs-1 text-danger opacity-75"></i>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="p-3 admin-card d-flex align-items-center justify-content-between">
            <div>
                <span class="text-light fw-bold small d-block">Álbuns de Fotos</span>
                <h3 class="font-orbitron fw-bold text-warning mb-0"><?php echo $total_albuns; ?></h3>
                <small class="text-light opacity-75"><?php echo number_format($total_fotos); ?> fotos</small>
            </div>
            <i class="fa-solid fa-images fs-1 text-warning opacity-75"></i>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="p-3 admin-card d-flex align-items-center justify-content-between">
            <div>
                <span class="text-light fw-bold small d-block">Músicas & Sets</span>
                <h3 class="font-orbitron fw-bold text-info mb-0"><?php echo $total_musicas; ?></h3>
            </div>
            <i class="fa-solid fa-compact-disc fs-1 text-info opacity-75"></i>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="p-3 admin-card d-flex align-items-center justify-content-between">
            <div>
                <span class="text-light fw-bold small d-block">Notícias</span>
                <h3 class="font-orbitron fw-bold text-success mb-0"><?php echo $total_noticias; ?></h3>
            </div>
            <i class="fa-solid fa-newspaper fs-1 text-success opacity-75"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Quick Actions -->
    <div class="col-lg-6">
        <div class="p-4 admin-card">
            <h5 class="font-orbitron text-white mb-3"><i class="fa-solid fa-bolt text-warning me-2"></i> Ações Rápidas</h5>
            <div class="row g-2">
                <div class="col-6">
                    <a href="/admin/agenda.php?action=new" class="btn btn-dark w-100 p-3 text-start border border-secondary text-white rounded-3">
                        <i class="fa-solid fa-calendar-plus text-danger fs-4 d-block mb-2"></i>
                        <strong class="d-block">Cadastrar Evento</strong>
                        <small class="text-muted">Adicionar nova festa na agenda</small>
                    </a>
                </div>
                <div class="col-6">
                    <a href="/admin/albuns.php?action=new" class="btn btn-dark w-100 p-3 text-start border border-secondary text-white rounded-3">
                        <i class="fa-solid fa-folder-plus text-warning fs-4 d-block mb-2"></i>
                        <strong class="d-block">Criar Álbum</strong>
                        <small class="text-muted">Upload de fotos do evento</small>
                    </a>
                </div>
                <div class="col-6">
                    <a href="/admin/musicas.php?action=new" class="btn btn-dark w-100 p-3 text-start border border-secondary text-white rounded-3">
                        <i class="fa-solid fa-music text-info fs-4 d-block mb-2"></i>
                        <strong class="d-block">Adicionar Música</strong>
                        <small class="text-muted">Cadastrar áudios e sets</small>
                    </a>
                </div>
                <div class="col-6">
                    <a href="/admin/noticias.php?action=new" class="btn btn-dark w-100 p-3 text-start border border-secondary text-white rounded-3">
                        <i class="fa-solid fa-pen-nib text-success fs-4 d-block mb-2"></i>
                        <strong class="d-block">Publicar Notícia</strong>
                        <small class="text-muted">Escrever matéria no blog</small>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Contacts -->
    <div class="col-lg-6">
        <div class="p-4 admin-card">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-orbitron text-white mb-0"><i class="fa-solid fa-envelope text-primary me-2"></i> Mensagens Recebidas</h5>
                <a href="/admin/contatos.php" class="btn btn-sm btn-outline-secondary rounded-pill">Ver Todas</a>
            </div>

            <?php if (!empty($recent_contatos)): ?>
                <div class="list-group list-group-flush bg-transparent">
                    <?php foreach ($recent_contatos as $c): ?>
                        <div class="list-group-item bg-dark bg-opacity-50 text-white border-secondary border-opacity-25 p-3 rounded mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-info"><?php echo htmlspecialchars($c['contato_nome']); ?></strong>
                                <small class="text-muted"><?php echo htmlspecialchars($c['contato_telefone'] ?: ''); ?></small>
                            </div>
                            <p class="small text-muted mb-0 text-truncate"><?php echo htmlspecialchars($c['contato_mensagem']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted small py-3">Nenhuma mensagem recebida até o momento.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
