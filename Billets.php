<?php

class Billets
{
	private   $id,
			  $titre,
			  $contenu,
			  $dateAjout;

  const TITRE_INVALIDE = 1;
  const CONTENU_INVALIDE = 2;


  public function __construct()
  {

  }

// SETTERS //

public function setId($id)
{
	$this->id = (int) $id;
}

public function setTitre($titre)
{
	if (!is_string($titre) || empty($titre))
	{
		$this->erreurs[] = self::TITRE_INVALIDE;
	}
	else {
		$this->titre = $titre;
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
  
}