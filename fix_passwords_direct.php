<?php
require_once 'config/database.php';

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

echo "<h2>Correction des mots de passe dans la base de données</h2>";

// Générer les nouveaux hashes MD5
$admin_hash = md5('admin123');
$client_hash = md5('client123');

echo "<p><strong>Nouveau hash admin123:</strong> $admin_hash</p>";
echo "<p><strong>Nouveau hash client123:</strong> $client_hash</p>";

try {
    // Mettre à jour le compte admin
    $query_admin = "UPDATE users SET mot_de_passe = ? WHERE email = 'admin@smartpizzaria.com'";
    $stmt_admin = $db->prepare($query_admin);
    $stmt_admin->execute([$admin_hash]);
    
    echo "<p>✅ Compte admin mis à jour</p>";
    
    // Mettre à jour le compte client
    $query_client = "UPDATE users SET mot_de_passe = ? WHERE email = 'mohamed@gmail.com'";
    $stmt_client = $db->prepare($query_client);
    $stmt_client->execute([$client_hash]);
    
    echo "<p>✅ Compte client mis à jour</p>";
    
    // Vérifier les mises à jour
    $query_check = "SELECT email, mot_de_passe, role FROM users WHERE email IN ('admin@smartpizzaria.com', 'mohamed@gmail.com')";
    $stmt_check = $db->prepare($query_check);
    $stmt_check->execute();
    
    echo "<h3>Vérification après mise à jour:</h3>";
    while($row = $stmt_check->fetch(PDO::FETCH_ASSOC)) {
        echo "<p><strong>Email:</strong> " . $row['email'] . "<br>";
        echo "<strong>Mot de passe:</strong> " . $row['mot_de_passe'] . "<br>";
        echo "<strong>Rôle:</strong> " . $row['role'] . "</p><hr>";
    }
    
    echo "<h3>Test de connexion:</h3>";
    echo "<p><a href='index.php?url=auth/login'>Aller à la page de login</a></p>";
    echo "<p>Utilisez:<br>";
    echo "Admin: admin@smartpizzaria.com / admin123<br>";
    echo "Client: mohamed@gmail.com / client123</p>";
    
} catch(PDOException $exception) {
    echo "<p style='color: red;'>❌ Erreur: " . $exception->getMessage() . "</p>";
}
?>
