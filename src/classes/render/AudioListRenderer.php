<?php

namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\tracks as tracks;

class AudioListRenderer implements Renderer
{
    private AudioList $audioList;

    public function __construct(AudioList $audioList)
    {
        $this->audioList = $audioList;
    }

    public function render(int $selector): string
    {
        return $this->renderCompact();
    }

    public function renderCompact(): string
//    {
//        $html = "<div class='audio-list'>";
//        $html .= "<h2>{$this->audioList->__get('nom')}</h2>";
//
//        $html .= "<ul>";
//        foreach ($this->audioList->__get('pistes') as $piste) {
//            $html .= "<li>{$piste->nom} - {$piste->duree} secondes</li>";
//        }
//        $html .= "</ul>";
//
//        $html .= "<p>Nombre de pistes : {$this->audioList->__get('nombrePistes')}</p>";
//        $html .= "<p>Durée totale : {$this->audioList->__get('dureeTotale')} secondes</p>";
//        $html .= "</div>";
//
//        return $html;
//    }
    {
        $html = "<div>";
        $html .= "<h3>{$this->audioList->nom} :</h3>";
        foreach ($this->audioList->pistes as $piste) {
            if ($piste instanceof tracks\AlbumTrack) {
                $renderer = new AlbumTrackRenderer($piste);
            } elseif ($piste instanceof tracks\PodcastTrack) {
                $renderer = new PodcastRenderer($piste);
            }
            $html .= $renderer->renderCompact();
        }

        $html .= "<p><strong>Nombre de pistes :</strong> {$this->audioList->nombrePistes}</p>";
        $html .= "<p><strong>Durée totale :</strong> {$this->audioList->dureeTotale} secondes</p>";
        $html .= "</div>";
        return $html;
    }

    public function renderLong(): string
    {
        return $this->renderCompact();
    }
}