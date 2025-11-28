<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

include_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid method']);
    exit;
}

$article_id = isset($_POST['article_id']) ? (int)$_POST['article_id'] : 0;

if ($article_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid article_id']);
    exit;
}

try {
    $database = new Database();
    $db = $database->getConnection();

    // Exemple simple: un like = une ligne
    $stmt = $db->prepare("INSERT INTO likes (article_id, created_at) VALUES (:article_id, NOW())");
    $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stmt->execute();

    // Récupérer le nouveau total
    $countStmt = $db->prepare("SELECT COUNT(*) AS like_count FROM likes WHERE article_id = :article_id");
    $countStmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $countStmt->execute();
    $row = $countStmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'like_count' => (int)$row['like_count']
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
