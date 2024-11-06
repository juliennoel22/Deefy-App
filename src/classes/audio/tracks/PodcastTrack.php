<?php
namespace iutnc\deefy\audio\tracks;
require_once 'AudioTrack.php';

class PodcastTrack extends AudioTrack {

    public function __construct(string $titre, string $nomFichierAudio, int $duree)
{
    parent::__construct($titre, $nomFichierAudio);
    $this->duree = $duree;
}


}