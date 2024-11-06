<?php

namespace iutnc\deefy\audio\lists;

class Album extends AudioList
{
    private $artiste;
    private $dateDeSortie;

    public function __construct($nom, $pistes, $artiste = null, $dateDeSortie = null)
    {
        if (empty($pistes)) {
            throw new Exception("Un album doit contenir au moins une piste.");
        }
        parent::__construct($nom, $pistes);
        $this->artiste = $artiste;
        $this->dateDeSortie = $dateDeSortie;
    }

    public function setArtiste($artiste)
    {
        $this->artiste = $artiste;
    }

    public function setDateDeSortie($date)
    {
        $this->dateDeSortie = $date;
    }
}