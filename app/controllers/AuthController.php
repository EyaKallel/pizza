<?php
class AuthController extends Controller {
    
    public function index() {
        // Rediriger vers la page de login par défaut
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
            $user->role = 'client'; // Rôle par défaut pour les nouveaux inscrits
            
            if ($user->emailExists()) {
                $error = "Cet email est déjà utilisé";
                $this->view('auth/register', ['error' => $error]);
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
                $error = "Erreur lors de l'inscription";
                $this->view('auth/register', ['error' => $error]);
            }
        } else {
            $this->view('auth/register');
        }
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $this->model('User');
            
            $user->email = $_POST['email'];
            $user->mot_de_passe = $_POST['password'];
            
            // Debug - afficher les valeurs
            error_log("Email: " . $_POST['email']);
            error_log("Password: " . $_POST['password']);
            error_log("MD5 Password: " . md5($_POST['password']));
            
            if ($user->login()) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['email'] = $user->email;
                $_SESSION['nom'] = $user->nom;
                $_SESSION['prenom'] = $user->prenom;
                $_SESSION['role'] = $user->role;
                
                // Redirection selon le rôle
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
    
    public function logout() {
        session_destroy();
        $this->redirect('auth/login');
    }
}
?>
