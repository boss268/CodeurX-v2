<?php
session_start(); include 'config.php'; include 'helper.php'; $msg='';
if (!isset($_SESSION['pending_user_email'])) { header('Location: inscription.php'); exit(); }
$email = $_SESSION['pending_user_email'];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $code = trim($_POST['code']);
    $res = supabase_request('GET', $supabaseUrl . "?email=eq." . urlencode($email), $api_key);
    $u = json_decode($res['body'], true)[0] ?? null;
    if (!$u) { $msg='Utilisateur introuvable.'; }
    elseif ($u['verification_code'] === $code) {
        supabase_request('PATCH', $supabaseUrl . "?email=eq." . urlencode($email), $api_key, ['is_verified'=>true,'verification_code'=>null]);
        $_SESSION['user']=['username'=>$u['username'],'email'=>$u['email']]; unset($_SESSION['pending_user_email']); header('Location: accueil.php'); exit();
    } else { $msg='Code incorrect.'; }
}
?>
<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Vérifier</title></head><body style="background:#121212;color:#e0e0e0;font-family:'Segoe UI',sans-serif"><div style="max-width:500px;margin:60px auto;background:#1f1f1f;padding:20px;border-radius:12px"><h2 style="color:#1e90ff">Confirmer votre compte</h2><p>Code envoyé à <?php echo htmlspecialchars($email);?></p><form method="POST"><input name="code" placeholder="000000" style="width:100%;padding:10px;margin-top:8px"><button style="margin-top:12px;padding:10px;background:#1e90ff;border:none;color:#fff">Valider</button></form><p style="color:#ff7777"><?php echo $msg;?></p></div></body></html>