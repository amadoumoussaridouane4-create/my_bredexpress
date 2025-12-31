<?php
session_start();
if(!isset($_SESSION['admin'])){ 
    header("Location: login.php"); 
    exit();
}
include "config.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - My BredExpress</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table{width:100%;border-collapse:collapse;margin-top:20px;box-shadow:0 4px 8px rgba(0,0,0,0.1);}
        th,td{border:1px solid #ddd;padding:10px;text-align:center;}
        th{background:#d35400;color:white;}
        tr:nth-child(even){background:#f9f9f9;} 
        tr:hover{background:#f39c12;color:white;}
        button{padding:5px 10px;background:#d35400;color:white;border:none;border-radius:5px;cursor:pointer;transition:.3s;}
        button:hover{background:#b84300;}
        @media(max-width:768px){table{display:block;overflow-x:auto;}}
    </style>
    
    <script>
    // Demander la permission pour les notifications
    if ('Notification' in window) {
        if (Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }

    // Vérifier les nouvelles commandes toutes les 30 secondes
    let dernierID = 0;

    function verifierNouvellesCommandes() {
        fetch('check_nouvelles_commandes.php')
            .then(response => response.json())
            .then(data => {
                if (data.nouvelle_commande && data.derniere_commande_id > dernierID) {
                    dernierID = data.derniere_commande_id;
                    
                    // Envoyer la notification
                    if (Notification.permission === 'granted') {
                        new Notification('🟢 Nouvelle commande My BredExpress !', {
                            body: `${data.nom_client} - ${data.quantite} ${data.type_pain}\nTotal: ${data.total} FCFA`,
                            icon: 'founder.jpg',
                            vibrate: [200, 100, 200]
                        });
                        
                        // Recharger la page pour afficher la nouvelle commande
                        setTimeout(() => location.reload(), 2000);
                    }
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    // Initialiser le dernier ID au chargement
    window.addEventListener('load', function() {
        fetch('check_nouvelles_commandes.php')
            .then(response => response.json())
            .then(data => {
                if (data.derniere_commande_id) {
                    dernierID = data.derniere_commande_id;
                }
            });
        
        // Vérifier toutes les 30 secondes
        setInterval(verifierNouvellesCommandes, 30000);
    });
    </script>
</head>
<body>
<header style="text-align:center;background:#d35400;color:white;padding:20px;">
    <h1>📋 Admin - My BredExpress</h1>
    <a href="logout.php" style="color:white;background:#b84300;padding:10px 20px;border-radius:6px;text-decoration:none;">🚪 Déconnexion</a>
</header>

<?php 
try {
    $stmt = $conn->prepare("SELECT * FROM commandes ORDER BY date_commande DESC");
    $stmt->execute();
    $commandes = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<table>
    <tr>
        <th>ID</th>
        <th>Client</th>
        <th>Téléphone</th>
        <th>Pain</th>
        <th>Qté</th>
        <th>Adresse</th>
        <th>GPS</th>
        <th>Prix Pain</th>
        <th>Frais Livraison</th>
        <th>Total</th>
        <th>Statut</th>
        <th>Action</th>
    </tr>
    <?php foreach($commandes as $row): ?>
    <tr>
        <td><?= htmlspecialchars($row['id']) ?></td>
        <td><?= htmlspecialchars($row['nom_client']) ?></td>
        <td><?= htmlspecialchars($row['telephone']) ?></td>
        <td><?= htmlspecialchars($row['type_pain']) ?></td>
        <td><?= htmlspecialchars($row['quantite']) ?></td>
        <td><?= htmlspecialchars($row['adresse']) ?></td>
        <td>
            <?php if (!empty($row['latitude']) && !empty($row['longitude'])): ?>
                <a href="https://www.google.com/maps?q=<?= htmlspecialchars($row['latitude']) ?>,<?= htmlspecialchars($row['longitude']) ?>" target="_blank">Voir</a>
            <?php else: ?>
                N/A
            <?php endif; ?>
        </td>   
        <td><?= htmlspecialchars($row['prix_unitaire']) ?></td>
        <td><?= htmlspecialchars($row['frais_livraison']) ?></td>
        <td><?= htmlspecialchars($row['total']) ?></td>
        <td><strong><?= htmlspecialchars($row['statut']) ?></strong></td>
        <td>
            <form method="POST" action="statut.php">
                <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
                <select name="statut">
                    <option <?= $row['statut']=="En attente"?"selected":"" ?>>En attente</option>
                    <option <?= $row['statut']=="En livraison"?"selected":"" ?>>En livraison</option>
                    <option <?= $row['statut']=="Livrée"?"selected":"" ?>>Livrée</option>
                </select>
                <button type="submit">✓ Mettre à jour</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>