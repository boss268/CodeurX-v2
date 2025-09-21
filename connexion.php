<?php
session_start(); include 'config.php'; include 'helper.php'; $msg='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $username = trim($_POST['username']); $password = $_POST['password'];
    $res = supabase_request('GET', $supabaseUrl . "?username=eq." . urlencode($username), $api_key);
    $u = json_decode($res['body'], true)[0] ?? null;
    if (!$u) { $msg='Pseudo introuvable.'; }
    elseif (empty($u['is_verified'])) { $msg='Compte non vérifié.'; }
    elseif (password_verify($password, $u['password'])) { $_SESSION['user']=['username'=>$u['username'],'email'=>$u['email']]; header('Location: accueil.php'); exit(); }
    else { $msg='Mot de passe incorrect.'; }
}
?>
<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Connexion</title></head><body style="background:#121212;color:#e0e0e0;font-family:'Segoe UI',sans-serif"><div style="max-width:400px;margin:80px auto;background:#1f1f1f;padding:20px;border-radius:12px"><h2 style="color:#1e90ff">Connexion</h2><form method="POST"><input name="username" placeholder="Pseudo" required style="width:100%;padding:10px;margin-top:8px"><input name="password" type="password" placeholder="Mot de passe" required style="width:100%;padding:10px;margin-top:8px"><button style="margin-top:12px;padding:10px;background:#1e90ff;border:none;color:#fff">Se connecter</button></form><p style="color:#ff7777"><?php echo $msg;?></p><a href='inscription.php' style='color:#1e90ff'>Créer un compte</a></div></body></html>