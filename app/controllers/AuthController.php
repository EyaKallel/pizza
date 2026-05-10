<?php
// Appeler Database pour démarrer les sessions automatiquement
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

class AuthController extends Controller {
    
    public function index() {
        $this->redirect('auth/login');
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $this->model('User');
            
            $user->nom = $_POST['nom'];
            $user->prenom = $_POST['prenom'];
            $user->email = $_POST['email'];
            $user->mot_de_passe = $_POST['mot_de_passe'];
            $user->telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';
            
            if (!empty($user->telephone) && !$this->validateTunisianPhone($user->telephone)) {
                $this->view('auth/register', ['error' => "Le numéro de téléphone doit contenir exactement 8 chiffres"]);
                return;
            }
            
            if ($user->emailExists()) {
                $this->view('auth/register', ['error' => "Cet email est déjà utilisé"]);
                return;
            }
            
            if ($user->register()) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['email'] = $user->email;
                $_SESSION['nom'] = $user->nom;
                $_SESSION['prenom'] = $user->prenom;
                $_SESSION['role'] = 'client';
                $this->redirect('home');
            } else {
                $this->view('auth/register', ['error' => "Erreur lors de l'inscription"]);
            }
        } else {
            $this->view('auth/register');
        }
    }
    
    private function validateTunisianPhone($phone) {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return strlen($phone) === 8 && ctype_digit($phone);
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                $this->view('auth/login', ['error' => "Email ou mot de passe incorrect"]);
            }
        } else {
            $this->view('auth/login');
        }
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('auth/login');
    }
}
?>