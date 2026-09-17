<?php
// admin/agenda.php - Manage Agenda & Events
include __DIR__ . '/header.php';

$msg = '';
$err = '';

// Delete handler
if (isset($_GET['del'])) {
    $del_id = intval($_GET['del']);
    $pdo->prepare("DELETE FROM agenda WHERE agenda_id = ?")->execute([$del_id]);
    $msg = "Evento excluído com sucesso!";
}

// Add / Edit Handler
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['agenda_id'] ?? 0);
    $title = trim($_POST['agenda_title'] ?? '');
    $data = trim($_POST['agenda_data'] ?? '');
    $hora = trim($_POST['agenda_hora'] ?? '');
    $local = trim($_POST['agenda_local'] ?? '');
    $atracao = trim($_POST['agenda_atracao'] ?? '');
    $producao = trim($_POST['agenda_producao'] ?? '');
    $info = trim($_POST['agenda_info'] ?? '');

    // Handle Upload
    $foto_url = $_POST['existing_foto_url'] ?? '';
    $uploaded = upload_file('foto_file', __DIR__ . '/../uploads/agenda');
    if ($uploaded) {
        $foto_url = $uploaded;
    }

    if (!empty($title)) {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE agenda SET agenda_title=?, agenda_data=?, agenda_hora=?, agenda_local=?, agenda_atracao=?, agenda_producao=?, agenda_info=?, foto_url=? WHERE agenda_id=?");
            $stmt->execute([$title, $data, $hora, $local, $atracao, $producao, $info, $foto_url, $id]);
            $msg = "Evento atualizado com sucesso!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO agenda (agenda_title, agenda_data, agenda_hora, agenda_local, agenda_atracao, agenda_producao, agenda_info, foto_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $data, $hora, $local, $atracao, $producao, $info, $foto_url]);
            $msg = "Novo evento cadastrado!";
        }
    } else {
        $err = "O título do evento é obrigatório.";
    }
}

// Fetch single for edit if requested
$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM agenda WHERE agenda_id = ?");
    $stmt->execute([$edit_id]);
    $edit_item = $stmt->fetch();
}

$agendas = $pdo->query("SELECT * FROM agenda ORDER BY agenda_data DESC")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="font-orbitron fw-bold text-white mb-1">Gerenciar Agenda de Eventos</h2>
        <p class="text-muted small mb-0">Cadastre e edite as festas e programações do Studio 1250.</p>
    </div>
    <a href="?action=new" class="btn btn-danger font-orbitron rounded-pill btn-sm"><i class="fa-solid fa-plus me-1"></i> Novo Evento</a>
</div>

<?php if(!empty($msg)): ?>
    <div class="alert alert-success py-2 small"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>
<?php if(!empty($err)): ?>
    <div class="alert alert-danger py-2 small"><?php echo htmlspecialchars($err); ?></div>
<?php endif; ?>

<!-- Form Section -->
<?php if (isset($_GET['action']) && $_GET['action'] == 'new' || $edit_item): ?>
    <div class="p-4 admin-card mb-5">
        <h5 class="font-orbitron text-white mb-3"><?php echo $edit_item ? 'Editar Evento' : 'Cadastrar Novo Evento'; ?></h5>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="agenda_id" value="<?php echo $edit_item['agenda_id'] ?? 0; ?>">
            <input type="hidden" name="existing_foto_url" value="<?php echo htmlspecialchars($edit_item['foto_url'] ?? ''); ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted">Título do Evento *</label>
                    <input type="text" name="agenda_title" class="form-control bg-dark border-secondary text-white" value="<?php echo htmlspecialchars($edit_item['agenda_title'] ?? ''); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Data do Evento</label>
                    <input type="date" name="agenda_data" class="form-control bg-dark border-secondary text-white" value="<?php echo !empty($edit_item['agenda_data']) ? date('Y-m-d', strtotime($edit_item['agenda_data'])) : date('Y-m-d'); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Horário</label>
                    <input type="text" name="agenda_hora" class="form-control bg-dark border-secondary text-white" value="<?php echo htmlspecialchars($edit_item['agenda_hora'] ?? '23:00'); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Local</label>
                    <input type="text" name="agenda_local" class="form-control bg-dark border-secondary text-white" value="<?php echo htmlspecialchars($edit_item['agenda_local'] ?? 'Studio 1250'); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Atração / DJs</label>
                    <input type="text" name="agenda_atracao" class="form-control bg-dark border-secondary text-white" value="<?php echo htmlspecialchars($edit_item['agenda_atracao'] ?? ''); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small text-muted">Produção</label>
                    <input type="text" name="agenda_producao" class="form-control bg-dark border-secondary text-white" value="<?php echo htmlspecialchars($edit_item['agenda_producao'] ?? 'Studio 1250'); ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label small text-muted">Cartaz / Banner (Imagem)</label>
                    <input type="file" name="foto_file" class="form-control bg-dark border-secondary text-white" accept="image/*">
                    <?php if(!empty($edit_item['foto_url'])): ?>
                        <small class="text-info d-block mt-1">Imagem atual: <?php echo htmlspecialchars($edit_item['foto_url']); ?></small>
                    <?php endif; ?>
                </div>
                <div class="col-md-12">
                    <label class="form-label small text-muted">Informações Adicionais / Descrição</label>
                    <textarea name="agenda_info" rows="3" class="form-control bg-dark border-secondary text-white"><?php echo htmlspecialchars($edit_item['agenda_info'] ?? ''); ?></textarea>
                </div>
                <div class="col-12 mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-danger font-orbitron rounded-pill px-4"><i class="fa-solid fa-floppy-disk me-1"></i> Salvar Evento</button>
                    <a href="/admin/agenda.php" class="btn btn-outline-secondary rounded-pill px-4">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>

<!-- Events List Table -->
<div class="p-4 admin-card">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr class="text-muted small">
                    <th>Imagem</th>
                    <th>Título</th>
                    <th>Data & Hora</th>
                    <th>Atração</th>
                    <th>Local</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agendas as $ag): ?>
                    <tr>
                        <td style="width: 70px;">
                            <?php $img = !empty($ag['foto_url']) ? "/uploads/agenda/".$ag['foto_url'] : "https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=200&auto=format&fit=crop"; ?>
                            <img src="<?php echo htmlspecialchars($img); ?>" class="rounded" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=200&auto=format&fit=crop'">
                        </td>
                        <td>
                            <strong class="text-white d-block"><?php echo htmlspecialchars($ag['agenda_title']); ?></strong>
                            <small class="text-muted"><?php echo htmlspecialchars($ag['agenda_producao'] ?: 'Studio 1250'); ?></small>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?php echo date('d/m/Y', strtotime($ag['agenda_data'] ?? 'now')); ?></span>
                            <small class="text-muted d-block"><?php echo htmlspecialchars($ag['agenda_hora'] ?: '23:00'); ?></small>
                        </td>
                        <td class="small text-muted"><?php echo htmlspecialchars($ag['agenda_atracao'] ?: 'DJs Residentes'); ?></td>
                        <td class="small text-muted"><?php echo htmlspecialchars($ag['agenda_local'] ?: 'Studio 1250'); ?></td>
                        <td class="text-end">
                            <a href="?edit=<?php echo $ag['agenda_id']; ?>" class="btn btn-sm btn-outline-info rounded-pill"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="?del=<?php echo $ag['agenda_id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este evento?');" class="btn btn-sm btn-outline-danger rounded-pill"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
