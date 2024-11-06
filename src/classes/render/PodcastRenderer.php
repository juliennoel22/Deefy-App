<?php
namespace iutnc\deefy\render;

require_once 'AudioTrackRenderer.php';
class PodcastRenderer extends AudioTrackRenderer {

    // Implémentation de renderCompact pour les podcasts
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

    // Implémentation de renderLong pour les podcasts
    public function renderLong(): string {
        return "
        <div>
            <h3>{$this->track->titre}</h3>
            <em><p>Mode Long</p></em>
            <audio controls>
                <source src='{$this->track->cheminFichierAudio}' type='audio/mpeg'>
                Votre navigateur ne supporte pas la balise audio.
            </audio> 
        </div>
        ";
    }
}
