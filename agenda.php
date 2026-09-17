<?php
// agenda.php - Events & Parties Page
require_once __DIR__ . '/config/db.php';

$search = trim($_GET['search'] ?? '');
$filter = trim($_GET['filter'] ?? 'all');

$query = "SELECT * FROM agenda WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (agenda_title LIKE ? OR agenda_atracao LIKE ? OR agenda_producao LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$query .= " ORDER BY agenda_data DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$agendas = $stmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <!-- Header Banner -->
    <div class="text-center mb-5">
        <span class="badge bg-neon-pink text-white uppercase tracking-wider mb-2 px-3 py-2 fs-6">PROGRAMAÇÃO OFICIAL</span>
        <h1 class="display-5 fw-bold font-orbitron text-white">AGENDA DE EVENTOS & FESTAS</h1>
        <p class="text-muted max-w-2xl mx-auto">Confira todas as atrações, horários, DJs convidados e produções do Studio 1250.</p>
    </div>

    <!-- Filter & Search Bar -->
    <div class="p-3 bg-card border border-secondary border-opacity-25 rounded-4 mb-5">
        <form method="GET" action="/agenda.php" class="row g-3 align-items-center">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary border-opacity-25 text-neon-cyan"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-dark border-secondary border-opacity-25 text-white" placeholder="Buscar por título, DJ ou atração..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-neon-pink fw-bold w-100 rounded-pill"><i class="fa-solid fa-filter me-1"></i> Filtrar Eventos</button>
                <?php if (!empty($search)): ?>
                    <a href="/agenda.php" class="btn btn-outline-secondary rounded-pill"><i class="fa-solid fa-xmark"></i></a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Event Cards Grid -->
    <?php if (!empty($agendas)): ?>
        <div class="row g-4">
            <?php foreach ($agendas as $ag): ?>
                <div class="col-lg-4 col-md-6" id="event-<?php echo $ag['agenda_id']; ?>">
                    <div class="card card-studio h-100">
                        <div class="card-img-wrapper">
                            <?php
                                $img_src = !empty($ag['foto_url']) ? "/uploads/agenda/" . $ag['foto_url'] : "https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=600&auto=format&fit=crop";
                            ?>
                            <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($ag['agenda_title']); ?>" onerror="this.src='https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=600&auto=format&fit=crop'">
                            <span class="card-badge"><i class="fa-solid fa-clock text-neon-cyan me-1"></i><?php echo htmlspecialchars($ag['agenda_hora'] ?: '23:00'); ?></span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-dark border border-neon-pink text-neon-pink fw-bold">
                                    <i class="fa-solid fa-calendar me-1"></i><?php echo date('d/m/Y', strtotime($ag['agenda_data'] ?? 'now')); ?>
                                </span>
                                <small class="text-muted"><i class="fa-solid fa-building me-1 text-neon-cyan"></i><?php echo htmlspecialchars($ag['agenda_local'] ?: 'Studio 1250'); ?></small>
                            </div>
                            <h4 class="card-title font-orbitron text-white fs-5 mb-2"><?php echo htmlspecialchars($ag['agenda_title']); ?></h4>

                            <div class="bg-dark bg-opacity-50 p-2 rounded mb-3 small text-muted">
                                <div><strong class="text-light">Atração:</strong> <?php echo htmlspecialchars($ag['agenda_atracao'] ?: 'DJs Residentes'); ?></div>
                                <?php if (!empty($ag['agenda_producao'])): ?>
                                    <div><strong class="text-light">Produção:</strong> <?php echo htmlspecialchars($ag['agenda_producao']); ?></div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($ag['agenda_info'])): ?>
                                <p class="card-text text-muted small flex-grow-1"><?php echo nl2br(htmlspecialchars($ag['agenda_info'])); ?></p>
                            <?php endif; ?>

                            <button class="btn btn-sm btn-outline-neon rounded-pill mt-auto w-100" data-bs-toggle="modal" data-bs-target="#modalEvent<?php echo $ag['agenda_id']; ?>">
                                <i class="fa-solid fa-ticket me-1"></i> Ver Cartaz & Info
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal for Event Detail -->
                <div class="modal fade" id="modalEvent<?php echo $ag['agenda_id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content bg-card text-white border-secondary">
                            <div class="modal-header border-secondary border-opacity-25">
                                <h5 class="modal-title font-orbitron text-neon-pink"><i class="fa-solid fa-compact-disc me-2"></i><?php echo htmlspecialchars($ag['agenda_title']); ?></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <img src="<?php echo htmlspecialchars($img_src); ?>" class="img-fluid rounded border border-secondary" alt="Cartaz" onerror="this.src='https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=600&auto=format&fit=crop'">
                                    </div>
                                    <div class="col-md-6 d-flex flex-column justify-content-center">
                                        <h4 class="font-orbitron text-white mb-3"><?php echo htmlspecialchars($ag['agenda_title']); ?></h4>
                                        <ul class="list-unstyled mb-3">
                                            <li class="mb-2"><i class="fa-solid fa-calendar text-neon-pink me-2"></i> Data: <strong><?php echo date('d/m/Y', strtotime($ag['agenda_data'] ?? 'now')); ?></strong></li>
                                            <li class="mb-2"><i class="fa-solid fa-clock text-neon-cyan me-2"></i> Horário: <strong><?php echo htmlspecialchars($ag['agenda_hora'] ?: '23:00'); ?></strong></li>
                                            <li class="mb-2"><i class="fa-solid fa-location-dot text-danger me-2"></i> Local: <strong><?php echo htmlspecialchars($ag['agenda_local'] ?: 'Studio 1250'); ?></strong></li>
                                            <li class="mb-2"><i class="fa-solid fa-microphone text-warning me-2"></i> Atrações: <strong><?php echo htmlspecialchars($ag['agenda_atracao'] ?: 'DJs Studio 1250'); ?></strong></li>
                                            <li class="mb-2"><li class="fa-solid fa-star text-info me-2"></i> Produção: <strong><?php echo htmlspecialchars($ag['agenda_producao'] ?: 'Studio 1250'); ?></strong></li>
                                        </ul>
                                        <?php if (!empty($ag['agenda_info'])): ?>
                                            <div class="p-3 bg-dark rounded border border-secondary text-muted small mb-3">
                                                <?php echo nl2br(htmlspecialchars($ag['agenda_info'])); ?>
                                            </div>
                                        <?php endif; ?>
                                        <a href="/contato.php" class="btn btn-neon-pink rounded-pill fw-bold"><i class="fa-solid fa-envelope me-2"></i> Reservar Ingressos / Camarotes</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fa-solid fa-calendar-xmark text-muted fs-1 mb-3"></i>
            <h4 class="text-white">Nenhum evento encontrado</h4>
            <p class="text-muted">Tente ajustar a busca ou limpar o filtro.</p>
            <a href="/agenda.php" class="btn btn-outline-neon rounded-pill">Ver Todos os Eventos</a>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
