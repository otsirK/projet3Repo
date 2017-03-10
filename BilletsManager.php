<?php
class BilletsManager
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

	public function add(Billets $billets)
	{
	    $requete = $this->db->prepare('INSERT INTO billets(titre, contenu, dateAjout) VALUES (:titre, :contenu, NOW())');
		$requete->bindValue(':titre', $billets->getTitre());
        $requete->bindValue(':contenu', $billets->getContenu());
        //$requete->bindValue(':dateAjout', $billets->getDateAjout());

        $requete->execute();

	}

    public function count()
    {
        return $this->db->query('SELECT COUNT(*) FROM billets')->fetchColumn();
    }

	public function delete($id)
	{
		$this->db->exec('DELETE FROM billets WHERE id = '.(int)$id);
	}


	public function getList($debut = -1, $limite = -1)
{
    $sql = 'SELECT id, titre, contenu, dateAjout, dateModif From billets ORDER BY id ASC';

    if ($debut != -1 || $limite != -1)
{
    $sql = ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut; //+=
}

    $requete = $this->db->query('SELECT id, titre, contenu, dateAjout, dateModif From billets ORDER BY id ASC');
    $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Billets');

    $listeBillets = $requete->fetchAll();

    // On parcourt notre liste de Billets pour pouvoir placer des instances de DateTime en guise de dates d'ajout et de modification.
    foreach ($listeBillets as $billets)
    {
        $billets->setDateAjout(new DateTime($billets->getDateAjout()));
        $billets->setDateModif(new DateTime($billets->getDateModif()));
    }

    $requete->closeCursor();

    return $listeBillets;
}

    public function getListLast($debut = -1, $limite = -1)
    {
        $sql = 'SELECT id, titre, contenu, dateAjout From billets LIMIT 0,5 ORDER BY id ASC';

        if ($debut != -1 || $limite != -1)
        {
            $sql = ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
        }

        $requete = $this->db->query('SELECT id, titre, contenu, dateAjout From billets ORDER BY id ASC');
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Billets');

        $listeBillets = $requete->fetchAll();

        // On parcourt notre liste de Billets pour pouvoir placer des instances de DateTime en guise de dates d'ajout et de modification.
        foreach ($listeBillets as $billets)
        {
            $billets->setDateAjout(new DateTime($billets->getDateAjout()));
            $billets->setDateModif(new DateTime($billets->getDateModif()));
        }

        $requete->closeCursor();

        return $listeBillets;
    }

	public function getUnique($id)
    {
        $requete = $this->db->prepare('SELECT id, titre, contenu, dateAjout FROM billets WHERE id = :id');
        $requete->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Billets');

        $billets = $requete->fetch();

        $billets->setDateAjout(new DateTime($billets->getDateAjout()));
        $billets->setDateModif(new DateTime($billets->getDateModif()));

        return $billets;
    }

    public function save(Billets $billets)
    {
        if ($billets->isValid())
        {
            $billets->isNew() ? $this->add($billets) : $this->update($billets);
        }
        else
        {
            throw new RuntimeException('Le billet doit être valide pour être enregistré');
        }
    }


    public function update(Billets $billets)
    {
        $requete = $this->db->prepare('UPDATE billets SET titre = :titre, contenu = :contenu, dateModif = NOW() WHERE id = :id');

        $requete->bindValue(':titre', $billets->getTitre());
        $requete->bindValue(':contenu', $billets->getContenu());
        $requete->bindValue(':id', $billets->getId(), PDO::PARAM_INT);

        $requete->execute();
    }
}
