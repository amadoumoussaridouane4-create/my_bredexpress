<?php
session_start();
if(!isset($_SESSION['admin'])){ 
    http_response_code(403);
    exit();
}

include "config.php";

header('Content-Type: application/json');

try {
    // Récupérer la dernière commande
    $stmt = $conn->prepare("SELECT id, nom_client, type_pain, quantite, total FROM commandes ORDER BY date_commande DESC LIMIT 1");
    $stmt->execute();
    $derniere = $stmt->fetch();
    
    if ($derniere) {
        echo json_encode([
            'nouvelle_commande' => true,
            'derniere_commande_id' => $derniere['id'],
            'nom_client' => $derniere['nom_client'],
            'type_pain' => $derniere['type_pain'],
            'quantite' => $derniere['quantite'],
            'total' => $derniere['total']
        ]);
    } else {
        echo json_encode([
            'nouvelle_commande' => false,
            'derniere_commande_id' => 0
        ]);
    }
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
