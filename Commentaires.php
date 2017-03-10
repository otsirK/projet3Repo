<?php

class Commentaires
{
	private   $id,
              $depth,
              $parentId,
			  $auteur,
			  $contenu,
			  $dateAjout,
              $sousCommentaire,
              $erreurs = [];


  const AUTEUR_INVALIDE = 1;
  const CONTENU_INVALIDE = 2;

  public function __construct($valeurs = [])
  {
      if (!empty($valeurs))
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
        return !(empty($this->auteur) || empty($this->contenu));
    }


  // SETTERS //

public function setId($id)
{
	$this->id = (int) $id;
}

public function setDepth($depth)
    {
        $this->depth = (int) $depth;
    }

    public function setParentId($parentId)
    {
        $this->parentId = (int) $parentId;
    }


public function setAuteur($auteur)
{
	if (!is_string($auteur) || empty($auteur))
    {
        $this->erreurs[] = self::AUTEUR_INVALIDE;
    }
    else {
        $this->auteur = $auteur;
    }
}


public function setContenu($contenu)
{
	if (!is_string($contenu) || empty($contenu))
    {
      $this->erreurs[] = self::CONTENU_INVALIDE;
    }
    else
    {
      $this->contenu = $contenu;
    }
}

public function setDateAjout(DateTime $dateAjout)
{
	$this->dateAjout = $dateAjout;
}

public function setSousCommentaire($sousCommentaire)
{
    $this->sousCommentaire = $sousCommentaire;
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

  public function getDepth()
    {
        return $this->depth;
    }

  public function getParentId()
    {
        return $this->parentId;
    }

  public function getAuteur()
  {
    return $this->auteur;
  }

  
  public function getContenu()
  {
    return $this->contenu;
  }
  
  public function getDateAjout()
  {
    return $this->dateAjout;
  }

    public function getSousCommentaire()
    {
        return $this->sousCommentaire;
    }

}