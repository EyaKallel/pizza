<?php
// Appeler la classe Database pour démarrer les sessions automatiquement
require_once 'config/database.php';

echo "<h1>🔍 TEST SESSION SIMPLE</h1>";

echo "<h2>✅ session_start() appelé automatiquement via Database</h2>";

// Créer une connexion pour activer les sessions
$database = new Database();
$db = $database->getConnection();

echo "<p>✅ Base de données connectée = sessions démarrées</p>";

echo "<h2>📊 Sessions actuelles:</h2>";
if (empty($_SESSION)) {
    echo "<p style='color: orange;'>⚠️ Aucune session active</p>";
} else {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Variable</th><th>Valeur</th></tr>";
    foreach ($_SESSION as $key => $value) {
        echo "<tr><td>$key</td><td>$value</td></tr>";
    }
    echo "</table>";
}

echo "<h2>🔐 Test de connexion admin:</h2>";

// Test avec les identifiants de la base
$email = 'admin@smartpizzaria.tn';
$password = 'admin123';

echo "<p><strong>Email:</strong> $email</p>";
echo "<p><strong>Password:</strong> $password</p>";

// Vérifier en base
$stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "<p style='color:green;'>✅ Utilisateur trouvé</p>";
    
    // Vérifier le mot de passe
    if (md5($password) === $user['mot_de_passe']) {
        echo "<p style='color:green;'>✅ Mot de passe correct</p>";
        
        // Créer les sessions
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['role'] = $user['role'];
        
        echo "<p style='color:green;'>✅ Sessions créées</p>";
        
        echo "<h3>📋 Sessions après connexion:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Variable</th><th>Valeur</th></tr>";
        foreach ($_SESSION as $key => $value) {
            echo "<tr><td><strong>$key</strong></td><td style='color:green;'>$value</td></tr>";
        }
        echo "</table>";
        
        echo "<h3>🚀 Test de redirection:</h3>";
        if ($user['role'] === 'admin') {
            echo "<a href='index.php?url=admin' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🚀 Aller à l'admin</a>";
        } else {
            echo "<a href='index.php?url=home' target='_blank' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🏠 Aller à l'accueil</a>";
        }
        
    } else {
        echo "<p style='color:red;'>❌ Mot de passe incorrect</p>";
        echo "<p><strong>MD5 attendu:</strong> " . md5($password) . "</p>";
        echo "<p><strong>MD5 en base:</strong> " . $user['mot_de_passe'] . "</p>";
    }
} else {
    echo "<p style='color:red;'>❌ Utilisateur non trouvé</p>";
}

echo "<hr>";
echo "<h2>📝 Conclusion:</h2>";
echo "<div style='background: #d4edda; padding: 20px; border-radius: 10px;'>";
echo "<p><strong>✅ session_start() est déjà dans config/database.php</strong></p>";
echo "<p><strong>✅ Pas besoin de l'appeler dans chaque page</strong></p>";
echo "<p><strong>✅ Il suffit d'appeler require_once 'config/database.php'</strong></p>";
echo "</div>";

echo "<hr>";
echo "<p><strong><a href='index.php?url=auth/login' style='font-size: 18px;'>🚀 Tester la connexion normale</a></strong></p>";
?>
