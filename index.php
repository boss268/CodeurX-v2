<?php
session_start();
if (isset($_SESSION['user'])) { header('Location: accueil.php'); exit(); }
?>
<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Codix</title>
<style>body{background:#121212;color:#e0e0e0;font-family:'Segoe UI',sans-serif;margin:0;padding:0}header{background:#1f1f1f;padding:15px 30px}h1{color:#1e90ff;margin:0}main{padding:40px;text-align:center}.btn{padding:12px 28px;border-radius:12px;background:#1e90ff;color:#fff;text-decoration:none;font-weight:bold}</style>
</head><body><header><h1>Codix</h1></header><main><h2 style='color:#1e90ff'>Bienvenue</h2><p style='color:#c0c0c0'>Connectez-vous ou inscrivez-vous pour continuer.</p><a class='btn' href='connexion.php'>Se connecter</a> <a class='btn' href='inscription.php'>S'inscrire</a></main></body></html>