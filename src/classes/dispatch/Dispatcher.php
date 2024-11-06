<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\AddUserAction;


class Dispatcher
{
    private string $action;

    public function __construct()
    {
        // Récupérer la valeur de "action" dans la query-string, ou "default" si absent
        $this->action = $_GET['action'] ?? 'default';
    }

    public function run(): void
    {
        switch ($this->action) {
            case  'add-user':
                $actionInstance = new AddUserAction();
                break;
            case 'playlist':
                $actionInstance = new DisplayPlaylistAction();
                break;

            case 'add-playlist':
                $actionInstance = new AddPlaylistAction();
                break;

            case 'add-track':
                $actionInstance = new AddPodcastTrackAction();
                break;

            case 'default':
            default:
                $actionInstance = new DefaultAction();
                break;
        }

        // Exécuter la méthode execute() de l'instance d'action et afficher le résultat
        $html = $actionInstance->execute();
        $this->renderPage($html);
    }


    private function renderPage(string $html): void
    {

        $siteTitle = "Deefy Appppp";
        $menu = <<<HTML
        <nav>
            <ul>
                <li><a href="?action=default">Accueil</a></li>
                <li><a href="?action=add-user">Inscription</a></li>
                <li><a href="?action=add-playlist">Créer une Playlist</a></li>
                <li><a href="?action=add-track">Uploader AlbumTrack</a></li>
                <li><a href="?action=add-cover">Uploader Image</a></li>
            </ul>
        </nav>
    HTML;

        // Construire la page complète avec le titre et le menu
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>$siteTitle</title>
        </head>
        <body>
            <header>
                <h1>$siteTitle</h1>
                $menu
            </header>
            <main>
                $html
            </main>
        </body>
        </html>
    HTML;
    }
}
