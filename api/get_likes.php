<?php
include_once 'config/database.php';

if (isset($_GET['article_id'])) {
    header('Content-Type: application/json');
    
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        if ($db) {
            $article_id = $_GET['article_id'];
            
            $stmt = $db->prepare("SELECT COUNT(*) as like_count FROM likes WHERE article_id = :article_id");
            $stmt->bindParam(':article_id', $article_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'like_count' => $result['like_count']
            ]);
        } else {
            throw new Exception('Database connection failed');
        }
        
    } catch(Exception $exception) {
        echo json_encode(['success' => false, 'error' => $exception->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No article ID provided']);
}
?>