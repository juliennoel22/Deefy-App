<?php
/*On souhaite maintenant ajouter la possibilité de gérer des podcasts dans notre application. Un podcast est une piste audio caractérisée par les propriétés suivantes : titre, auteur, date, genre, duree et nom de fichier audio. Un podcast est créé par un constructeur recevant le titre du podcast et le chemin vers le fichier audio.•identifier les propriétés et méthodes communes entre un podcast et une piste audio d'un album. Créer en conséquence la classe AudioTrack qui regroupe ces caractéristiques communes et propose un constructeur,•modifier la classe AlbumTrack pour qu'elle hérite d'AudioTrack. Le constructeur pour la classe  AlbumTrack  doit conserver la même signature mais doit utiliser le constructeur hérité de AudioTrack.•créer la classe PodcastTrack qui hérite d'AudioTrack.•compléter le programme principal pour créer un podcast*/

namespace iutnc\deefy\audio\tracks;

require_once 'vendor/autoload.php';

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

define('AUDIO_PATH', 'musiques/');

//require_once 'C:\\xampp\\htdocs\\S3\\WEB\\TD5\\src\\classes\\exception\\InvalidPropertyNameException.php';
//require_once 'C:/xampp/htdocs/S3/WEB/TD5/src/classes/exception/InvalidPropertyValueException.php';

class AudioTrack {
    private string $titre;
    private string $auteur = "";
    private string $date;
    private string $genre = "";
    private int $duree;
    private string $nomFichierAudio;
    private string $artiste = "";
    private string $album;
    private int $annee;
    private int $numeroPiste;


    private int $id;


    public function __construct(string $titre, string $nomFichierAudio) {
        $this->titre = $titre;
        $this->nomFichierAudio = $nomFichierAudio;
//        $this->cheminFichierAudio = "audio/".$nomFichierAudio;
        $this->cheminFichierAudio = AUDIO_PATH.$nomFichierAudio;


    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        throw new InvalidPropertyNameException($property);
    }

    public function setDuree(int $duree) {
        if ($duree < 0) {
            throw new InvalidPropertyValueException('duree', $duree);
        }
        $this->duree = $duree;
    }

    public function __toString() {
        return json_encode($this);
    }
}