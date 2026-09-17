<?php
// ajax_contact.php - Handles AJAX Contact Form Submission
header('Content-Type: application/json');
require_once __DIR__ . '/config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$mensagem = trim($_POST['mensagem'] ?? '');

if (empty($nome) || empty($email) || empty($mensagem)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos obrigatórios (*).']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'O endereço de e-mail informado é inválido.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO contato (contato_nome, contato_cidade, contato_email, contato_telefone, contato_mensagem) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $cidade, $email, $telefone, $mensagem]);

    echo json_encode(['success' => true, 'message' => 'Sua mensagem foi enviada com sucesso! Entraremos em contato em breve.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar mensagem no banco de dados.']);
}
