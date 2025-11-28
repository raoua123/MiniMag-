<?php
session_start();
include_once '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username !== '' && $password !== '') {
        $database = new Database();
        $db = $database->getConnection();

        $stmt = $db->prepare("SELECT id, username, password_hash, role FROM users WHERE username = :u LIMIT 1");
        $stmt->bindParam(':u', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Identifiants invalides';
        }
    } else {
        $error = 'Veuillez remplir tous les champs';
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>MiniMag — Connexion admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&family=Playfair+Display:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg1:#fff7fb; --bg2:#fffef6; --muted:#6b6b6b;
      --p1:#ff6b6b; --p2:#4ecdc4;
    }
    *{box-sizing:border-box;margin:0;padding:0}
    body{
      height:100vh;
      background:linear-gradient(180deg,var(--bg1),var(--bg2));
      font-family:'Inter',system-ui,sans-serif;
      display:flex;
      align-items:center;
      justify-content:center;
    }
    .login-card{
      background:white;
      padding:40px;
      border-radius:24px;
      box-shadow:0 20px 60px rgba(0,0,0,0.15);
      max-width:420px;
      width:100%;
    }
    .mag-logo{
      display:flex;
      align-items:center;
      gap:14px;
      margin-bottom:24px;
    }
    .mag-logo .mark{
      width:60px;height:60px;border-radius:14px;
      background:linear-gradient(135deg,var(--p1),var(--p2));
      display:flex;align-items:center;justify-content:center;
      color:white;font-weight:900;font-family:'Playfair Display',serif;
      font-size:22px;box-shadow:0 16px 40px rgba(16,24,40,0.12);
    }
    .mag-logo h1{font-family:'Playfair Display',serif;font-size:22px}
    .login-title{font-size:20px;font-weight:700;margin-bottom:8px}
    .login-sub{font-size:14px;color:var(--muted);margin-bottom:24px}
    .form-group{margin-bottom:16px}
    label{display:block;margin-bottom:6px;font-weight:600;color:var(--muted)}
    input{
      width:100%;padding:12px;border-radius:10px;
      border:2px solid #e0e0e0;font-family:inherit;
    }
    input:focus{outline:none;border-color:var(--p1)}
    .submit-btn{
      width:100%;margin-top:8px;
      background:linear-gradient(135deg,var(--p1),var(--p2));
      color:white;border:0;padding:12px 20px;border-radius:12px;
      font-weight:700;cursor:pointer;
      box-shadow:0 4px 12px rgba(255,107,107,0.3);
    }
    .error{color:#ff4b4b;font-size:14px;margin-bottom:10px}
    .back-link{display:block;margin-top:16px;font-size:14px;color:var(--muted);text-decoration:none}
  </style>
</head>
<body>
  <div class="login-card">
    <div class="mag-logo">
      <div class="mark">MM</div>
      <div>
        <h1>MiniMag</h1>
        <div style="font-size:13px;color:var(--muted)">Espace administration</div>
      </div>
    </div>

    <h2 class="login-title">Connexion</h2>
    <p class="login-sub">Connectez-vous pour gérer les articles.</p>

    <?php if ($error): ?>
      <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="form-group">
        <label for="username">Nom d’utilisateur</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="submit-btn">Se connecter</button>
    </form>

    <a href="../index.php" class="back-link">← Retour au site</a>
  </div>
</body>
</html>
