<?php
/*On souhaite maintenant gérer l'affichage HTML d'une piste audio d'un album. A cette fin, on va créer la classe AlbumTrackRenderer dont le rôle sera de gérer les différents modes d'affichage d'une  piste (par exemple mode "compact" pour un affichage en liste, mode "long" pour un affichage en plein écran). Cette classe présente les caractéristiques suivantes : •le constructeur reçoit un objet de type AlbumTrack•la conversion en html est réalisée par la méthode render(int $selector): string qui reçoit un sélecteur permettant de choisir le mode d'affichage et retourne un fragment de codehtml correspondant à l'affichage de la piste (penser à utiliser la balise html <audio>),•la conversion effective est réalisée pour chaque mode par une méthode privée qui est appelée par la méthode render selon le cas.Cette classe s'utilise donc de la manière suivante : $t = new AlbumTrack("thriller", "thriller.flac") ; $r = new AlbumTrackRenderer( $t );print $r->render( 1 ) ;   // 1 = mode compact*/
namespace iutnc\deefy\render;

require_once 'AudioTrackRenderer.php';
class AlbumTrackRenderer extends AudioTrackRenderer {

    // Implémentation de renderCompact pour les pistes d'album
    public function renderCompact(): string {
        return "
        <div>
            <h3>{$this->track->titre}</h3>
            <em><p>Mode Court</p></em>
            <audio controls>
                <source src='{$this->track->cheminFichierAudio}' type='audio/mpeg'>
                Votre navigateur ne supporte pas la balise audio.
            </audio> 
        </div>
        ";
    }

    // Implémentation de renderLong pour les pistes d'album
    public function renderLong(): string {
        return "
        <div>
            <h3>{$this->track->titre}</h3>
            <em><p>Mode Long</p></em>
            <p>Album : {$this->track->album}</p>
            <p>Numéro de piste :{$this->track->numeroPiste}</p>
            <audio controls>
                <source src='{$this->track->cheminFichierAudio}' type='audio/mpeg'>
                Votre navigateur ne supporte pas la balise audio.
            </audio> 
        </div>
        ";
    }
}