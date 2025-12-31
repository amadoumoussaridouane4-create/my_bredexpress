<?php
include "config.php";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $tel = trim($_POST['tel']);
    $pain = trim($_POST['pain']);
    $qte = (int)$_POST['qte'];
    $adresse = trim($_POST['adresse']);
    $latitude = isset($_POST['latitude']) ? trim($_POST['latitude']) : '';
    $longitude = isset($_POST['longitude']) ? trim($_POST['longitude']) : '';
    
    $prix_unitaire = 200;
    $frais_livraison = 300;
    $total_pain = $prix_unitaire * $qte;
    $total = $total_pain + $frais_livraison;
    
    try {
        $sql = "INSERT INTO commandes (nom_client, telephone, type_pain, quantite, adresse, latitude, longitude, prix_unitaire, frais_livraison, total) VALUES (:nom, :tel, :pain, :qte, :adresse, :latitude, :longitude, :prix_unitaire, :frais_livraison, :total)";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':tel', $tel);
        $stmt->bindParam(':pain', $pain);
        $stmt->bindParam(':qte', $qte, PDO::PARAM_INT);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);
        $stmt->bindParam(':prix_unitaire', $prix_unitaire, PDO::PARAM_INT);
        $stmt->bindParam(':frais_livraison', $frais_livraison, PDO::PARAM_INT);
        $stmt->bindParam(':total', $total, PDO::PARAM_INT);
        
        $stmt->execute();
        
        // Construire le message avec coordonnées GPS si disponibles
        $message = "🟢 NOUVELLE COMMANDE - My BredExpress\n";
        $message .= " Client : $nom\n";
        $message .= " Tel : $tel\n";
        $message .= " Pain : $pain\n";
        $message .= " Quantité : $qte\n";
        $message .= " Total : $total FCFA (Pain: $total_pain + Livraison: $frais_livraison)\n";
        $message .= "📍 Adresse : $adresse\n";
        
        // Ajouter lien Google Maps si GPS disponible
        if (!empty($latitude) && !empty($longitude)) {
            $message .= "🗺️ Localisation GPS : https://www.google.com/maps?q=$latitude,$longitude\n";
        }
        
        $message .= " Paiement : À la livraison";
        
        $encodedMessage = urlencode($message);
        
        header("Location: https://wa.me/22789450364?text=" . $encodedMessage);
        exit();
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    header("Location: index.html");
    exit();
}
?>