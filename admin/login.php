<?php
// admin/login.php - Admin Authentication Page
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';

if (is_admin_logged_in()) {
    header("Location: /admin/index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($login) && !empty($password)) {
        // Find user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_login = ? OR user_email = ?");
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch();

        if ($user) {
            // Verify password - check standard password_verify OR md5 fallback for original dump
            $pass_valid = false;
            if (password_verify($password, $user['user_password'])) {
                $pass_valid = true;
            } elseif (md5($password) === strtolower($user['user_password'])) {
                $pass_valid = true;
            }

            if ($pass_valid) {
                $_SESSION['admin_user_id'] = $user['user_id'];
                $_SESSION['admin_user_name'] = $user['user_login'];
                header("Location: /admin/index.php");
                exit;
            } else {
                $error = 'Senha incorreta. Tente novamente.';
            }
        } else {
            $error = 'Usuário não encontrado.';
        }
    } else {
        $error = 'Preencha usuário e senha.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Painel Admin Studio 1250</title>

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #0a0a12 0%, #171128 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(22, 25, 38, 0.95);
            border: 1px solid rgba(255, 0, 127, 0.3);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
        }
        .font-orbitron { font-family: 'Orbitron', sans-serif; }
    </style>
</head>
<body>

<div class="login-card p-4 p-md-5 text-white">
    <div class="text-center mb-4">
        <span class="badge bg-danger fs-6 font-orbitron mb-2">STUDIO 1250</span>
        <h3 class="font-orbitron fw-bold">Painel de Controle</h3>
        <p class="text-muted small">Digite suas credenciais de administrador</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger py-2 small mb-4"><i class="fa-solid fa-triangle-exclamation me-2"></i><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label small fw-bold text-muted">Usuário ou E-mail</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-light"><i class="fa-solid fa-user"></i></span>
                <input type="text" name="login" class="form-control bg-dark border-secondary text-white" placeholder="admin" required autofocus>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold text-muted">Senha</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-light"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password" class="form-control bg-dark border-secondary text-white" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn btn-danger w-100 py-2 font-orbitron fw-bold rounded-pill mb-3">
            <i class="fa-solid fa-right-to-bracket me-2"></i> Entrar no Painel
        </button>

        <div class="text-center">
            <a href="/index.php" class="text-muted small text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i> Voltar para o site principal</a>
        </div>
    </form>
</div>

</body>
</html>
