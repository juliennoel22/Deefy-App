<?php
namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;

class AddPlaylistAction extends Action
{
    public function execute(): string
    {
        if ($this->http_method === 'GET') {
            // Si la requête est de type GET, on affiche le formulaire
            return <<<HTML
            <h2>Créer une nouvelle playlist</h2>
            <form method="post" action="?action=add-playlist">
                <label for="playlist_name">Nom de la playlist :</label>
                <input type="text" id="playlist_name" name="playlist_name" required>
                <button type="submit">Créer la playlist</button>
            </form>
            HTML;
        } elseif ($this->http_method === 'POST') {
            // Si la requête est de type POST, on traite le formulaire
            $playlist_name = filter_var($_POST['playlist_name'] ?? '', FILTER_SANITIZE_STRING);

            if ($playlist_name) {
                // Instancier une nouvelle playlist avec le nom nettoyé
                $playlist = new Playlist($playlist_name);

                // Démarrer la session si elle n'est pas encore démarrée
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // Enregistrer la playlist dans la session
                $_SESSION['playlist'] = $playlist;

                // Afficher la playlist et le lien pour ajouter une piste
                $renderer = new AudioListRenderer($playlist);
                $playlist_html = $renderer->render(1);

                return <<<HTML
                <h2>Playlist créée : {$playlist_name}</h2>
                {$playlist_html}
                <a href="?action=add-track">Ajouter une piste</a>
                HTML;
            } else {
                // En cas d'erreur avec le nom de la playlist
                return "<p>Erreur : Le nom de la playlist est invalide.</p>";
            }
        }

        return '';
    }
}