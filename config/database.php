<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'minimag_db';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Initialize database
            $this->initializeDatabase();
            
        } catch(PDOException $exception) {
            // If database doesn't exist, create it
            if ($exception->getCode() == 1049) {
                $this->createDatabase();
            } else {
                error_log("Connection error: " . $exception->getMessage());
            }
        }
        return $this->conn;
    }

    private function createDatabase() {
        try {
            // Connect without database selected
            $temp_conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
            $temp_conn->exec("CREATE DATABASE IF NOT EXISTS " . $this->db_name);
            $temp_conn = null;
            
            // Reconnect with database
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $this->initializeDatabase();
            
        } catch(PDOException $exception) {
            error_log("Database creation failed: " . $exception->getMessage());
        }
    }

    private function initializeDatabase() {
        // Create tables
        $tables = [
            "CREATE TABLE IF NOT EXISTS articles (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                excerpt TEXT,
                content LONGTEXT,
                image_url VARCHAR(500),
                category VARCHAR(100),
                reading_time INT,
                publication_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                status VARCHAR(20) DEFAULT 'published'
            ) ENGINE=InnoDB",
            
            "CREATE TABLE IF NOT EXISTS likes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                article_id INT,
                ip_address VARCHAR(45),
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_like (article_id, ip_address)
            ) ENGINE=InnoDB",
            
            "CREATE TABLE IF NOT EXISTS comments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                article_id INT,
                author_name VARCHAR(255),
                author_email VARCHAR(255),
                content TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                status VARCHAR(20) DEFAULT 'approved'
            ) ENGINE=InnoDB"
        ];

        foreach ($tables as $table) {
            $this->conn->exec($table);
        }

        // Insert sample data if no articles
        $stmt = $this->conn->query("SELECT COUNT(*) FROM articles");
        if ($stmt->fetchColumn() == 0) {
            $this->insertSampleData();
        }
    }

    private function insertSampleData() {
        $articles = [
            [
                'title' => 'Créez votre coin créatif',
                'excerpt' => 'Des astuces simples pour rendre votre bureau plus joyeux et productif.',
                'content' => '<h2>Transformez votre espace de travail</h2><p>Créez un environnement inspirant pour booster votre créativité. Organisez votre espace avec des éléments qui vous motivent et vous inspirent.</p><h3>Conseils pratiques</h3><ul><li>Choisissez un éclairage adapté</li><li>Ajoutez des plantes vertes</li><li>Personnalisez votre décoration</li></ul>',
                'image_url' => 'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=800&h=600&fit=crop',
                'category' => 'Design',
                'reading_time' => 5
            ],
            [
                'title' => 'Découvrez le bonheur simple', 
                'excerpt' => 'Apprenez à apprécier les petites choses de la vie quotidienne.',
                'content' => '<h2>Le bonheur dans les détails</h2><p>Redécouvrez la beauté des moments simples du quotidien. Le bonheur se cache souvent là où on ne l\'attend pas.</p><h3>Exercices pratiques</h3><ul><li>Tenir un journal de gratitude</li><li>Pratiquer la pleine conscience</li><li>Prendre le temps de respirer</li></ul>',
                'image_url' => 'https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=800&h=600&fit=crop',
                'category' => 'Lifestyle',
                'reading_time' => 4
            ],
            [
                'title' => 'Snacks créatifs en 10min',
                'excerpt' => 'Des idées de snacks rapides, sains et délicieux.',
                'content' => '<h2>Snacks express et healthy</h2><p>Des recettes simples et rapides pour vos petites faims. Parfait pour les pauses café ou les envies sucrées.</p><h3>Recettes favorites</h3><ul><li>Toast à l\'avocat et graines</li><li>Yogourt aux fruits et granola</li><li>Boules d\'énergie maison</li></ul>',
                'image_url' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=800&h=600&fit=crop',
                'category' => 'Food',
                'reading_time' => 3
            ]
        ];

        $stmt = $this->conn->prepare("
            INSERT INTO articles (title, excerpt, content, image_url, category, reading_time) 
            VALUES (:title, :excerpt, :content, :image_url, :category, :reading_time)
        ");

        foreach ($articles as $article) {
            $stmt->execute($article);
        }
    }
}
?>