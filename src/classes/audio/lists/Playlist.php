<?php

namespace iutnc\deefy\audio\lists;

class Playlist extends AudioList
{
    public function ajouterPiste($piste)
    {
        $this->pistes[] = $piste;
        $this->nombrePistes++;
        $this->dureeTotale += $piste->duree;
    }

    public function supprimerPiste($indice)
    {
        if (isset($this->pistes[$indice])) {
            $this->dureeTotale -= $this->pistes[$indice]->duree;
            unset($this->pistes[$indice]);
            $this->pistes = array_values($this->pistes); // Réindexer le tableau
            $this->nombrePistes--;
        } else {
            throw new Exception("Indice de piste invalide.");
        }
    }

    public function ajouterListePistes($nouvellesPistes)
    {
        foreach ($nouvellesPistes as $piste) {
            if (!in_array($piste, $this->pistes, true)) { // Vérification de doublon
                $this->ajouterPiste($piste);
            }
        }
    }
}
