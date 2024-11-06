<?php
namespace iutnc\deefy\render;

require_once 'Renderer.php';

abstract class AudioTrackRenderer implements Renderer{

    protected $track;

    // Constructeur prenant un objet de type AudioTrack
    public function __construct($track) {
        $this->track = $track;
    }

    // Méthode render suivant le patron template
    public function render(int $selector): string {
        switch($selector) {
            case 1:
                return $this->renderCompact();
            case 2:
                return $this->renderLong();
            default:
                return $this->renderCompact();
        }
    }

    // Méthodes abstraites pour le rendu spécifique
    abstract public function renderCompact(): string;
    abstract public function renderLong(): string;

}
