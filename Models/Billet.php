<?php

class Billet
{
    private $id,
        $titre,
        $contenu,
        $dateAjout,
        $dateModif,
        $erreurs = [];


    const TITRE_INVALIDE = "Le titre est invalide !</br>";
    const CONTENU_INVALIDE = "Le contenu est invalide !";


    public function __construct($valeurs = [])
    {
        if (!empty($valeurs)) // Si on a spécifié des valeurs, alors on hydrate l'objet.
        {
            $this->hydrate($valeurs);
        }
    }

    public function hydrate($donnees)
    {
        foreach ($donnees as $attribut => $valeur)
        {
            $methode = 'set'.ucfirst($attribut);

            if (is_callable([$this, $methode]))
            {
                $this->$methode($valeur);
            }
        }
    }

    public function isNew()
    {
        return empty($this->id);
    }

    public function isValid()
    {
        return !(empty($this->titre) || empty($this->contenu));
    }

// SETTERS //

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    public function setTitre($titre)
    {
        if (!is_string($titre) || empty($titre)) {
            $this->erreurs[] = self::TITRE_INVALIDE;
        } else {
            $this->titre = $titre;
        }
    }

    public function setContenu($contenu)
    {
        if (!is_string($contenu) || empty($contenu)) {
            $this->erreurs[] = self::CONTENU_INVALIDE;
        } else {
            $this->contenu = $contenu;
        }
    }

    public function setDateAjout(DateTime $dateAjout)
    {
        $this->dateAjout = $dateAjout;
    }

    public function setDateModif(DateTime $dateModif)
    {
        $this->dateModif = $dateModif;
    }

// GETTERS //

    public function getErreurs()
    {
        return $this->erreurs;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function getContenu()
    {
        return $this->contenu;
    }

    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    public function getDateModif()
    {
        return $this->dateModif;
    }
}