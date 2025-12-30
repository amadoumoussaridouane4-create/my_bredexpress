<?php
session_start();
if(!isset($_SESSION['admin'])){ 
    header("Location: login.php"); 
    exit();
}
include "config.php";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $statut = $_POST['statut'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM commandes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        
        if($row) {
            $nom = $row['nom_client'];
            $tel = $row['telephone'];
            $pain = $row['type_pain'];
            $qte = $row['quantite'];
            $adresse = $row['adresse'];
            $total = $row['total'];
            
            $stmt_update = $conn->prepare("UPDATE commandes SET statut = :statut WHERE id = :id");
            $stmt_update->bindParam(':statut', $statut);
            $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_update->execute();
            
            if($statut == "En livraison") {
                $message = urlencode("🚚 Bonjour $nom,
Votre commande de $qte $pain est en cours de livraison.
Adresse : $adresse
Total : $total FCFA");
            } elseif($statut == "Livrée") {
                $message = urlencode("✅ Bonjour $nom,
Votre commande de $qte $pain a été livrée.
Merci d'utiliser My BredExpress !");
            } else {
                $message = urlencode("🕒 Bonjour $nom,
Votre commande de $qte $pain est enregistrée et en attente.
Total : $total FCFA");
            }
            
            header("Location: https://wa.me/227$tel?text=" . $message);
            exit();
        } else {
            header("Location: admin.php");
            exit();
        }
    } catch(PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
} else {
    header("Location: admin.php");
    exit();
}
?>