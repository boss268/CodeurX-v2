<?php session_start(); include 'config.php'; include 'helper.php'; if (!isset($_SESSION['user'])) { header('Location: connexion.php'); exit(); } $cur = $_SESSION['user']; $msg='';
$res = supabase_request('GET', $supabaseUrl . "?username=eq." . urlencode($cur['username']) . "&select=*", $api_key);
$user = json_decode($res['body'], true)[0] ?? $cur;
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (isset($_POST['change_info'])) {
        $newu = trim($_POST['new_username']); $newe = trim($_POST['new_email']);
        if ($newu !== $user['username']) {
            $chk = supabase_request('GET', $supabaseUrl . "?username=eq." . urlencode($newu), $api_key);
            if (!empty(json_decode($chk['body'],true))) { $msg='Pseudo déjà pris.'; }
            else { supabase_request('PATCH', $supabaseUrl . "?username=eq." . urlencode($user['username']), $api_key, ['username'=>$newu]); $_SESSION['user']['username']=$newu; $msg='Pseudo mis à jour.'; }
        }
        if ($newe !== $user['email']) {
            $code = rand(100000,999999);
            supabase_request('PATCH', $supabaseUrl . "?username=eq." . urlencode($user['username']), $api_key, ['email'=>$newe,'is_verified'=>false,'verification_code'=>$code]);
            send_verification_email($newe,$code,$from_email); $msg.=' Email: code envoyé.';
        }
    }
    if (isset($_POST['change_pwd'])) {
        $old = $_POST['old']; $new = $_POST['new']; $conf = $_POST['confirm'];
        if (!password_verify($old,$user['password'])) { $msg='Ancien mot de passe incorrect.'; }
        elseif ($new !== $conf) { $msg='Confirmation invalide.'; }
        else { supabase_request('PATCH', $supabaseUrl . "?username=eq." . urlencode($user['username']), $api_key, ['password'=>password_hash($new,PASSWORD_DEFAULT)]); $msg='Mot de passe mis à jour.'; }
    }
}
?>
<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Profil</title></head><body style="background:#121212;color:#e0e0e0;font-family:'Segoe UI',sans-serif"><header style="background:#1f1f1f;padding:15px 30px;display:flex;justify-content:space-between"><h1 style="color:#1e90ff">Codix</h1></header><main style="max-width:1000px;margin:40px auto;display:flex;gap:20px"><div style="background:#1f1f1f;padding:20px;border-radius:10px;flex:1"><h3 style="color:#1e90ff">Informations</h3><p><strong>Pseudo:</strong> <?php echo htmlspecialchars($user['username']); ?></p><p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p><p><strong>Mot de passe:</strong> <span id='pwd'>••••••</span> <button onclick="toggle()" style='background:#1e90ff;border:none;color:#fff;padding:6px;border-radius:8px;margin-left:8px'>👁️</button></p></div><div style='background:#1f1f1f;padding:20px;border-radius:10px;flex:1'><h3 style='color:#1e90ff'>Modifier</h3><form method='POST'><input name='new_username' placeholder='Nouveau pseudo' style='width:100%;padding:8px;margin-top:6px'><input name='new_email' placeholder='Nouvel email' style='width:100%;padding:8px;margin-top:6px'><button name='change_info' style='margin-top:10px;padding:8px;background:#1e90ff;border:none;color:#fff'>Mettre à jour</button></form><hr><form method='POST'><input name='old' type='password' placeholder='Ancien mot de passe' style='width:100%;padding:8px;margin-top:6px'><input name='new' type='password' placeholder='Nouveau mot de passe' style='width:100%;padding:8px;margin-top:6px'><input name='confirm' type='password' placeholder='Confirmer' style='width:100%;padding:8px;margin-top:6px'><button name='change_pwd' style='margin-top:10px;padding:8px;background:#1e90ff;border:none;color:#fff'>Changer mot de passe</button></form><p style='color:#ff7777'><?php echo $msg;?></p></div></main><script>function toggle(){let e=document.getElementById('pwd'); if(e.textContent.includes('•')) e.textContent = '<?php echo addslashes($user["password"]); ?>'; else e.textContent='••••••';}</script></body></html>