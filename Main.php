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


print "<h1>AudioList</h1>";
$podcast = new tracks\PodcastTrack("My Podcast", "01-Im_with_you_BB-King-Lucille.mp3", 555);
$piste1 = new tracks\AlbumTrack("Already Rich1", "02-I_Need_Your_Love-BB_King-Lucille.mp3", "Already Rich", 1, 145);
$piste2 = new tracks\AlbumTrack("Already Rich2", "01-Im_with_you_BB-King-Lucille.mp3", "Already Rich", 2, 145);
$piste3 = new tracks\AlbumTrack("Already Rich3", "01-Im_with_you_BB-King-Lucille.mp3", "Already Rich", 3, 145);
$piste4 = new tracks\AlbumTrack("Already Rich4", "01-Im_with_you_BB-King-Lucille.mp3", "Already Rich", 4, 145);
$piste5 = new tracks\AlbumTrack("Already Rich5", "01-Im_with_you_BB-King-Lucille.mp3", "Already Rich", 5, 145);
$playlist = new lists\Playlist("Ma playlist");
$playlist->ajouterPiste($piste5);
$playlist->ajouterListePistes([$piste1, $piste2, $piste3, $piste4, $podcast]);
$renderer = new render\AudioListRenderer($playlist);
echo $renderer->render(1);

//TD11


// J'ai passé l'exo 3 du TD 11

//tD12
session_start();
// Créer une instance de Dispatcher et exécuter la méthode run
$dispatcher = new Dispatcher();
$dispatcher->run();

//TD13

// Définir la configuration de la BD au démarrage de l'application
DeefyRepository::setConfig('Config.db.ini');

// Récupérer une instance pour effectuer une action
$r = DeefyRepository::getInstance();

echo "<h2>Liste des playlists</h2>";
$pl = $r->findAllPlaylists(); // Exemple d'appel d'une méthode


//$audiolist = new lists\AudioList($pl);
//
//print_r($audiolist);
//
//$renderer2 = new render\AudioListRenderer($audiolist);
//
//echo $renderer2->renderCompact();

// Créer une instance d'AudioList pour chaque playlist
foreach ($pl as $playlistData) {
    // Assurez-vous d'accéder correctement aux propriétés de l'objet Playlist
    $audioList = new AudioList(
        $playlistData->nom,      // Le nom de la playlist
        $playlistData->pistes,   // Un tableau d'objets de type Track (AlbumTrack ou PodcastTrack)
    // Vous pouvez ajouter d'autres propriétés si nécessaires comme 'nombrePistes', 'dureeTotale'
    );

    // Créer un renderer pour afficher cette AudioList
    $renderer2 = new render\AudioListRenderer($audioList);

    // Afficher l'AudioList sous forme compacte
    echo $renderer2->renderCompact();
}
echo "<h2>Sauvegarder une playslit vide de pistes</h2>";

$repository = DeefyRepository::getInstance();

// Créer une nouvelle playlist (vide de pistes)
$playlist_vide = new lists\Playlist("Ma nouvelle playlist");

// Sauvegarder cette playlist dans la base de données
if ($repository->saveEmptyPlaylist($playlist_vide)) {
    echo "La playlist a été sauvegardée avec succès !";
    echo "ID de la playlist : " . $playlist_vide->getId() . "</br>";
} else {
    echo "Erreur lors de la sauvegarde de la playlist.";
}

echo "<h2>Supprimer des playlists</h2>";

// Récupérer une instance du repository
$repository3 = DeefyRepository::getInstance();

// Définir les bornes des ID à supprimer
$a = 5; // ID de départ
$b = 16; // ID de fin

// Boucle pour supprimer les playlists dans la plage spécifiée
for ($id = $a; $id <= $b; $id++) {
    // Appel de la méthode pour supprimer la playlist
    $result = $repository3->deletePlaylist($id);

    // Vérification du succès de la suppression
    if ($result) {
        echo "Playlist avec ID $id a été supprimée avec succès.<br>";
    } else {
        echo "Erreur lors de la suppression de la playlist avec ID $id.<br>";
    }
}

//echo "<h2>Ajouter un track</h2>";
//
//// Récupérer une instance du repository
//$repository4 = DeefyRepository::getInstance();
//
//// Créer une nouvelle piste
//$track = new tracks\AlbumTrack("Already Rich1", "02-I_Need_Your_Love-BB_King-Lucille.mp3", "Already Rich", 1, 145);
//
//$result = $repository4->saveTrack($track);
//
//if ($result) {
//    echo "La piste a été ajoutée avec succès !";
//} else {
//    echo "Erreur lors de l'ajout de la piste.";
//}
//
//echo "<h2>Lier une piste existante à une playlist existante </h2>";
//
//// Récupérer une instance du repository
//$repository5 = DeefyRepository::getInstance();
//
//// ID de la piste à ajouter et de la playlist
//$idTrack = 11;  // Exemple : ID de la piste existante
//$idPlaylist = 17;  // Exemple : ID de la playlist existante
//
//// Appeler la méthode pour ajouter la piste à la playlist
//$result = $repository5->addExistingTrackToEndOfPlaylist($idTrack, $idPlaylist);
//
//// Vérifier si l'ajout a réussi ou échoué
//if ($result) {
//    echo "Piste ajoutée à la playlist avec succès !\n";
//} else {
//    echo "Échec de l'ajout de la piste à la playlist.\n";
//}

//$hashedPassword = password_hash('user1', PASSWORD_BCRYPT);
//echo $hashedPassword;
//echo "Arret";


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

//// Afficher chaque playlist en utilisant renderCompact()
//foreach ($pl as $playlist) {
//    // Ici on passe chaque objet Playlist à la méthode renderCompact
//    echo $renderer2->renderCompact([$playlist]);
//}


echo "<h2>Inscription</h2>";






/*

    //Exo 2
    $piste1 = new AlbumTrack('Piste1', 'Chemin1', 'Album1', 1);
    $piste2 = new AlbumTrack('Piste2', 'Chemin2', 'Album2', 2);


    echo $piste1->titre . ' ' . $piste1->nomFichierAudio . ' ' . $piste1->album . ' ' . $piste1->numeroPiste . '<br>';

    echo $piste2->titre . ' ' . $piste2->nomFichierAudio . ' ' . $piste2->album . ' ' . $piste2->numeroPiste . '<br>';
    echo  "\n--------\n";
    echo  "\n--------\n";

    echo $piste1->__toString() . '<br>';

    echo $piste2 . '<br>';
    echo  "\n--------\n";
    echo  "\n--------\n";

    print_r($piste1);
    echo  "\n--------\n";
    var_dump($piste2);

    //Exo 3
    echo  "\n--------\n";
    echo "</br>";
    $t = new AlbumTrack("Imwithyou", "01-Im_with_you_BB-King-Lucille.mp3", "Im with you", 1) ;
    $r = new AlbumTrackRenderer( $t );

    print $r->render( 1 ) ;   // 1 = mode compact
    print $r->render( 2 ) ;   // 2 = mode long


    echo "<h2>Création d'un podcast</h2>";

    //$podcast = new PodcastTrack("Podcast1", "01-Podcast1.mp3");
    $podcast = new PodcastTrack("Podcast1", "02-I_Need_Your_Love-BB_King-Lucille.mp3");
    $podcast_r = new PodcastRenderer($podcast);

    print $podcast_r->render( 1 ) ; // 1 = mode compact
    print $podcast_r->render( 2 ) ; // 2 = mode long


echo "\n--------\n";
echo "</br>";

try {
    $audioTrack = new AudioTrack("Titre de la piste", "fichier.mp3");
    $audioTrack->setDuree(-10); // Ceci déclenchera InvalidPropertyValueException
    echo $audioTrack->unknownProperty; // Ceci déclenchera InvalidPropertyNameException
} catch (InvalidPropertyNameException $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
} catch (InvalidPropertyValueException $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
} catch (Exception $e) {
    echo "Erreur inattendue : " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}


echo "\n</br>----------</br>\n";
*/