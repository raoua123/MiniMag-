<?php
header('Content-Type: application/json');
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT * FROM articles WHERE status = 'published' ORDER BY publication_date DESC";
$stmt = $db->prepare($query);
$stmt->execute();

$articles = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $articles[] = $row;
}

echo json_encode($articles);
?>