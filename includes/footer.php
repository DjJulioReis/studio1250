<!-- Footer -->
<footer class="footer mt-5 pt-5 pb-3 border-top border-secondary border-opacity-25 bg-black text-light">
    <div class="container">
        <div class="row g-4 mb-4">
            <!-- Brand & Info -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <span class="neon-badge me-2">1250</span>
                    <span class="fw-bold fs-4 text-white font-orbitron">STUDIO<span class="text-neon-pink">1250</span></span>
                </div>
                <p class="text-muted small">O mais tradicional templo da música flashback, dance, rock e underground de Curitiba e região. Reviva os melhores momentos com som de altíssima qualidade.</p>
                <div class="social-links d-flex gap-2 mt-3">
                    <a href="#" class="social-btn"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="social-btn"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#" class="social-btn"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="font-orbitron text-neon-cyan fs-6 mb-3 text-uppercase">Navegação</h5>
                <ul class="list-unstyled footer-links small">
                    <li><a href="/index.php"><i class="fa-solid fa-chevron-right me-1 text-neon-pink"></i> Início</a></li>
                    <li><a href="/agenda.php"><i class="fa-solid fa-chevron-right me-1 text-neon-pink"></i> Agenda & Festas</a></li>
                    <li><a href="/galeria.php"><i class="fa-solid fa-chevron-right me-1 text-neon-pink"></i> Fotos de Eventos</a></li>
                    <li><a href="/musicas.php"><i class="fa-solid fa-chevron-right me-1 text-neon-pink"></i> Setlists & Músicas</a></li>
                    <li><a href="/videos.php"><i class="fa-solid fa-chevron-right me-1 text-neon-pink"></i> Vídeos & Clips</a></li>
                    <li><a href="/noticias.php"><i class="fa-solid fa-chevron-right me-1 text-neon-pink"></i> Notícias</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h5 class="font-orbitron text-warning fs-6 mb-3 text-uppercase">Informações</h5>
                <ul class="list-unstyled text-muted small">
                    <li class="mb-2"><i class="fa-solid fa-location-dot text-danger me-2"></i> Av. Marechal Mascarenhas de Moraes, 901</li>
                    <li class="mb-2"><i class="fa-solid fa-phone text-success me-2"></i> (41) 3257-1250 / 99624-4035</li>
                    <li class="mb-2"><i class="fa-solid fa-envelope text-info me-2"></i> contato@studio1250.com.br</li>
                    <li class="mb-2"><i class="fa-solid fa-clock text-warning me-2"></i> Sextas e Sábados a partir das 22:00</li>
                </ul>
            </div>

            <!-- Floating Mini Player Widget -->
            <div class="col-lg-3 col-md-6">
                <h5 class="font-orbitron text-neon-pink fs-6 mb-3 text-uppercase">Player Rápido</h5>
                <div class="mini-player-card p-3 rounded border border-secondary border-opacity-25 bg-dark">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fa-solid fa-compact-disc fa-spin text-neon-cyan fs-3 me-2"></i>
                        <div>
                            <div class="fw-bold small text-truncate" id="miniPlayerTitle">Radio Studio 1250</div>
                            <small class="text-muted d-block" id="miniPlayerArtist">O melhor dos anos 80, 90 & Rock</small>
                        </div>
                    </div>
                    <audio id="globalAudioPlayer" src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3" preload="none"></audio>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <button class="btn btn-sm btn-neon-pink rounded-circle" id="btnMiniPlay"><i class="fa-solid fa-play"></i></button>
                        <div class="flex-grow-1 mx-2">
                            <div class="progress" style="height: 6px; cursor: pointer;" id="miniPlayerProgress">
                                <div class="progress-bar bg-neon-cyan" role="progressbar" style="width: 0%" id="miniPlayerProgressBar"></div>
                            </div>
                        </div>
                        <i class="fa-solid fa-volume-high text-muted fs-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-secondary border-opacity-25 my-3">

        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between small text-muted">
            <p class="mb-2 mb-md-0">&copy; <?php echo date('Y'); ?> Studio 1250. Todos os direitos reservados.</p>
            <div>
                <a href="/admin/login.php" class="text-muted text-decoration-none me-3"><i class="fa-solid fa-lock me-1"></i> Área Restrita</a>
                <a href="#top" class="text-neon-cyan text-decoration-none back-to-top"><i class="fa-solid fa-arrow-up me-1"></i> Voltar ao Topo</a>
            </div>
        </div>
    </div>
</footer>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Slick Carousel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<!-- Fancybox Gallery -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<!-- Custom JS -->
<script src="/assets/js/main.js"></script>

</body>
</html>
