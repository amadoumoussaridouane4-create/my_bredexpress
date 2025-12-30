<?php
// Configuration pour la base de données Render
$DATABASE_URL = getenv('DATABASE_URL') ?: 'postgresql://mybredexpress_user:FrQnruSs4SVYaduBV0mNyy5g0WxTFRJi@dpg-d59nshv5r7bs739coasg-a.frankfurt-postgres.render.com/my_bredexpress';

$db = parse_url($DATABASE_URL);

$host = $db['host'];
$port = $db['port'];
$dbname = ltrim($db['path'], '/');
$username = $db['user'];
$password = $db['pass'];

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>