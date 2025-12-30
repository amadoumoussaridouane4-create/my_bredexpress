<?php
session_start();
include "config.php";

if(isset($_POST['login'])){
    $user = $_POST['username'];
    $pass = $_POST['password'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = :username");
        $stmt->bindParam(':username', $user);
        $stmt->execute();
        $admin = $stmt->fetch();
        
        if($admin && password_verify($pass, $admin['password'])){
            $_SESSION['admin'] = $admin['username'];
            header("Location: admin.php");
            exit();
        } else {
            $erreur = "Identifiants incorrects";
        }
    } catch(PDOException $e) {
        $erreur = "Erreur : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - My BredExpress</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<form method="POST" style="max-width:400px;margin:50px auto;text-align:center;background:white;padding:30px;border-radius:10px;box-shadow:0 4px 8px rgba(0,0,0,0.1);">
    <h2>🔒 Connexion Admin</h2>
    <?php if(isset($erreur)) echo "<p style='color:red;font-weight:bold;'>$erreur</p>"; ?>
    <input type="text" name="username" placeholder="Nom d'utilisateur" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button name="login">Se connecter</button>
</form>
</body>
</html>