<?php
namespace iutnc\deefy\action;

use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\render\AudioListRenderer;

class AddPodcastTrackAction extends Action
{
    public function execute(): string
    {
        // Vérifiez si la requête est de type GET pour afficher le formulaire
        if ($this->http_method === 'GET') {
            return <<<HTML
                <form method="post" action="?action=add-track" enctype="multipart/form-data">
                    <label for="titre">Titre de la piste :</label>
                    <input type="text" id="titre" name="titre" required>
                   
                    <label for="userfile">Fichier audio :</label>
                    <input type="file" id="userfile" name="userfile" accept=".mp3" required>

                    <label for="duree">Duree :</label>
                    <textarea id="duree" name="duree"></textarea>

                    <button type="submit">Ajouter la piste</button>
                </form>
            HTML;
        } elseif ($this->http_method === 'POST') {
            // Récupérer et nettoyer les données du formulaire
            $titre = filter_var($_POST['titre'], FILTER_SANITIZE_STRING);
            $duree = filter_var($_POST['duree'], FILTER_SANITIZE_STRING);

            // Vérification du fichier audio
            if (isset($_FILES['userfile'])) {
                // Vérifiez s'il n'y a pas d'erreur lors de l'upload
                if ($_FILES['userfile']['error'] === UPLOAD_ERR_OK) {
                    $fileTmpPath = $_FILES['userfile']['tmp_name'];
                    $fileName = $_FILES['userfile']['name'];
                    $fileType = $_FILES['userfile']['type'];

                    // Vérification de l'extension et du type de fichier
                    if (substr($fileName, -4) === '.mp3' && $fileType === 'audio/mpeg') {
                        // Génération d'un nom de fichier aléatoire
                        $newFileName = uniqid('audio_', true) . '.mp3';
                        $destPath = './musiques/' . $newFileName;

                        // Déplacement du fichier téléchargé vers le répertoire audio
                        if (move_uploaded_file($fileTmpPath, $destPath)) {
                            // Instancier une nouvelle PodcastTrack et définir ses attributs
                            $track = new PodcastTrack($titre, $newFileName, $duree);

                            // Récupérer la playlist depuis la session
                            if (isset($_SESSION['playlist'])) {
                                $playlist = $_SESSION['playlist'];

                                // Ajouter la nouvelle piste à la playlist
                                $playlist->ajouterPiste($track);

                                // Réenregistrer la playlist mise à jour dans la session
                                $_SESSION['playlist'] = $playlist;

                                // Utiliser AudioListRenderer pour afficher la playlist
                                $renderer = new AudioListRenderer($playlist);
                                $html = $renderer->render(1);

                                // Ajouter le lien pour ajouter une autre piste
                                $html .= '<a href="?action=add-track">Ajouter encore une piste</a>';

                                return $html;
                            } else {
                                return "<p>Erreur : aucune playlist trouvée en session.</p>";
                            }
                        } else {
                            return "<p>Erreur lors du téléchargement du fichier audio.</p>";
                        }
                    } else {
                        return "<p>Erreur : le fichier doit être au format MP3.</p>";
                    }
                } else {
                    return "<p>Erreur lors du téléchargement : " . $_FILES['userfile']['error'] . "</p>";
                }
            } else {
                return "<p>Erreur : aucune donnée de fichier audio n'a été soumise.</p>";
            }
        }
        return '';
    }
}
