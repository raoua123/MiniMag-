<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MiniMag — Magazine en ligne mignon</title>
  <meta name="description" content="MiniMag - un mini magazine coloré, interactif et responsive avec navigation type magazine (slide) et page-article en spread." />
  <meta name="author" content="MiniMag" />
  <meta property="og:title" content="MiniMag — Magazine en ligne mignon" />
  <meta property="og:description" content="MiniMag - un mini magazine coloré, interactif et responsive" />
  <meta property="og:type" content="website" />
  <style>
    :root {
      --p1: #FF6B6B;
      --p2: #FF8E8E;
      --bg1: #f8f9fa;
      --bg2: #e9ecef;
      --muted: #6c757d;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, var(--bg1), var(--bg2));
      min-height: 100vh;
    }

    /* Loader */
    .loader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: white;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      transition: opacity 0.5s;
    }

    .loader-content {
      text-align: center;
    }

    .loader-title {
      font-family: 'Playfair Display', serif;
      font-size: 3rem;
      margin-bottom: 2rem;
      background: linear-gradient(135deg, var(--p1), var(--p2));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .loader-dots {
      display: flex;
      gap: 10px;
      justify-content: center;
    }

    .dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: var(--p1);
      animation: bounce 1.4s infinite ease-in-out;
    }

    .dot:nth-child(1) { animation-delay: -0.32s; }
    .dot:nth-child(2) { animation-delay: -0.16s; }

    @keyframes bounce {
      0%, 80%, 100% { transform: scale(0); }
      40% { transform: scale(1); }
    }

    /* Header */
    .mag-header {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 20px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .mag-logo {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .mark {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, var(--p1), var(--p2));
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 800;
      font-size: 1.2rem;
    }

    .mag-logo h1 {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      background: linear-gradient(135deg, var(--p1), var(--p2));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .mag-nav {
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }

    .mag-nav a {
      text-decoration: none;
      color: var(--muted);
      font-weight: 600;
      transition: color 0.3s;
    }

    .mag-nav a:hover {
      color: var(--p1);
    }

    /* Search Bar */
    .search-container {
      position: relative;
      display: flex;
      align-items: center;
    }

    .search-bar {
      padding: 0.5rem 1rem;
      padding-left: 2.5rem;
      border: 2px solid #e0e0e0;
      border-radius: 25px;
      font-size: 0.9rem;
      width: 200px;
      transition: all 0.3s;
      background: #f8f9fa;
    }

    .search-bar:focus {
      outline: none;
      border-color: var(--p1);
      width: 250px;
      background: white;
    }

    .search-icon {
      position: absolute;
      left: 12px;
      color: var(--muted);
    }

    /* Category Filter */
    .category-filter {
      padding: 0.5rem 1rem;
      border: 2px solid #e0e0e0;
      border-radius: 25px;
      font-size: 0.9rem;
      background: #f8f9fa;
      cursor: pointer;
      transition: all 0.3s;
    }

    .category-filter:focus {
      outline: none;
      border-color: var(--p1);
      background: white;
    }

    /* Profile Icon */
    .profile-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--p1), var(--p2));
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      text-decoration: none;
    }

    .profile-icon:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(255,107,107,0.3);
    }

    /* Main Content */
    .mag-main {
      padding: 2rem;
    }

    .page-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .home-page {
      position: relative;
    }

    .home-wrap {
      background: white;
      border-radius: 24px;
      padding: 3rem;
      box-shadow: 0 10px 40px rgba(0,0,0,0.1);
      position: relative;
      overflow: hidden;
    }

    .home-title {
      font-family: 'Playfair Display', serif;
      font-size: 3.5rem;
      text-align: center;
      margin-bottom: 1rem;
      background: linear-gradient(135deg, var(--p1), var(--p2));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .home-subtitle {
      text-align: center;
      font-size: 1.2rem;
      color: var(--muted);
      margin-bottom: 3rem;
    }

    /* Article Grid */
    .mag-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 2rem;
      margin: 3rem 0;
    }

    .article {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
      border: 1px solid #f0f0f0;
    }

    .article:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    }

    .article a {
      text-decoration: none;
      color: inherit;
      display: block;
    }

    .media {
      position: relative;
      height: 200px;
      overflow: hidden;
    }

    .media img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .article:hover .media img {
      transform: scale(1.05);
    }

    .badge {
      position: absolute;
      top: 1rem;
      left: 1rem;
      background: linear-gradient(135deg, var(--p1), var(--p2));
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
    }

    .content {
      padding: 1.5rem;
    }

    .kicker {
      color: var(--p1);
      font-size: 0.9rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 0.5rem;
    }

    .title {
      font-family: 'Playfair Display', serif;
      font-size: 1.4rem;
      font-weight: 700;
      line-height: 1.3;
      margin-bottom: 0.75rem;
      color: #2d3748;
    }

    .excerpt {
      color: var(--muted);
      line-height: 1.5;
      margin-bottom: 1rem;
    }

    .meta {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--muted);
      font-size: 0.9rem;
    }

    /* Carousel Styles */
    .carousel-section {
      margin: 2rem 0;
    }

    .carousel-container {
      position: relative;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      background: #f8f9fa;
    }

    .carousel {
      position: relative;
      overflow: hidden;
      border-radius: 16px;
      height: 400px;
    }

    .carousel-track {
      display: flex;
      transition: transform 0.5s ease;
      height: 100%;
    }

    .carousel-slide {
      min-width: 100%;
      position: relative;
      height: 100%;
      flex-shrink: 0;
    }

    .carousel-slide.active {
      display: block;
    }

    .carousel-slide img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .carousel-caption {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(transparent, rgba(0,0,0,0.7));
      color: white;
      padding: 2rem;
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem;
    }

    .carousel-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(255,255,255,0.9);
      border: none;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      font-size: 1.5rem;
      cursor: pointer;
      z-index: 10;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .carousel-btn:hover {
      background: white;
      transform: translateY(-50%) scale(1.1);
    }

    .carousel-btn.prev { left: 20px; }
    .carousel-btn.next { right: 20px; }

    .carousel-dots {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 10px;
      z-index: 10;
    }

    .carousel-dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: rgba(255,255,255,0.5);
      cursor: pointer;
      transition: all 0.3s;
    }

    .carousel-dot.active {
      background: white;
      transform: scale(1.2);
    }

    /* Article Actions */
    .article-actions {
      display: flex;
      gap: 10px;
      margin-top: 12px;
    }

    .like-btn, .comment-btn {
      background: rgba(255,255,255,0.9);
      border: 2px solid #f0f0f0;
      padding: 6px 12px;
      border-radius: 20px;
      cursor: pointer;
      font-size: 12px;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .like-btn:hover, .comment-btn:hover {
      background: white;
      transform: scale(1.05);
    }

    .like-btn.liked {
      animation: heartBeat 0.6s;
      background: #ff6b6b;
      color: white;
    }

    @keyframes heartBeat {
      0% { transform: scale(1); }
      25% { transform: scale(1.3); }
      50% { transform: scale(1); }
      75% { transform: scale(1.2); }
      100% { transform: scale(1); }
    }

    /* Testimonials Styles */
    .testimonials-section {
      margin: 4rem 0;
      padding: 2rem;
      background: linear-gradient(135deg, var(--bg1), var(--bg2));
      border-radius: 16px;
    }

    .section-title {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      margin-bottom: 2rem;
      text-align: center;
    }

    .testimonials-container {
      position: relative;
      height: 120px;
      overflow: hidden;
    }

    .testimonial {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.5s ease;
      text-align: center;
    }

    .testimonial.active {
      opacity: 1;
      transform: translateY(0);
    }

    .testimonial p {
      font-size: 1.1rem;
      font-style: italic;
      margin-bottom: 1rem;
    }

    .testimonial span {
      color: var(--muted);
      font-weight: 600;
    }

    /* Contact Form Styles */
    .contact-section {
      margin: 4rem 0;
    }

    .contact-form {
      max-width: 600px;
      margin: 0 auto;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: var(--muted);
    }

    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 12px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-family: inherit;
      transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: var(--p1);
    }

    .error-message {
      color: #ff6b6b;
      font-size: 0.8rem;
      margin-top: 0.25rem;
      display: block;
    }

    .submit-btn {
      background: linear-gradient(135deg, var(--p1), var(--p2));
      color: white;
      border: none;
      padding: 15px 30px;
      border-radius: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s;
      width: 100%;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(255,107,107,0.3);
    }

    /* Decorative Elements */
    .arrow, .sticker {
      position: absolute;
      font-size: 2rem;
      opacity: 0.1;
      z-index: 1;
    }

    .arrow1 { top: 10%; right: 5%; }
    .arrow2 { top: 20%; left: 3%; }
    .arrow3 { bottom: 15%; right: 8%; }

    .sticker {
      background: linear-gradient(135deg, var(--p1), var(--p2));
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 12px;
      font-size: 0.9rem;
      font-weight: 700;
      opacity: 0.9;
      transform: rotate(-5deg);
    }

    .sticker1 { top: 5%; left: 5%; }
    .sticker2 { top: 15%; right: 10%; transform: rotate(5deg); }
    .sticker3 { bottom: 10%; left: 8%; transform: rotate(-3deg); }

    /* Responsive */
    @media (max-width: 700px) {
      .mag-header {
        padding: 1rem;
        flex-direction: column;
        gap: 1rem;
      }

      .mag-nav {
        width: 100%;
        justify-content: space-between;
      }

      .search-bar {
        width: 150px;
      }

      .search-bar:focus {
        width: 180px;
      }

      .home-wrap {
        padding: 1.5rem;
      }

      .home-title {
        font-size: 2.5rem;
      }

      .mag-grid {
        grid-template-columns: 1fr;
      }

      .carousel {
        height: 250px;
      }
      
      .carousel-caption {
        font-size: 1.2rem;
        padding: 1rem;
      }
      
      .testimonials-section {
        padding: 1rem;
      }
      
      .section-title {
        font-size: 1.5rem;
      }

      .arrow, .sticker {
        display: none;
      }
    }
  </style>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&family=Playfair+Display:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
  <div class="loader">
    <div class="loader-content">
      <div class="loader-title">MiniMag</div>
      <div class="loader-dots">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
      </div>
    </div>
  </div>

  <header class="mag-header">
    <div class="mag-logo">
      <div class="mark">MM</div>
      <div>
        <h1>MiniMag</h1>
        <div style="font-size:13px;color:var(--muted)">Magazine créatif & inspirant ✨</div>
      </div>
    </div>
    <nav class="mag-nav">
      <a href="index.php">🏠 Accueil</a>
      
      <!-- Search Bar -->
      <div class="search-container">
        <span class="search-icon">🔍</span>
        <input type="text" class="search-bar" placeholder="Rechercher...">
      </div>
      
      <!-- Category Filter -->
      <select class="category-filter">
        <option value="">Toutes les catégories</option>
        <option value="Design">Design</option>
        <option value="Lifestyle">Lifestyle</option>
        <option value="Food">Food</option>
        <option value="Voyage">Voyage</option>
        <option value="Tech">Tech</option>
      </select>
      
      <!-- Profile Icon -->
      <a href="admin/login.php" class="profile-icon">👤</a>
    </nav>
  </header>

  <main class="mag-main">
    <div class="page-container">
      <div class="home-page">
        <div class="home-wrap">
          <h2 class="home-title">✨ Édition Printemps</h2>
          <p class="home-subtitle">Découvrez nos articles créatifs, inspirants et colorés</p>
          
          <!-- Image Carousel Section -->
          <div class="carousel-section">
            <div class="carousel-container">
              <div class="carousel">
                <div class="carousel-track">
                  <div class="carousel-slide active">
                    <img src="https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=800&h=400&fit=crop" alt="Design créatif">
                    <div class="carousel-caption">Créez votre espace inspirant</div>
                  </div>
                  <div class="carousel-slide">
                    <img src="https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=800&h=400&fit=crop" alt="Lifestyle">
                    <div class="carousel-caption">Découvrez le bonheur simple</div>
                  </div>
                  <div class="carousel-slide">
                    <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=800&h=400&fit=crop" alt="Food">
                    <div class="carousel-caption">Snacks créatifs en 10min</div>
                  </div>
                </div>
              </div>
              <button class="carousel-btn prev">‹</button>
              <button class="carousel-btn next">›</button>
              <div class="carousel-dots"></div>
            </div>
          </div>

          <div class="mag-grid">
            <?php
            // Include database connection
            include_once 'config/database.php';
            
            try {
                $database = new Database();
                $db = $database->getConnection();
                
                if ($db) {
                    // Try to get articles from database
                    $query = "SELECT * FROM articles WHERE status = 'published' ORDER BY publication_date DESC LIMIT 9";
                    $stmt = $db->prepare($query);
                    $stmt->execute();
                    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (empty($articles)) {
                        // If no articles in database, show instruction message
                        echo '
                        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: #f8f9fa; border-radius: 16px;">
                            <h3>📝 Aucun article trouvé</h3>
                            <p>Veuillez ajouter des articles dans la base de données SQLite.</p>
                            <p><small>Ouvrez <strong>minimag.db</strong> avec SQLite DB Browser et ajoutez des articles dans la table "articles"</small></p>
                        </div>';
                    } else {
                        // Display articles from database
                        $article_count = 0;
                        foreach ($articles as $row) {
                            $article_count++;
                            $article_class = 'a' . $article_count;
                            
                            // Format date
                            $publication_date = date('d M', strtotime($row['publication_date']));
                            
                            // Get like count
                            $like_count = 0;
                            $comment_count = 0;
                            
                            try {
                                $like_query = "SELECT COUNT(*) as like_count FROM likes WHERE article_id = :article_id";
                                $like_stmt = $db->prepare($like_query);
                                $like_stmt->bindParam(':article_id', $row['id']);
                                $like_stmt->execute();
                                $like_data = $like_stmt->fetch(PDO::FETCH_ASSOC);
                                $like_count = $like_data['like_count'] ?? 0;
                                
                                $comment_query = "SELECT COUNT(*) as comment_count FROM comments WHERE article_id = :article_id AND status = 'approved'";
                                $comment_stmt = $db->prepare($comment_query);
                                $comment_stmt->bindParam(':article_id', $row['id']);
                                $comment_stmt->execute();
                                $comment_data = $comment_stmt->fetch(PDO::FETCH_ASSOC);
                                $comment_count = $comment_data['comment_count'] ?? 0;
                            } catch(Exception $e) {
                                // Use default counts if query fails
                            }
                            
                            echo '
                            <article class="article ' . $article_class . '" data-article-id="' . $row['id'] . '">
                              <a href="article.php?id=' . $row['id'] . '">
                                <div class="media">
                                  <img src="' . $row['image_url'] . '" alt="' . htmlspecialchars($row['title']) . '" loading="lazy">
                                  <div class="badge">' . $row['category'] . '</div>
                                </div>
                                <div class="content">
                                  <div class="kicker">' . $row['category'] . '</div>
                                  <div class="title">' . htmlspecialchars($row['title']) . '</div>
                                  <div class="excerpt">' . htmlspecialchars($row['excerpt']) . '</div>
                                  <div class="meta">
                                    <div class="icon">💡</div>
                                    <div class="meta-text">' . $row['reading_time'] . ' min • ' . $publication_date . '</div>
                                  </div>
                                  <div class="article-actions">
                                    <button class="like-btn" data-article="' . $row['id'] . '">❤️ <span class="like-count">' . $like_count . '</span></button>
                                    <button class="comment-btn" data-article="' . $row['id'] . '">💬 <span class="comment-count">' . $comment_count . '</span></button>
                                  </div>
                                </div>
                              </a>
                            </article>';
                        }
                    }
                    
                } else {
                    throw new Exception('Database connection failed');
                }
                
            } catch(Exception $exception) {
                // Show error message if database fails completely
                echo '
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; background: #ffebee; border-radius: 16px;">
                    <h3>❌ Erreur de base de données</h3>
                    <p>Impossible de se connecter à la base de données.</p>
                    <p><small>Assurez-vous que l\'extension SQLite est activée dans PHP</small></p>
                </div>';
            }
            ?>
          </div>

          <!-- Testimonials Section -->
          <section class="testimonials-section">
            <h3 class="section-title">💬 Ce que disent nos lecteurs</h3>
            <div class="testimonials-container">
              <div class="testimonials-slider">
                <div class="testimonial active">
                  <p>"Ce magazine m'inspire chaque jour ! Les articles sont tellement créatifs."</p>
                  <span>- Marie L.</span>
                </div>
                <div class="testimonial">
                  <p>"J'adore le design et la qualité du contenu. Je recommande à tous !"</p>
                  <span>- Pierre D.</span>
                </div>
                <div class="testimonial">
                  <p>"Une vraie bouffée d'air frais dans ma routine. Merci MiniMag !"</p>
                  <span>- Sophie M.</span>
                </div>
              </div>
            </div>
          </section>

          <!-- Contact Section -->
          <section class="contact-section">
            <h3 class="section-title">📧 Restons en contact</h3>
            <form class="contact-form" id="contactForm">
              <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" id="name" name="name" required>
                <span class="error-message"></span>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <span class="error-message"></span>
              </div>
              <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
                <span class="error-message"></span>
              </div>
              <button type="submit" class="submit-btn">Envoyer le message</button>
            </form>
          </section>
        </div>

        <div class="arrow arrow1">↗</div>
        <div class="arrow arrow2">↖</div>
        <div class="arrow arrow3">↘</div>
        <div class="sticker sticker1">★ New!</div>
        <div class="sticker sticker2">♡ Love it</div>
        <div class="sticker sticker3">✨ Fresh</div>
      </div>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const loader = document.querySelector('.loader');
      setTimeout(() => {
        loader.style.opacity = '0';
        setTimeout(() => loader.style.display = 'none', 500);
      }, 1000);
    });

    class ImageCarousel {
      constructor() {
        this.currentSlide = 0;
        this.slides = document.querySelectorAll('.carousel-slide');
        this.init();
      }

      init() {
        if (this.slides.length === 0) return;
        
        this.createDots();
        this.setupEventListeners();
        this.startAutoPlay();
        this.showSlide(0);
      }

      createDots() {
        const dotsContainer = document.querySelector('.carousel-dots');
        dotsContainer.innerHTML = '';
        
        this.slides.forEach((_, index) => {
          const dot = document.createElement('div');
          dot.classList.add('carousel-dot');
          if (index === 0) dot.classList.add('active');
          dot.addEventListener('click', () => this.goToSlide(index));
          dotsContainer.appendChild(dot);
        });
      }

      setupEventListeners() {
        document.querySelector('.carousel-btn.prev').addEventListener('click', () => this.prevSlide());
        document.querySelector('.carousel-btn.next').addEventListener('click', () => this.nextSlide());
      }

      nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.slides.length;
        this.showSlide(this.currentSlide);
      }

      prevSlide() {
        this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.showSlide(this.currentSlide);
      }

      goToSlide(index) {
        this.currentSlide = index;
        this.showSlide(this.currentSlide);
      }

      showSlide(index) {
        this.slides.forEach(slide => slide.classList.remove('active'));
        this.slides[index].classList.add('active');
        
        document.querySelectorAll('.carousel-dot').forEach((dot, i) => {
          dot.classList.toggle('active', i === index);
        });
        
        document.querySelector('.carousel-track').style.transform = `translateX(-${index * 100}%)`;
      }

      startAutoPlay() {
        setInterval(() => this.nextSlide(), 5000);
      }
    }

    class InteractionSystem {
      constructor() {
        this.init();
      }

      init() {
        document.addEventListener('click', (e) => {
          if (e.target.closest('.like-btn')) {
            e.preventDefault();
            this.handleLike(e.target.closest('.like-btn'));
          }
          if (e.target.closest('.comment-btn')) {
            e.preventDefault();
            this.handleComment(e.target.closest('.comment-btn'));
          }
        });
      }

      async handleLike(button) {
        const articleId = button.dataset.article;
        
        try {
          const response = await fetch('like_article.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `article_id=${articleId}`
          });
          
          const result = await response.json();
          
          if (result.success) {
            button.querySelector('.like-count').textContent = result.like_count;
            button.classList.add('liked');
            setTimeout(() => button.classList.remove('liked'), 600);
          } else {
            console.error('Like error:', result.error);
          }
        } catch (error) {
          console.error('Like request failed:', error);
          this.handleLikeFallback(button);
        }
      }

      handleLikeFallback(button) {
        const articleId = button.dataset.article;
        const likes = JSON.parse(localStorage.getItem('magazineLikes')) || {};
        likes[articleId] = (likes[articleId] || 0) + 1;
        localStorage.setItem('magazineLikes', JSON.stringify(likes));
        
        button.querySelector('.like-count').textContent = likes[articleId];
        button.classList.add('liked');
        setTimeout(() => button.classList.remove('liked'), 600);
      }

      async handleComment(button) {
        const articleId = button.dataset.article;
        const comment = prompt('Laissez un commentaire:');
        
        if (comment) {
          try {
            const response = await fetch('add_comment.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
              },
              body: `article_id=${articleId}&comment=${encodeURIComponent(comment)}`
            });
            
            const result = await response.json();
            
            if (result.success) {
              button.querySelector('.comment-count').textContent = result.comment_count;
              alert('Merci pour votre commentaire!');
            } else {
              alert('Erreur: ' + result.error);
            }
          } catch (error) {
            console.error('Comment request failed:', error);
            this.handleCommentFallback(button);
          }
        }
      }

      handleCommentFallback(button) {
        const articleId = button.dataset.article;
        const comments = JSON.parse(localStorage.getItem('magazineComments')) || {};
        comments[articleId] = (comments[articleId] || 0) + 1;
        localStorage.setItem('magazineComments', JSON.stringify(comments));
        
        button.querySelector('.comment-count').textContent = comments[articleId];
        alert('Merci pour votre commentaire!');
      }
    }

    class DynamicMenu {
      constructor() {
        this.header = document.querySelector('.mag-header');
        this.lastScrollY = window.scrollY;
        this.init();
      }

      init() {
        window.addEventListener('scroll', () => this.handleScroll());
      }

      handleScroll() {
        const currentScrollY = window.scrollY;
        
        if (currentScrollY > this.lastScrollY && currentScrollY > 100) {
          this.header.style.transform = 'translateY(-100%)';
        } else {
          this.header.style.transform = 'translateY(0)';
        }
        
        this.lastScrollY = currentScrollY;
      }
    }

    class TestimonialsSlider {
      constructor() {
        this.currentTestimonial = 0;
        this.testimonials = document.querySelectorAll('.testimonial');
        this.init();
      }

      init() {
        if (this.testimonials.length === 0) return;
        setInterval(() => this.nextTestimonial(), 4000);
      }

      nextTestimonial() {
        this.testimonials[this.currentTestimonial].classList.remove('active');
        this.currentTestimonial = (this.currentTestimonial + 1) % this.testimonials.length;
        this.testimonials[this.currentTestimonial].classList.add('active');
      }
    }

    class FormValidator {
      constructor() {
        this.form = document.getElementById('contactForm');
        this.init();
      }

      init() {
        if (!this.form) return;
        
        this.form.addEventListener('submit', (e) => this.validateForm(e));
        this.setupRealTimeValidation();
      }

      setupRealTimeValidation() {
        const inputs = this.form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
          input.addEventListener('blur', () => this.validateField(input));
          input.addEventListener('input', () => this.clearError(input));
        });
      }

      validateField(field) {
        const errorElement = field.parentElement.querySelector('.error-message');
        
        if (field.type === 'email' && field.value && !this.isValidEmail(field.value)) {
          this.showError(field, 'Veuillez entrer un email valide');
          return false;
        }
        
        if (field.required && !field.value.trim()) {
          this.showError(field, 'Ce champ est requis');
          return false;
        }
        
        this.clearError(field);
        return true;
      }

      validateForm(e) {
        e.preventDefault();
        let isValid = true;
        const fields = this.form.querySelectorAll('input[required], textarea[required]');
        
        fields.forEach(field => {
          if (!this.validateField(field)) {
            isValid = false;
          }
        });

        if (isValid) {
          this.showSuccess();
          this.form.reset();
        }
      }

      isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      }

      showError(field, message) {
        const errorElement = field.parentElement.querySelector('.error-message');
        errorElement.textContent = message;
        field.style.borderColor = '#ff6b6b';
      }

      clearError(field) {
        const errorElement = field.parentElement.querySelector('.error-message');
        errorElement.textContent = '';
        field.style.borderColor = '';
      }

      showSuccess() {
        alert('Merci ! Votre message a été envoyé avec succès.');
      }
    }

    class SearchAndFilter {
      constructor() {
        this.searchBar = document.querySelector('.search-bar');
        this.categoryFilter = document.querySelector('.category-filter');
        this.articles = document.querySelectorAll('.article');
        this.init();
      }

      init() {
        if (!this.searchBar || !this.categoryFilter) return;
        
        this.searchBar.addEventListener('input', () => this.filterArticles());
        this.categoryFilter.addEventListener('change', () => this.filterArticles());
      }

      filterArticles() {
        const searchTerm = this.searchBar.value.toLowerCase();
        const selectedCategory = this.categoryFilter.value;
        
        this.articles.forEach(article => {
          const title = article.querySelector('.title').textContent.toLowerCase();
          const excerpt = article.querySelector('.excerpt').textContent.toLowerCase();
          const category = article.querySelector('.kicker').textContent;
          
          const matchesSearch = title.includes(searchTerm) || excerpt.includes(searchTerm);
          const matchesCategory = !selectedCategory || category === selectedCategory;
          
          if (matchesSearch && matchesCategory) {
            article.style.display = 'block';
          } else {
            article.style.display = 'none';
          }
        });
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      new ImageCarousel();
      new InteractionSystem();
      new DynamicMenu();
      new TestimonialsSlider();
      new FormValidator();
      new SearchAndFilter();
    });
  </script>
</body>
</html>