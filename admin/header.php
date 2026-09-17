<?php
// admin/header.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';
require_admin_auth();

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Studio 1250</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --admin-bg: #0d0f17;
            --admin-card: #161926;
            --admin-sidebar: #11131f;
            --neon-pink: #ff007f;
            --neon-cyan: #00f3ff;
        }
        body {
            background-color: var(--admin-bg);
            color: #e2e8f0;
            font-family: 'Inter', sans-serif;
        }
        .admin-sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: var(--admin-sidebar);
            border-right: 1px solid rgba(255,255,255,0.08);
        }
        .admin-sidebar .nav-link {
            color: #cbd5e1;
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .admin-sidebar .nav-link:hover, .admin-sidebar .nav-link.active {
            color: #fff;
            background: linear-gradient(90deg, rgba(255,0,127,0.2) 0%, rgba(0,243,255,0.1) 100%);
            border-left: 4px solid var(--neon-pink);
        }
        .admin-card {
            background-color: var(--admin-card);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 12px;
        }
        .text-muted, small, .small {
            color: #cbd5e1 !important;
        }
        .font-orbitron { font-family: 'Orbitron', sans-serif; }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar Navigation -->
    <aside class="admin-sidebar p-3 d-flex flex-column flex-shrink-0">
        <a href="/admin/index.php" class="d-flex align-items-center text-white text-decoration-none mb-4 p-2">
            <span class="badge bg-danger me-2 font-orbitron">1250</span>
            <span class="fs-5 fw-bold font-orbitron">CMS ADMIN</span>
        </a>

        <ul class="nav nav-pills flex-column mb-auto">
            <li>
                <a href="/admin/index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-chart-pie me-2 text-primary"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="/admin/agenda.php" class="nav-link <?php echo ($current_page == 'agenda.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-calendar-days me-2 text-danger"></i> Agenda / Festas
                </a>
            </li>
            <li>
                <a href="/admin/albuns.php" class="nav-link <?php echo (in_array($current_page, ['albuns.php', 'fotos.php'])) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-images me-2 text-warning"></i> Álbuns & Fotos
                </a>
            </li>
            <li>
                <a href="/admin/musicas.php" class="nav-link <?php echo ($current_page == 'musicas.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-compact-disc me-2 text-info"></i> Músicas & Sets
                </a>
            </li>
            <li>
                <a href="/admin/noticias.php" class="nav-link <?php echo ($current_page == 'noticias.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-newspaper me-2 text-success"></i> Notícias
                </a>
            </li>
            <li>
                <a href="/admin/videos.php" class="nav-link <?php echo ($current_page == 'videos.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-film me-2 text-neon-pink"></i> Vídeos YouTube
                </a>
            </li>
            <li>
                <a href="/admin/banners.php" class="nav-link <?php echo ($current_page == 'banners.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-sliders me-2 text-warning"></i> Banners Slider
                </a>
            </li>
            <li>
                <a href="/admin/contatos.php" class="nav-link <?php echo ($current_page == 'contatos.php') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-envelope me-2 text-primary"></i> Mensagens
                </a>
            </li>
        </ul>

        <hr class="border-secondary border-opacity-25 my-3">

        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-circle-user fs-4 me-2 text-info"></i>
                <strong><?php echo htmlspecialchars($_SESSION['admin_user_name'] ?? 'Admin'); ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser">
                <li><a class="dropdown-item" href="/index.php" target="_blank"><i class="fa-solid fa-globe me-2"></i> Ver Site</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="/admin/logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Sair</a></li>
            </ul>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <main class="flex-grow-1 p-4 overflow-auto">
