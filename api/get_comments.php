<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

include_once '../config/database.php';

$article_id = isset($_GET['article_id']) ? (int)$_GET['article_id'] : 0;

if ($article_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid article_id']);
    exit;
}

try {
    $database = new Database();
    $db = $database->getConnection();

    $stmt = $db->prepare("
        SELECT id, author_name, content, created_at
        FROM comments
        WHERE article_id = :article_id AND status = 'approved'
        ORDER BY created_at DESC
    ");
    $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'comments' => $comments
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
