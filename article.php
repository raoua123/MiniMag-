<?php
// article.php

// Inclure la connexion BD
include_once 'config/database.php';

// Récupérer l'ID d'article depuis l'URL
$article_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Initialiser
$article = null;
$like_count = 0;
$comment_count = 0;

try {
    $database = new Database();
    $db = $database->getConnection();

    if ($db && $article_id > 0) {
        // Récupérer l'article
        $query = "SELECT * FROM articles WHERE id = :id AND status = 'published'";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $article_id, PDO::PARAM_INT);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($article) {
            // Likes
            $like_query = "SELECT COUNT(*) AS like_count FROM likes WHERE article_id = :article_id";
            $like_stmt = $db->prepare($like_query);
            $like_stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
            $like_stmt->execute();
            $like_data = $like_stmt->fetch(PDO::FETCH_ASSOC);
            $like_count = $like_data['like_count'] ?? 0;

            // Commentaires
            $comment_query = "SELECT COUNT(*) AS comment_count 
                              FROM comments 
                              WHERE article_id = :article_id AND status = 'approved'";
            $comment_stmt = $db->prepare($comment_query);
            $comment_stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
            $comment_stmt->execute();
            $comment_data = $comment_stmt->fetch(PDO::FETCH_ASSOC);
            $comment_count = $comment_data['comment_count'] ?? 0;
        }
    }
} catch (Exception $exception) {
    error_log("Article page error: " . $exception->getMessage());
}

// Si article introuvable → 404 + contenu par défaut
if (!$article) {
    header("HTTP/1.0 404 Not Found");
    $article = [
        'title' => 'Article non trouvé',
        'content' => '<p>L\'article que vous recherchez n\'existe pas ou a été supprimé.</p>',
        'category' => 'Erreur',
        'reading_time' => 1,
        'image_url' => 'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=800&h=600&fit=crop',
        'excerpt' => 'Article introuvable',
        'publication_date' => date('Y-m-d')
    ];
    $like_count = 0;
    $comment_count = 0;
}

// Formater la date
$publication_date = date('d M', strtotime($article['publication_date']));
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($article['title']); ?> — MiniMag</title>
  <meta name="description" content="<?php echo htmlspecialchars($article['excerpt']); ?>" />
  <meta name="author" content="MiniMag" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&family=Playfair+Display:wght@400;600;700;800;900&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg1:#fff7fb; --bg2:#fffef6; --muted:#6b6b6b;
      --p1:#ff6b6b; --p2:#4ecdc4; --p3:#ffd93d; --p4:#95e1d3;
      --card:#ffffff; --shadow:0 16px 40px rgba(16,24,40,0.12); --radius:16px;
    }

    *{box-sizing:border-box;margin:0;padding:0}

    html,body{
      height:100%;
      background:linear-gradient(180deg,var(--bg1),var(--bg2));
      font-family:'Inter',system-ui,sans-serif;
      color:#232323;
      overflow-x:hidden;
    }

    .mag-header{
      display:flex;
      align-items:center;
      justify-content:space-between;
      padding:20px 30px;
      background:rgba(255,255,255,0.8);
      backdrop-filter:blur(10px);
      box-shadow:0 4px 20px rgba(0,0,0,0.05);
      position:sticky;
      top:0;
      z-index:100;
      animation: slideDown 0.5s ease-out;
    }

    @keyframes slideDown {
      from { transform: translateY(-100%); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    .mag-logo{
      display:flex;
      align-items:center;
      gap:14px;
    }

    .mag-logo .mark{
      width:60px;
      height:60px;
      border-radius:14px;
      background:linear-gradient(135deg,var(--p1),var(--p2));
      display:flex;
      align-items:center;
      justify-content:center;
      color:white;
      font-weight:900;
      font-family:'Playfair Display',serif;
      font-size:22px;
      box-shadow:var(--shadow);
      transform:rotate(-3deg);
      transition: all 0.3s;
    }

    .mag-logo .mark:hover { transform: rotate(3deg) scale(1.1); }

    .mag-logo h1{
      margin:0;
      font-size:22px;
      font-family:'Playfair Display',serif;
    }

    .back-btn{
      background:linear-gradient(135deg,var(--p1),var(--p2));
      color:white;
      border:0;
      padding:12px 24px;
      border-radius:12px;
      font-weight:700;
      cursor:pointer;
      transition:all 0.3s;
      box-shadow:0 4px 12px rgba(255,107,107,0.3);
      display:inline-flex;
      align-items:center;
      gap:8px;
      text-decoration:none;
    }

    .back-btn:hover{
      transform:translateX(-4px) scale(1.05);
      box-shadow:0 6px 20px rgba(255,107,107,0.4);
    }

    .article-main{
      max-width:1000px;
      margin:40px auto;
      padding:0 30px 100px;
    }

    .article-container{
      background:white;
      border-radius:24px;
      overflow:hidden;
      box-shadow:0 20px 60px rgba(0,0,0,0.15);
      animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .article-hero{
      position:relative;
      height:400px;
      overflow:hidden;
      animation: zoomIn 0.8s ease-out;
    }

    @keyframes zoomIn {
      from { transform: scale(1.1); }
      to { transform: scale(1); }
    }

    .article-hero img{
      width:100%;
      height:100%;
      object-fit:cover;
    }

    .article-badge{
      position:absolute;
      top:24px;
      left:24px;
      background:linear-gradient(135deg,var(--p1),var(--p2));
      color:white;
      padding:10px 20px;
      border-radius:999px;
      font-weight:800;
      font-size:12px;
      text-transform:uppercase;
      letter-spacing:1px;
      box-shadow:0 6px 20px rgba(0,0,0,0.2);
    }

    .article-body{ padding:40px; }

    .article-title{
      font-family:'Playfair Display',serif;
      font-size:48px;
      font-weight:900;
      margin-bottom:24px;
      line-height:1.2;
      color:#232323;
      animation: fadeInUp 0.6s ease-out 0.2s backwards;
    }

    .article-meta{
      display:flex;
      align-items:center;
      gap:16px;
      margin-bottom:32px;
      padding-bottom:32px;
      border-bottom:2px solid #f0f0f0;
      animation: fadeInUp 0.6s ease-out 0.3s backwards;
    }

    .article-icon{
      width:48px;
      height:48px;
      border-radius:12px;
      background:linear-gradient(135deg,var(--p3),var(--p4));
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:24px;
      box-shadow:0 4px 12px rgba(0,0,0,0.08);
    }

    .article-meta-text{
      font-size:14px;
      color:var(--muted);
    }

    .article-meta-text .reading-time{
      font-weight:700;
      color:#232323;
      display:block;
      margin-bottom:4px;
    }

    .article-content{
      animation: fadeInUp 0.6s ease-out 0.4s backwards;
    }

    .article-content p{
      line-height:1.8;
      margin-bottom:20px;
      font-size:17px;
      color:#333;
    }

    .article-content h2{
      font-family:'Playfair Display',serif;
      font-size:32px;
      font-weight:800;
      margin:40px 0 20px;
      color:var(--p1);
    }

    .article-content h3{
      font-family:'Playfair Display',serif;
      font-size:24px;
      font-weight:700;
      margin:32px 0 16px;
      color:#232323;
    }

    .article-content ul, .article-content ol{
      margin:20px 0 20px 24px;
      line-height:1.8;
    }

    .article-content li{
      margin-bottom:12px;
      font-size:17px;
      color:#333;
    }

    .article-content strong{ font-weight:700; color:#232323; }
    .article-content em{ font-style:italic; color:var(--muted); }

    /* Interactions */
    .interactions-section {
      margin: 60px 0 40px;
      padding: 40px;
      background: linear-gradient(135deg, rgba(255,107,107,0.05), rgba(78,205,196,0.05));
      border-radius: 20px;
      border: 2px solid rgba(255,107,107,0.1);
      animation: fadeInUp 0.6s ease-out 0.5s backwards;
    }

    .interactions-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      flex-wrap: wrap;
      gap: 20px;
    }

    .interactions-title {
      font-family: 'Playfair Display', serif;
      font-size: 28px;
      font-weight: 800;
      color: #232323;
    }

    .interaction-stats {
      display: flex;
      gap: 30px;
    }

    .stat-item {
      display: flex;
      align-items: center;
      gap: 10px;
      background: white;
      padding: 12px 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .stat-count {
      font-weight: 800;
      font-size: 18px;
      color: var(--p1);
    }

    .stat-label {
      font-size: 14px;
      color: var(--muted);
    }

    .interaction-buttons {
      display: flex;
      gap: 15px;
      margin-bottom: 30px;
    }

    .interaction-btn {
      background: white;
      border: 2px solid #f0f0f0;
      padding: 12px 24px;
      border-radius: 12px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .interaction-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }

    .interaction-btn.like-btn:hover {
      border-color: #ff6b6b;
      color: #ff6b6b;
    }

    .interaction-btn.comment-btn:hover {
      border-color: #4ecdc4;
      color: #4ecdc4;
    }

    .interaction-btn.liked {
      background: #ff6b6b;
      color: white;
      border-color: #ff6b6b;
      animation: heartBeat 0.6s;
    }

    @keyframes heartBeat {
      0% { transform: scale(1); }
      25% { transform: scale(1.1); }
      50% { transform: scale(1); }
      75% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }

    /* Commentaires */
    .comments-section { margin-top: 30px; }
    .comments-title {
      font-family: 'Playfair Display', serif;
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 20px;
      color: #232323;
    }

    .comment-form {
      background: white;
      padding: 24px;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.08);
      margin-bottom: 30px;
    }

    .form-group { margin-bottom: 20px; }

    .form-group label {
      display: block;
      margin-bottom: 8px;
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

    .submit-comment-btn {
      background: linear-gradient(135deg, var(--p1), var(--p2));
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }

    .submit-comment-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(255,107,107,0.3);
    }

    .comments-list {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .comment {
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      border-left: 4px solid var(--p1);
      animation: fadeInUp 0.4s ease-out;
    }

    .comment-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .comment-author { font-weight: 700; color: #232323; }
    .comment-date { font-size: 12px; color: var(--muted); }
    .comment-text { line-height: 1.6; color: #333; }

    .no-comments {
      text-align: center;
      padding: 40px;
      color: var(--muted);
      font-style: italic;
    }

    .thank-you{
      margin-top:60px;
      padding:40px;
      background:linear-gradient(135deg,rgba(255,107,107,0.1),rgba(78,205,196,0.1));
      border:3px solid var(--p1);
      border-radius:20px;
      text-align:center;
      animation: fadeInUp 0.6s ease-out 0.6s backwards;
    }

    .thank-you-title{
      font-size:24px;
      font-weight:800;
      margin-bottom:20px;
      color:#232323;
    }

    .thank-you-btn{
      background:linear-gradient(135deg,var(--p1),var(--p2));
      color:white;
      border:0;
      padding:14px 32px;
      border-radius:12px;
      font-weight:700;
      font-size:16px;
      cursor:pointer;
      transition:all 0.3s;
      box-shadow:0 4px 12px rgba(255,107,107,0.3);
      display:inline-flex;
      align-items:center;
      gap:8px;
      text-decoration:none;
    }

    .thank-you-btn:hover{
      transform:translateY(-2px) scale(1.05);
      box-shadow:0 6px 20px rgba(255,107,107,0.4);
    }

    @media (max-width:768px){
      .mag-header{
        padding:12px 16px;
        flex-direction:column;
        gap:12px;
      }

      .article-main{
        padding:20px 16px 60px;
        margin:20px auto;
      }

      .article-hero{ height:250px; }
      .article-body{ padding:24px; }
      .article-title{ font-size:32px; }
      .article-content h2{ font-size:24px; }

      .interactions-section {
        padding: 24px;
        margin: 40px 0;
      }

      .interactions-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .interaction-stats { gap: 15px; }
      .interaction-buttons { flex-direction: column; }
      .thank-you{ padding:24px; }
    }
  </style>
</head>
<body>
  <header class="mag-header">
    <div class="mag-logo">
      <div class="mark">MM</div>
      <div>
        <h1>MiniMag</h1>
        <div style="font-size:13px;color:var(--muted)">Magazine créatif & inspirant ✨</div>
      </div>
    </div>
    <a href="index.php" class="back-btn">← Retour</a>
  </header>

  <main class="article-main">
    <article class="article-container">
      <div class="article-hero">
        <img src="<?php echo htmlspecialchars($article['image_url']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
        <div class="article-badge"><?php echo htmlspecialchars($article['category']); ?></div>
      </div>

      <div class="article-body">
        <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>

        <div class="article-meta">
          <div class="article-icon">💡</div>
          <div class="article-meta-text">
            <span class="reading-time"><?php echo (int)$article['reading_time']; ?> min de lecture</span>
            <span><?php echo $publication_date; ?></span>
          </div>
        </div>

        <div class="article-content">
          <?php echo $article['content']; ?>
        </div>

        <section class="interactions-section">
          <div class="interactions-header">
            <h2 class="interactions-title">Interactions</h2>
            <div class="interaction-stats">
              <div class="stat-item">
                <span class="stat-count" id="likeCount"><?php echo (int)$like_count; ?></span>
                <span class="stat-label">J'aime</span>
              </div>
              <div class="stat-item">
                <span class="stat-count" id="commentCount"><?php echo (int)$comment_count; ?></span>
                <span class="stat-label">Commentaires</span>
              </div>
            </div>
          </div>

          <div class="interaction-buttons">
            <button class="interaction-btn like-btn" id="articleLikeBtn" data-article="<?php echo (int)$article_id; ?>">
              ❤️ J'aime cet article
            </button>
            <button class="interaction-btn comment-btn" id="showCommentFormBtn">
              💬 Ajouter un commentaire
            </button>
          </div>

          <div class="comments-section">
            <h3 class="comments-title">Commentaires</h3>

            <form class="comment-form" id="commentForm" style="display: none;">
              <input type="hidden" name="article_id" value="<?php echo (int)$article_id; ?>">
              <div class="form-group">
                <label for="commentAuthor">Votre nom</label>
                <input type="text" id="commentAuthor" name="author" required>
              </div>
              <div class="form-group">
                <label for="commentText">Votre commentaire</label>
                <textarea id="commentText" name="comment" rows="4" required></textarea>
              </div>
              <button type="submit" class="submit-comment-btn">Publier le commentaire</button>
            </form>

            <div class="comments-list" id="commentsList">
              <div class="no-comments" id="noComments">
                Soyez le premier à commenter cet article !
              </div>
            </div>
          </div>
        </section>

        <div class="thank-you">
          <p class="thank-you-title">✨ Merci d'avoir lu cet article ! ✨</p>
          <a href="index.php" class="thank-you-btn">← Découvrir plus d'articles</a>
        </div>
      </div>
    </article>
  </main>

  <script>
    class ArticleInteractions {
      constructor() {
        this.articleId = <?php echo (int)$article_id; ?>;
        this.init();
      }

      init() {
        this.setupEventListeners();
        this.loadComments();
      }

      setupEventListeners() {
        const likeBtn = document.getElementById('articleLikeBtn');
        const showCommentFormBtn = document.getElementById('showCommentFormBtn');
        const commentForm = document.getElementById('commentForm');

        if (likeBtn) {
          likeBtn.addEventListener('click', () => {
            this.handleLike();
          });
        }

        if (showCommentFormBtn) {
          showCommentFormBtn.addEventListener('click', () => {
            this.toggleCommentForm();
          });
        }

        if (commentForm) {
          commentForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleComment();
          });
        }
      }

      async handleLike() {
        try {
          const response = await fetch('api/like_article.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'article_id=' + encodeURIComponent(this.articleId)
          });

          const result = await response.json();

          if (result.success) {
            document.getElementById('likeCount').textContent = result.like_count;

            const likeBtn = document.getElementById('articleLikeBtn');
            likeBtn.classList.add('liked');
            likeBtn.innerHTML = '❤️ Merci pour votre like !';

            setTimeout(() => {
              likeBtn.classList.remove('liked');
              likeBtn.innerHTML = '❤️ J\'aime cet article';
            }, 2000);
          }
        } catch (error) {
          console.error('Like error:', error);
          this.handleLikeFallback();
        }
      }

      handleLikeFallback() {
        const likes = JSON.parse(localStorage.getItem('articleLikes')) || {};
        const currentLikes = likes[this.articleId] || 0;
        likes[this.articleId] = currentLikes + 1;
        localStorage.setItem('articleLikes', JSON.stringify(likes));

        document.getElementById('likeCount').textContent = likes[this.articleId];

        const likeBtn = document.getElementById('articleLikeBtn');
        likeBtn.classList.add('liked');
        likeBtn.innerHTML = '❤️ Merci pour votre like !';

        setTimeout(() => {
          likeBtn.classList.remove('liked');
          likeBtn.innerHTML = '❤️ J\'aime cet article';
        }, 2000);
      }

      toggleCommentForm() {
        const form = document.getElementById('commentForm');
        if (!form) return;

        if (form.style.display === 'none' || form.style.display === '') {
          form.style.display = 'block';
          form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
          form.style.display = 'none';
        }
      }

      async handleComment() {
        const authorInput = document.getElementById('commentAuthor');
        const textInput = document.getElementById('commentText');

        const author = authorInput.value.trim();
        const text = textInput.value.trim();

        if (!author || !text) {
          alert('Veuillez remplir tous les champs');
          return;
        }

        try {
          const response = await fetch('api/add_comment.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body:
              'article_id=' + encodeURIComponent(this.articleId) +
              '&author_name=' + encodeURIComponent(author) +
              '&comment=' + encodeURIComponent(text)
          });

          const result = await response.json();

          if (result.success) {
            document.getElementById('commentCount').textContent = result.comment_count;
            document.getElementById('commentForm').reset();
            document.getElementById('commentForm').style.display = 'none';
            this.loadComments();
            alert('Merci pour votre commentaire !');
          } else {
            alert('Erreur: ' + result.error);
          }
        } catch (error) {
          console.error('Comment error:', error);
          this.handleCommentFallback(author, text);
        }
      }

      handleCommentFallback(author, text) {
        const comments = JSON.parse(localStorage.getItem('articleComments')) || {};
        if (!comments[this.articleId]) {
          comments[this.articleId] = [];
        }

        const comment = {
          id: Date.now(),
          author: author,
          text: text,
          date: new Date().toLocaleDateString('fr-FR')
        };

        comments[this.articleId].unshift(comment);
        localStorage.setItem('articleComments', JSON.stringify(comments));

        document.getElementById('commentForm').reset();
        document.getElementById('commentForm').style.display = 'none';
        this.loadComments();
        alert('Merci pour votre commentaire !');
      }

      async loadComments() {
        try {
          const response = await fetch('api/get_comments.php?article_id=' + encodeURIComponent(this.articleId));
          const result = await response.json();

          if (result.success) {
            this.renderComments(result.comments);
          } else {
            this.loadCommentsFallback();
          }
        } catch (error) {
          console.error('Load comments error:', error);
          this.loadCommentsFallback();
        }
      }

      loadCommentsFallback() {
        const comments = JSON.parse(localStorage.getItem('articleComments')) || {};
        const articleComments = comments[this.articleId] || [];
        this.renderComments(articleComments);
      }

      renderComments(comments) {
        const commentsList = document.getElementById('commentsList');
        const noComments = document.getElementById('noComments');

        if (!commentsList || !noComments) return;

        if (!comments || comments.length === 0) {
          noComments.style.display = 'block';
          commentsList.innerHTML = '';
          commentsList.appendChild(noComments);
          return;
        }

        noComments.style.display = 'none';

        commentsList.innerHTML = comments.map(comment => {
          const author = this.escapeHtml(comment.author_name || comment.author);
          const date = comment.created_at || comment.date || '';
          const text = this.escapeHtml(comment.content || comment.text);

          return `
            <div class="comment" data-comment-id="${comment.id}">
              <div class="comment-header">
                <span class="comment-author">${author}</span>
                <span class="comment-date">${date}</span>
              </div>
              <div class="comment-text">${text}</div>
            </div>
          `;
        }).join('');
      }

      escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text == null ? '' : String(text);
        return div.innerHTML;
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      new ArticleInteractions();
    });
  </script>
</body>
</html>
