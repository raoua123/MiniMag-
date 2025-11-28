<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include_once '../config/database.php';

$role = $_SESSION['role'] ?? 'editor';

$database = new Database();
$db = $database->getConnection();

// CRUD basique: handle add/update/delete via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id']) && $role === 'admin') {
        $id = (int)$_POST['delete_id'];
        $stmt = $db->prepare("DELETE FROM articles WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        // ajout ou update
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $title = trim($_POST['title'] ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $category = trim($_POST['category'] ?? 'Général');
        $image_url = trim($_POST['image_url'] ?? '');
        $reading_time = (int)($_POST['reading_time'] ?? 3);
        $status = $_POST['status'] ?? 'draft';

        if ($title !== '' && $excerpt !== '' && $content !== '') {
            if ($id > 0) {
                $stmt = $db->prepare("
                    UPDATE articles
                    SET title=:t, excerpt=:e, content=:c, category=:cat,
                        image_url=:img, reading_time=:rt, status=:s
                    WHERE id=:id
                ");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            } else {
                $stmt = $db->prepare("
                    INSERT INTO articles (title, excerpt, content, category, image_url, reading_time, status, publication_date)
                    VALUES (:t, :e, :c, :cat, :img, :rt, :s, NOW())
                ");
            }

            $stmt->bindParam(':t', $title);
            $stmt->bindParam(':e', $excerpt);
            $stmt->bindParam(':c', $content);
            $stmt->bindParam(':cat', $category);
            $stmt->bindParam(':img', $image_url);
            $stmt->bindParam(':rt', $reading_time, PDO::PARAM_INT);
            $stmt->bindParam(':s', $status);
            $stmt->execute();
        }
    }
}

// Récupérer tous les articles
$stmt = $db->prepare("SELECT * FROM articles ORDER BY publication_date DESC");
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MiniMag — Tableau de bord</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&family=Playfair+Display:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg1:#fff7fb; --bg2:#fffef6; --muted:#6b6b6b;
      --p1:#ff6b6b; --p2:#4ecdc4;
    }
    *{box-sizing:border-box;margin:0;padding:0}
    body{
      min-height:100vh;
      background:linear-gradient(180deg,var(--bg1),var(--bg2));
      font-family:'Inter',system-ui,sans-serif;
    }
    .mag-header{
      display:flex;align-items:center;justify-content:space-between;
      padding:20px 30px;background:rgba(255,255,255,0.9);
      backdrop-filter:blur(10px);
      box-shadow:0 4px 20px rgba(0,0,0,0.05);position:sticky;top:0;z-index:100;
    }
    .mag-logo{display:flex;align-items:center;gap:14px;}
    .mag-logo .mark{
      width:50px;height:50px;border-radius:14px;
      background:linear-gradient(135deg,var(--p1),var(--p2));
      display:flex;align-items:center;justify-content:center;
      color:white;font-weight:900;font-family:'Playfair Display',serif;
      font-size:20px;box-shadow:0 16px 40px rgba(16,24,40,0.12);
    }
    .mag-logo h1{font-family:'Playfair Display',serif;font-size:20px}
    .user-info{font-size:14px;color:var(--muted);text-align:right}
    .logout-link{
      color:var(--p1);text-decoration:none;font-weight:600;
      margin-left:8px;
    }
    .admin-main{
      max-width:1200px;margin:30px auto;padding:0 30px 60px;
    }
    .section-title{
      font-family:'Playfair Display',serif;
      font-size:26px;font-weight:800;margin-bottom:10px;
    }
    .section-sub{font-size:14px;color:var(--muted);margin-bottom:24px}
    .admin-grid{
      display:grid;grid-template-columns:1.1fr 1.2fr;gap:24px;align-items:flex-start;
    }
    .card{
      background:white;border-radius:20px;padding:24px;
      box-shadow:0 12px 40px rgba(0,0,0,0.08);
    }
    .card h3{font-size:18px;margin-bottom:8px}
    .card p{font-size:14px;color:var(--muted);margin-bottom:16px}
    .form-group{margin-bottom:14px}
    label{display:block;margin-bottom:4px;font-size:13px;font-weight:600;color:var(--muted)}
    input,textarea,select{
      width:100%;padding:10px;border-radius:10px;
      border:2px solid #e0e0e0;font-family:inherit;font-size:14px;
    }
    textarea{min-height:90px}
    input:focus,textarea:focus,select:focus{outline:none;border-color:var(--p1)}
    .btn{
      border:0;border-radius:10px;padding:10px 18px;
      font-size:14px;font-weight:600;cursor:pointer;
    }
    .btn-primary{
      background:linear-gradient(135deg,var(--p1),var(--p2));color:white;
    }
    .btn-danger{background:#ff4b4b;color:white;}
    table{
      width:100%;border-collapse:collapse;font-size:13px;
    }
    th,td{padding:8px 6px;border-bottom:1px solid #f0f0f0;text-align:left}
    th{background:#fff7fb;font-weight:700}
    .badge{
      display:inline-block;padding:2px 8px;border-radius:999px;
      font-size:11px;font-weight:600;
    }
    .badge-published{background:#d1fae5;color:#065f46;}
    .badge-draft{background:#fee2e2;color:#991b1b;}
    @media(max-width:900px){
      .admin-grid{grid-template-columns:1fr;}
    }
  </style>
</head>
<body>
  <header class="mag-header">
    <div class="mag-logo">
      <div class="mark">MM</div>
      <div>
        <h1>MiniMag — Admin</h1>
        <div style="font-size:13px;color:var(--muted)">Gestion du magazine</div>
      </div>
    </div>
    <div class="user-info">
      Connecté en tant que <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong><br>
      Rôle : <strong><?php echo htmlspecialchars($role); ?></strong>
      <a href="logout.php" class="logout-link">Se déconnecter</a>
    </div>
  </header>

  <main class="admin-main">
    <h2 class="section-title">Tableau de bord</h2>
    <p class="section-sub">Ajoutez, modifiez et supprimez les articles de MiniMag.</p>

    <div class="admin-grid">
      <!-- Formulaire article -->
      <section class="card">
        <h3>Nouvel article / Modifier</h3>
        <p>Complétez les champs puis enregistrez. Les rédacteurs peuvent créer/éditer, les admins peuvent aussi supprimer.</p>
        <form method="post">
          <input type="hidden" name="id" id="article_id">
          <div class="form-group">
            <label for="title">Titre</label>
            <input type="text" name="title" id="title" required>
          </div>
          <div class="form-group">
            <label for="excerpt">Extrait (résumé)</label>
            <input type="text" name="excerpt" id="excerpt" required>
          </div>
          <div class="form-group">
            <label for="content">Contenu (HTML autorisé)</label>
            <textarea name="content" id="content" required></textarea>
          </div>
          <div class="form-group">
            <label for="category">Catégorie</label>
            <input type="text" name="category" id="category" value="Inspiration">
          </div>
          <div class="form-group">
            <label for="image_url">Image URL</label>
            <input type="text" name="image_url" id="image_url" placeholder="https://...">
          </div>
          <div class="form-group">
            <label for="reading_time">Temps de lecture (min)</label>
            <input type="number" name="reading_time" id="reading_time" value="3" min="1">
          </div>
          <div class="form-group">
            <label for="status">Statut</label>
            <select name="status" id="status">
              <option value="draft">Brouillon</option>
              <option value="published">Publié</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Enregistrer l’article</button>
        </form>
      </section>

      <!-- Liste des articles -->
      <section class="card">
        <h3>Articles existants</h3>
        <p>Cliquez sur “Modifier” pour charger un article dans le formulaire.<?php if ($role === 'admin'): ?> Les admins peuvent aussi le supprimer.<?php endif; ?></p>
        <div style="max-height:450px;overflow:auto;">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Cat.</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($articles as $a): ?>
                <tr data-article='<?php echo json_encode([
                  "id"=>$a["id"],
                  "title"=>$a["title"],
                  "excerpt"=>$a["excerpt"],
                  "content"=>$a["content"],
                  "category"=>$a["category"],
                  "image_url"=>$a["image_url"],
                  "reading_time"=>$a["reading_time"],
                  "status"=>$a["status"]
                ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>'>
                  <td><?php echo (int)$a['id']; ?></td>
                  <td><?php echo htmlspecialchars($a['title']); ?></td>
                  <td><?php echo htmlspecialchars($a['category']); ?></td>
                  <td>
                    <span class="badge <?php echo $a['status']==='published'?'badge-published':'badge-draft'; ?>">
                      <?php echo htmlspecialchars($a['status']); ?>
                    </span>
                  </td>
                  <td><?php echo htmlspecialchars($a['publication_date']); ?></td>
                  <td>
                    <button type="button" class="btn" onclick="loadArticle(this)">Modifier</button>
                    <?php if ($role === 'admin'): ?>
                      <form method="post" style="display:inline" onsubmit="return confirm('Supprimer cet article ?');">
                        <input type="hidden" name="delete_id" value="<?php echo (int)$a['id']; ?>">
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                      </form>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </main>

  <script>
    function loadArticle(btn) {
      const tr = btn.closest('tr');
      const data = JSON.parse(tr.getAttribute('data-article'));

      document.getElementById('article_id').value = data.id || '';
      document.getElementById('title').value = data.title || '';
      document.getElementById('excerpt').value = data.excerpt || '';
      document.getElementById('content').value = data.content || '';
      document.getElementById('category').value = data.category || '';
      document.getElementById('image_url').value = data.image_url || '';
      document.getElementById('reading_time').value = data.reading_time || 3;
      document.getElementById('status').value = data.status || 'draft';

      window.scrollTo({top:0,behavior:'smooth'});
    }
  </script>
</body>
</html>
