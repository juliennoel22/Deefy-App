<?php
//Exo 1
/*Construire une classe nommée AlbumTrack pour représenter les pistes audio d'un album. Chaque piste est décrite par les propriétés suivantes, correspondant pour la plupart aux tags ID3v2 habituellement inclus dans des fichiers audio : titre, artiste, album, année, numéro de piste (dans l'album), genre, duree (en secondes), nom du fichier audio.•pour l'instant tous les attributs sont publics,•prévoir un constructeur recevant uniquement le titre de la piste et le chemin du fichier audio, le nom de l'album, et le numéro de piste dans l'album,•programmer une méthode pour transformer l'objet en une chaine de caractères. On appellera la méthode __toString() et on utilise la fonction php json_encode pour produire une chaine de caractère au format json.•nommer le fichier AlbumTrack.php.*/
namespace iutnc\deefy\audio\tracks;
require_once 'AudioTrack.php';

class AlbumTrack extends AudioTrack{

    // Faire gaffe  au chemin dufichier audio qui doit être en fait le nom du fichier audio
    public function __construct(string $titre, string $nomFichierAudio, string $album, int $numeroPiste, int $duree) {
        parent::__construct($titre, $nomFichierAudio);
        $this->album = $album;
        $this->numeroPiste = $numeroPiste;

        $this->duree = $duree;
    }

    public function __toString() {
        return json_encode($this);
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return parent::__get($property);
    }
}