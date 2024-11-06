<?php
namespace iutnc\deefy\action;

use iutnc\deefy\action\Action;
use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\auth\AuthnProvider;
class AddUserAction extends Action
{
    public function execute(): string
    {
        // Vérifiez si la requête est de type GET pour afficher le formulaire
        if ($this->http_method === 'GET') {
            return <<<HTML
                <form method="post" action="?action=add-user">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>

                    <label for="password_confirm">Confirmer le mot de passe :</label>
                    <input type="password" id="password_confirm" name="password_confirm" required>

                    <button type="submit">S'inscrire</button>
                </form>
            HTML;
        } elseif ($this->http_method === 'POST') {
            // Récupérer les données du formulaire
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];

            // Vérifier si les mots de passe correspondent
            if ($password !== $password_confirm) {
                return "<p>Les mots de passe ne correspondent pas. Veuillez réessayer.</p>";
            }

            // Appel de la méthode register() pour enregistrer le nouvel utilisateur
            try {
//                AuthnProvider::register($email, $password);
                // Créer une instance de AuthnProvider
                $authnProvider = new AuthnProvider();

                // Appeler la méthode register() sur l'instance
                $authnProvider->register($email, $password);

                return "<p>Inscription réussie !</p>";
            } catch (AuthnException $e) {
                return "<p>Erreur : " . $e->getMessage() . "</p>";
            }
        }

        return '';
    }
}
