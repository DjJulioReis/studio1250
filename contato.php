<?php
// contato.php - Contact Page
require_once __DIR__ . '/config/db.php';

include __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <span class="badge bg-neon-pink text-white uppercase tracking-wider mb-2 px-3 py-2 fs-6">FALE CONOSCO</span>
        <h1 class="display-5 fw-bold font-orbitron text-white">CONTATO & LOCALIZAÇÃO</h1>
        <p class="text-muted max-w-2xl mx-auto">Tire dúvidas, faça reservas de camarotes, envie sugestões ou solicite orçamentos para eventos fechados.</p>
    </div>

    <div class="row g-4">
        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="p-4 p-md-5 rounded-4 bg-card border border-secondary border-opacity-25 h-100">
                <h4 class="font-orbitron text-white mb-4"><i class="fa-solid fa-paper-plane text-neon-pink me-2"></i> Envie uma Mensagem</h4>

                <div id="contactAlert" class="alert d-none" role="alert"></div>

                <form id="contactForm" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-light small fw-bold">Seu Nome *</label>
                            <input type="text" name="nome" class="form-control bg-dark border-secondary text-white" placeholder="Ex: João da Silva" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light small fw-bold">Cidade / Estado</label>
                            <input type="text" name="cidade" class="form-control bg-dark border-secondary text-white" placeholder="Ex: Curitiba / PR">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light small fw-bold">E-mail *</label>
                            <input type="email" name="email" class="form-control bg-dark border-secondary text-white" placeholder="seuemail@exemplo.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light small fw-bold">Telefone / WhatsApp</label>
                            <input type="text" name="telefone" class="form-control bg-dark border-secondary text-white" placeholder="(41) 99999-9999">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-light small fw-bold">Sua Mensagem *</label>
                            <textarea name="mensagem" rows="5" class="form-control bg-dark border-secondary text-white" placeholder="Escreva aqui detalhes sobre sua dúvida, reserva ou evento..." required></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-neon-pink fw-bold w-100 py-3 rounded-pill"><i class="fa-solid fa-paper-plane me-2"></i> Enviar Mensagem</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info & Map Card -->
        <div class="col-lg-5">
            <div class="p-4 p-md-5 rounded-4 bg-card border border-secondary border-opacity-25 h-100 d-flex flex-column justify-content-between">
                <div>
                    <h4 class="font-orbitron text-white mb-4"><i class="fa-solid fa-location-dot text-neon-cyan me-2"></i> Informações do Studio 1250</h4>
                    <ul class="list-unstyled text-light mb-4">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fa-solid fa-map-pin text-danger fs-5 me-3 mt-1"></i>
                            <div>
                                <strong class="d-block text-white">Endereço</strong>
                                <span class="text-muted small">Av. Marechal Mascarenhas de Moraes, 901<br>Atuba - Curitiba / PR</span>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fa-solid fa-phone text-success fs-5 me-3 mt-1"></i>
                            <div>
                                <strong class="d-block text-white">Telefones & WhatsApp</strong>
                                <span class="text-muted small">(41) 3257-1250 / (41) 99624-4035</span>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fa-solid fa-envelope text-info fs-5 me-3 mt-1"></i>
                            <div>
                                <strong class="d-block text-white">E-mail Oficial</strong>
                                <span class="text-muted small">contato@studio1250.com.br</span>
                            </div>
                        </li>
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fa-solid fa-clock text-warning fs-5 me-3 mt-1"></i>
                            <div>
                                <strong class="d-block text-white">Horário de Funcionamento</strong>
                                <span class="text-muted small">Sextas e Sábados das 22h às 05h</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Interactive Google Map Embed -->
                <div class="rounded-3 overflow-hidden border border-secondary border-opacity-25 mt-3">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3604.288591873722!2d-49.22271878498651!3d-25.395155683806294!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94dce54bcfa134d1%3A0xb5b796d11a7e1c8d!2sAv.%20Marechal%20Mascarenhas%20de%20Moraes%2C%20901%20-%20Atuba%2C%20Curitiba%20-%20PR!5e0!3m2!1spt-BR!2sbr!4v1620000000000!5m2!1spt-BR!2sbr" width="100%" height="220" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
