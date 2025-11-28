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

$article_id  = isset($_POST['article_id']) ? (int)$_POST['article_id'] : 0;
$author_name = trim($_POST['author_name'] ?? '');
$comment     = trim($_POST['comment'] ?? '');

if ($article_id <= 0 || $author_name === '' || $comment === '') {
    echo json_encode(['success' => false, 'error' => 'Missing fields']);
    exit;
}

try {
    $database = new Database();
    $db = $database->getConnection();

    $stmt = $db->prepare("
        INSERT INTO comments (article_id, author_name, content, status, created_at)
        VALUES (:article_id, :author_name, :content, 'approved', NOW())
    ");
    $stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $stmt->bindParam(':author_name', $author_name);
    $stmt->bindParam(':content', $comment);
    $stmt->execute();

    // Nouveau total de commentaires approuvés
    $countStmt = $db->prepare("
        SELECT COUNT(*) AS comment_count
        FROM comments
        WHERE article_id = :article_id AND status = 'approved'
    ");
    $countStmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
    $countStmt->execute();
    $row = $countStmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'comment_count' => (int)$row['comment_count']
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
