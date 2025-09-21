<?php
session_start();
include 'config.php'; include 'helper.php';
$msg='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $username=trim($_POST['username']); $email=trim($_POST['email']); $password=$_POST['password']; $confirm=$_POST['confirm'];
    if ($password!==$confirm) { $msg='Les mots de passe ne correspondent pas.'; }
    else {
        $url_check = $supabaseUrl . "?or=(username.eq." . urlencode($username) . ",email.eq." . urlencode($email) . ")";
        $res = supabase_request('GET', $url_check, $api_key);
        $exists = json_decode($res['body'], true);
        if (!empty($exists)) { $msg='Pseudo ou email déjà utilisé.'; }
        else {
            $code = rand(100000,999999);
            $data=['username'=>$username,'email'=>$email,'password'=>password_hash($password,PASSWORD_DEFAULT),'is_verified'=>false,'verification_code'=>(string)$code];
            $res2 = supabase_request('POST', $supabaseUrl, $api_key, $data);
            if ($res2['code']==201) {
                send_verification_email($email,$code,$from_email);
                $_SESSION['pending_user_email']=$email; header('Location: verify.php'); exit();
            } else { $msg='Erreur création compte.'; }
        }
    }
}
?>
<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Inscription</title></head><body style="background:#121212;color:#e0e0e0;font-family:'Segoe UI',sans-serif"><div style="max-width:600px;margin:60px auto;background:#1f1f1f;padding:20px;border-radius:12px"><h2 style="color:#1e90ff">Inscription</h2><form method="POST"><input name="username" placeholder="Pseudo" required style="width:100%;padding:10px;margin-top:8px"><input name="email" type="email" placeholder="Email" required style="width:100%;padding:10px;margin-top:8px"><input name="password" type="password" placeholder="Mot de passe" required style="width:100%;padding:10px;margin-top:8px"><input name="confirm" type="password" placeholder="Confirmer" required style="width:100%;padding:10px;margin-top:8px"><button style="margin-top:12px;padding:10px;background:#1e90ff;border:none;color:#fff">S'inscrire</button></form><p style="color:#ff7777"><?php echo $msg;?></p><a href='connexion.php' style='color:#1e90ff'>Déjà un compte ?</a></div></body></html>