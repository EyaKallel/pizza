<h1>🔧 INSTRUCTIONS POUR LES SESSIONS</h1>

<h2>✅ Solution simple et directe</h2>

<div style="background: #d4edda; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>📋 Dans CHAQUE page qui a besoin de sessions :</h3>
    
    <h4>🎯 Au tout début du fichier (avant tout code HTML) :</h4>
    <pre style="background: #f8f9fa; padding: 15px; border-radius: 5px; border-left: 4px solid #28a745;">
&lt;?php
// Inclure la base de données = démarrer les sessions automatiquement
require_once 'config/database.php';

// Créer la connexion pour activer les sessions
$database = new Database();
$db = $database->getConnection();

// Maintenant $_SESSION fonctionne dans cette page
?&gt;
    </pre>
    
    <h4>📝 Pages qui DOIVENT avoir cet appel :</h4>
    <ul>
        <li><strong>app/controllers/AuthController.php</strong> - Login/logout</li>
        <li><strong>app/views/auth/login.php</strong> - Formulaire login</li>
        <li><strong>app/controllers/HomeController.php</strong> - Page accueil</li>
        <li><strong>app/views/home/index.php</strong> - Affichage accueil</li>
        <li><strong>app/controllers/AdminController.php</strong> - Interface admin</li>
        <li><strong>app/views/admin/*.php</strong> - Toutes les vues admin</li>
        <li><strong>app/controllers/CartController.php</strong> - Panier</li>
        <li><strong>app/controllers/ProfileController.php</strong> - Profil utilisateur</li>
        <li><strong>app/controllers/CheckoutController.php</strong> - Commande</li>
    </ul>
</div>

<h2>🚫 Ce qu'il ne faut PAS faire</h2>

<div style="background: #f8d7da; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>❌ À éviter :</h3>
    <ul>
        <li><strong>JAMAIS</strong> appeler session_start() directement</li>
        <li><strong>PAS</strong> créer une autre classe pour les sessions</li>
        <li><strong>PAS</strong> de require_once 'config/session_manager.php' (inutile)</li>
        <li><strong>PAS</strong> de fonctions complexes</li>
    </ul>
    
    <h3>✅ Ce qu'il faut faire :</h3>
    <ul>
        <li><strong>TOUJOURS</strong> require_once 'config/database.php'</li>
        <li><strong>TOUJOURS</strong> new Database()</li>
        <li><strong>TOUJOURS</strong> getConnection()</li>
        <li><strong>UNIQUEMENT</strong> ces 3 lignes au début</li>
    </ul>
</div>

<h2>🔧 Exemple complet</h2>

<div style="background: #fff3cd; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>📄 Dans un contrôleur :</h3>
    <pre style="background: #f8f9fa; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
&lt;?php
// Au début du contrôleur
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

class AuthController extends Controller {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // $_SESSION fonctionne déjà grâce à l'appel ci-dessus
            
            $user = $this->model('User');
            $user->email = $_POST['email'];
            $user->mot_de_passe = $_POST['password'];
            
            if ($user->login()) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['email'] = $user->email;
                $_SESSION['nom'] = $user->nom;
                $_SESSION['prenom'] = $user->prenom;
                $_SESSION['role'] = $user->role;
                
                if ($user->role === 'admin') {
                    $this->redirect('admin');
                } else {
                    $this->redirect('home');
                }
            } else {
                $error = "Email ou mot de passe incorrect";
                $this->view('auth/login', ['error' => $error]);
            }
        } else {
            $this->view('auth/login');
        }
    }
}
?&gt;
    </pre>
    
    <h3>📄 Dans une vue :</h3>
    <pre style="background: #f8f9fa; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107;">
&lt;?php
// Au début de la vue
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();
?&gt;

&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;title&gt;Smart Pizzeria&lt;/title&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;?php
    // Maintenant $_SESSION fonctionne
    if (isset($_SESSION['user_id'])) {
        echo 'Bonjour ' . $_SESSION['nom'] . ' !';
    } else {
        echo 'Veuillez vous connecter';
    }
    ?&gt;
&lt;/body&gt;
&lt;/html&gt;
    </pre>
</div>

<h2>🎯 Résumé</h2>

<div style="background: #e9ecef; padding: 20px; border-radius: 10px; margin: 20px 0;">
    <h3>✅ La solution est SIMPLE :</h3>
    <ol>
        <li><strong>Au début de chaque page</strong> qui a besoin de sessions</li>
        <li><strong>require_once 'config/database.php'</strong></li>
        <li><strong>$database = new Database()</strong></li>
        <li><strong>$db = $database->getConnection()</strong></li>
        <li><strong>C'est tout !</strong> $_SESSION fonctionne</li>
    </ol>
    
    <p><strong>🚀 Plus simple que ça, c'est pas possible !</strong></p>
</div>

<hr>

<p><strong><a href="test_session_simple.php" style="font-size: 18px; background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">🔍 Tester les sessions maintenant</a></strong></p>
