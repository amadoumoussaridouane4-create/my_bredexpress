<?php
include "config.php";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $tel = trim($_POST['tel']);
    $pain = trim($_POST['pain']);
    $qte = (int)$_POST['qte'];
    $adresse = trim($_POST['adresse']);
    
    $prix_unitaire = 200;
    $frais_livraison = 300;
    $total_pain = $prix_unitaire * $qte;
    $total = $total_pain + $frais_livraison;
    
    try {
        $sql = "INSERT INTO commandes (nom_client, telephone, type_pain, quantite, adresse, prix_unitaire, frais_livraison, total) VALUES (:nom, :tel, :pain, :qte, :adresse, :prix_unitaire, :frais_livraison, :total)";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':tel', $tel);
        $stmt->bindParam(':pain', $pain);
        $stmt->bindParam(':qte', $qte, PDO::PARAM_INT);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':prix_unitaire', $prix_unitaire, PDO::PARAM_INT);
        $stmt->bindParam(':frais_livraison', $frais_livraison, PDO::PARAM_INT);
        $stmt->bindParam(':total', $total, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $message = urlencode("NOUVELLE COMMANDE - My BredExpress. Client : $nom. Tel : $tel. Pain : $pain. Quantite : $qte. Total : $total FCFA. Adresse : $adresse");
        
        header("Location: https://wa.me/22789450364?text=" . $message);
        exit();
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    header("Location: index.html");
    exit();
}
