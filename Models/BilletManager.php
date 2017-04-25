<?php
class BilletManager
{
	private $db;

    /**
     * BilletManager constructor.
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /* Ajout d'un billet */

	public function add(Billet $billet)
	{
	    $requete = $this->db->prepare('INSERT INTO billets(titre, contenu, dateAjout, dateModif) VALUES (:titre, :contenu, NOW(), NOW())');
		$requete->bindValue(':titre', $billet->getTitre());
        $requete->bindValue(':contenu', $billet->getContenu());

        $requete->execute();

	}

	/* Compter le nombre de billets */

    public function count()
    {
        return $this->db->query('SELECT COUNT(*) FROM billets')->fetchColumn();
    }

	public function delete($id)
	{
        $requete = $this->db->prepare('DELETE FROM billets WHERE id = :id');
        $requete->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $requete->execute();
	}

	/* Liste de billets par id */

	public function getList($debut = -1, $limite = -1)
{
    $sql = 'SELECT id, titre, contenu, dateAjout, dateModif From billets ORDER BY id DESC';

    if ($debut != -1 || $limite != -1)
{
    $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut; //+=
}
    $requete = $this->db->query($sql);

    //$requete = $this->db->query('SELECT id, titre, contenu, dateAjout, dateModif From billets ORDER BY id ASC');
    $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Billet');

    $listeBillets = $requete->fetchAll();

    // On parcourt notre liste de Billets pour pouvoir placer des instances de DateTime en guise de dates d'ajout et de modification.
    foreach ($listeBillets as $billet)
    {
        $billet->setDateAjout(new DateTime($billet->getDateAjout()));
        $billet->setDateModif(new DateTime($billet->getDateModif()));
    }

    $requete->closeCursor();

    return $listeBillets;
}

// A supprimer
    public function getListLast($debut = -1, $limite = -1)
    {
        $sql = 'SELECT id, titre, contenu, dateAjout From billets LIMIT 0,5 ORDER BY id ASC';

        if ($debut != -1 || $limite != -1)
        {
            $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
        }
        $requete = $this->db->query($sql);
        //$requete = $this->db->query('SELECT id, titre, contenu, dateAjout From billets ORDER BY id ASC');
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Billet');

        $listeBillets = $requete->fetchAll();

        // On parcourt notre liste de Billets pour pouvoir placer des instances de DateTime en guise de dates d'ajout et de modification.
        foreach ($listeBillets as $billet)
        {
            $billet->setDateAjout(new DateTime($billet->getDateAjout()));
            $billet->setDateModif(new DateTime($billet->getDateModif()));
        }

        $requete->closeCursor();

        return $listeBillets;
    }

    /* Récupérer un billet avec l'id */

	public function getUnique($id)
    {
        $requete = $this->db->prepare('SELECT id, titre, contenu, dateAjout FROM billets WHERE id = :id');
        $requete->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Billet');

        $billet = $requete->fetch();

        $billet->setDateAjout(new DateTime($billet->getDateAjout()));
        //$billets->setDateModif(new DateTime($billets->getDateModif()));

        return $billet;
    }

    /* Sauvegegarder un billet dans la bdd */

    public function save(Billet $billet)
    {
        if ($billet->isValid())
        {
            $billet->isNew() ? $this->add($billet) : $this->update($billet);
        }
        else
        {
            throw new RuntimeException('Le billet doit être valide pour être enregistré');
        }
    }

    /* Mettre à jour la bdd */

    public function update(Billet $billet)
    {
        $requete = $this->db->prepare('UPDATE billets SET titre = :titre, contenu = :contenu, dateModif = NOW() WHERE id = :id');

        $requete->bindValue(':titre', $billet->getTitre());
        $requete->bindValue(':contenu', $billet->getContenu());
        $requete->bindValue(':id', $billet->getId(), PDO::PARAM_INT);

        $requete->execute();
    }
}
