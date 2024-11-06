<?php
namespace iutnc\deefy\auth;

use iutnc\deefy\repository\DeefyRepository;
use PDO;
use iutnc\deefy\exception\AuthnException;

class AuthnProvider {

    // Déclaration de l'instance de DeefyRepository
    private DeefyRepository $deefyRepository;

    // Constructeur pour initialiser l'instance de DeefyRepository
    public function __construct() {
        $this->deefyRepository = DeefyRepository::getInstance();
    }

    /**
     * Authentifie un utilisateur en vérifiant son email et son mot de passe.
     * @param string $email L'email de l'utilisateur.
     * @param string $password Le mot de passe en clair de l'utilisateur.
     * @throws AuthnException si l'authentification échoue.
     */
    public static function signin(string $email, string $password): void {
        // Obtention de l'instance PDO à partir de DeefyRepository
        $pdo = DeefyRepository::getInstance()->getPDO();

        // Requête pour obtenir uniquement le hash du mot de passe en fonction de l'email
        $query = "SELECT passwd FROM user WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe
        if ($user && password_verify($password, $user['passwd'])) {
            // Authentification réussie, stockage de l'utilisateur en session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user'] = $email; // Stockage de l'utilisateur (email) en session
            echo "Authentification réussie !";
        } else {
            // L'authentification échoue, déclenchement d'une exception
            throw new AuthnException("Erreur d'authentification : email ou mot de passe incorrect.");
        }
    }


    /**
     * Enregistre un nouvel utilisateur avec un email et un mot de passe en clair.
     * @param string $email L'email de l'utilisateur.
     * @param string $password Le mot de passe en clair de l'utilisateur.
     * @throws AuthnException si l'email existe déjà ou si le mot de passe n'est pas valide.
     */
    public function register(string $email, string $password): void {
        // Contrôle de la qualité du mot de passe
        if (strlen($password) < 10) {
            throw new AuthnException("Le mot de passe doit comporter au moins 10 caractères.");
        }

        // Vérification si l'email existe déjà
        $pdo = DeefyRepository::getInstance()->getPDO();
        $query = "SELECT COUNT(*) FROM user WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            throw new AuthnException("Un compte avec cet email existe déjà.");
        }


        // Hash du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        echo "Password hash: " . $hashed_password; // Vérifiez que le hachage se fait correctement
        // TEMPORAIRE

        // Insertion de l'utilisateur dans la base de données
        $query = "INSERT INTO user (email, passwd, role) VALUES (:email, :passwd, 1)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':passwd', $hashed_password);
        $stmt->execute();
        if ($stmt->errorCode() !== '00000') {
            // Si il y a une erreur
            echo "Erreur SQL : " . implode(", ", $stmt->errorInfo());
        } else {
            echo "Inscription réussie !";
        }


        echo "Inscription réussie !";
    }
}
