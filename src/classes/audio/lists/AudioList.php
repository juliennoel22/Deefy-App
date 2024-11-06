<?php

namespace iutnc\deefy\audio\lists;
class AudioList
{
    protected string $nom;
    protected $nombrePistes;
    protected $dureeTotale;
    protected $pistes = [];

    protected ?int $id = null;

    public function __construct(string $nom, $pistes = [])
    {
        $this->nom = $nom;
        $this->pistes = $pistes;
        $this->nombrePistes = count($pistes);
        $this->dureeTotale = $this->calculerDureeTotale();
        $this->id = null; // Par défaut, on n’a pas d'ID assigné
    }

    // Setter pour l'ID
    public function setId(int $id): void {
        $this->id = $id;
    }

    // Getter pour l'ID
    public function getId(): ?int {
        return $this->id;
    }

    // Setter pour le nom
    public function setNom(string $nom): void {
        $this->nom = $nom;
    }
    // Getter pour le nom
    public function getNom(): string {
        return $this->nom;
    }

    protected function calculerDureeTotale()
    {
        $duree = 0;
        foreach ($this->pistes as $piste) {
            $duree += $piste->duree; // suppose que chaque piste a une propriété `duree`
        }
        return $duree;
    }

    public function __get($prop)
    {
        if (property_exists($this, $prop)) {
            return $this->$prop;
        }
        throw new Exception("Propriété inexistante: " . $prop);
    }
}

