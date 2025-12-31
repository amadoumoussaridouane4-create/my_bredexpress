<?php
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_avis = trim($_POST['nom_avis']);
    $note = $_POST['note'];
    $commentaire = trim($_POST['commentaire']);
    
    // Message pour WhatsApp
    $message = "⭐ NOUVEL AVIS CLIENT\n\n";
    $message .= "Nom : $nom_avis\n";
    $message .= "Note : $note\n";
    $message .= "Commentaire : $commentaire\n\n";
    $message .= "My BreadExpress";
    
    $encodedMessage = urlencode($message);
    
    header("Location: https://wa.me/22791915509?text=" . $encodedMessage);
    exit();
} else {
    header("Location: index.html");
    exit();
}
?>
