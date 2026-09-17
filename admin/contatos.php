<?php
// admin/contatos.php - Manage Received Contact Messages
include __DIR__ . '/header.php';

$msg = '';

if (isset($_GET['del'])) {
    $del_id = intval($_GET['del']);
    $pdo->prepare("DELETE FROM contato WHERE contato_id = ?")->execute([$del_id]);
    $msg = "Mensagem removida!";
}

$contatos = $pdo->query("SELECT * FROM contato ORDER BY contato_id DESC")->fetchAll();
?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="font-orbitron fw-bold text-white mb-1">Mensagens de Contato Recebidas</h2>
        <p class="text-muted small mb-0">Consulte os recados e dúvidas enviados pelos clientes no formulário de contato.</p>
    </div>
    <span class="badge bg-primary fs-6 font-orbitron"><?php echo count($contatos); ?> mensagens</span>
</div>

<?php if(!empty($msg)): ?>
    <div class="alert alert-success py-2 small"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>

<div class="p-4 admin-card">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr class="text-muted small">
                    <th>Data</th>
                    <th>Nome / Cidade</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Mensagem</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contatos as $c): ?>
                    <tr>
                        <td class="small text-muted" style="white-space: nowrap;">
                            <?php echo !empty($c['contato_data']) ? date('d/m/Y H:i', strtotime($c['contato_data'])) : '-'; ?>
                        </td>
                        <td>
                            <strong class="text-white d-block"><?php echo htmlspecialchars($c['contato_nome']); ?></strong>
                            <small class="text-muted"><?php echo htmlspecialchars($c['contato_cidade'] ?: '-'); ?></small>
                        </td>
                        <td class="small text-info"><?php echo htmlspecialchars($c['contato_email']); ?></td>
                        <td class="small text-muted"><?php echo htmlspecialchars($c['contato_telefone'] ?: '-'); ?></td>
                        <td class="small text-light" style="max-width: 300px;">
                            <?php echo nl2br(htmlspecialchars($c['contato_mensagem'])); ?>
                        </td>
                        <td class="text-end">
                            <a href="mailto:<?php echo htmlspecialchars($c['contato_email']); ?>" class="btn btn-sm btn-outline-info rounded-pill" title="Responder por e-mail"><i class="fa-solid fa-reply"></i></a>
                            <a href="?del=<?php echo $c['contato_id']; ?>" onclick="return confirm('Excluir esta mensagem?');" class="btn btn-sm btn-outline-danger rounded-pill"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($contatos)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Nenhuma mensagem recebida ainda.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
