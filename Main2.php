<?php


/*Construire un programme qui utilise cette classe. Faites impérativement un fichier séparé (principe PSR-1) et utilisez require_once pour charger la définition de la classe dans ce programme.Ce programme : •crée deux pistes et valorise leus attributs,•affiche les informations de chaque piste en accédant aux différents attributs, et en utilisant les constructions et fonctions echo, print, printf. Examiner la documentation PHPpour comprendre les différences entre ces 3 instructions.•affiche les objets complets en un seul appel, en utilisant la fonction __toString() (par exemple echo $t1->__toString(); ), puis sans utiliser cette méthode (echo $t1 ; ) ; tester avec echo puis print_r puis var_dump*/

// Inclusion des fichiers nécessaires
//require_once 'AlbumTrack.php';
//require_once 'AlbumTrackRenderer.php';
//require_once 'PodcastTrack.php';
//require_once 'PodcastRenderer.php';
//require_once 'AudioTrack.php';
//require_once 'AlbumTrack.php';
//require_once 'PodcastTrack.php';
//require_once 'AudioList.php';
//require_once 'Album.php';
//require_once 'Playlist.php';
//require_once 'AudioListRenderer.php';

//use iutnc\deefy\audio\lists\Album;
//use iutnc\deefy\audio\lists\Playlist;
//use iutnc\deefy\audio\lists\AudioList;
//
//use iutnc\deefy\audio\tracks\AudioTrack;
//use iutnc\deefy\audio\tracks\AlbumTrack;
//use iutnc\deefy\audio\tracks\PodcastTrack;
//
//use iutnc\deefy\exception\InvalidPropertyNameException;
//use iutnc\deefy\exception\InvalidPropertyValueException;
//
//use iutnc\deefy\render\AlbumTrackRenderer;
//use iutnc\deefy\render\PodcastRenderer;
//use iutnc\deefy\render\AudioListRenderer;
//use iutnc\deefy\audio\lists\AudioTrackRenderer;

require_once 'vendor/autoload.php';

use iutnc\deefy\audio\tracks as tracks;
use iutnc\deefy\audio\lists as lists;
use iutnc\deefy\render as render;
use iutnc\deefy\dispatch\Dispatcher;

use \iutnc\deefy\repository\DeefyRepository;

use iutnc\deefy\audio\lists\AudioList;

use iutnc\deefy\auth\AuthnProvider;


session_start();
// Créer une instance de Dispatcher et exécuter la méthode run
$dispatcher = new Dispatcher();
$dispatcher->run();


// Définir la configuration de la BD au démarrage de l'application
DeefyRepository::setConfig('Config.db.ini');

// Récupérer une instance pour effectuer une action
$r = DeefyRepository::getInstance();



echo "<h2>Authentification</h2>";

// Si la méthode HTTP est GET, on affiche le formulaire de login
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo '<form method="POST" action="?action=login">
            Email: <input type="text" name="email">
            Mot de passe: <input type="password" name="password">
            <button type="submit">Se connecter</button>
          </form>';
}

// Si la méthode HTTP est POST, on tente d'authentifier l'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        iutnc\deefy\auth\AuthnProvider::signin($email, $password);
        echo "Bienvenue, $email ! Vous êtes authentifié.";
    } catch (iutnc\deefy\AuthnException $e) {
        echo $e->getMessage();
    }
}




echo "<h2>Inscription</h2>";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $authnProvider = new AuthnProvider();
        $authnProvider->register($email, $password); // Appel de la méthode d'enregistrement
        echo "Inscription réussie pour $email !";
    } catch (iutnc\deefy\exception\AuthnException $e) {
        echo "Erreur :";
        echo $e->getMessage(); // Afficher l'erreur si elle se produit
        echo "Erreur :";

    }
}

