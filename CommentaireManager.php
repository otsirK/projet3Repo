<?php
class CommentaireManager
{
	private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /* AJOUT D UN COMMENTAIRE */

    public function add(Commentaire $commentaire)
    {
        $requete = $this->db->prepare('INSERT INTO commentaires(parentId, auteur, contenu, dateAjout) VALUES (:parentId, :auteur, :contenu, NOW())');
        $requete->bindValue(':parentId', $commentaire->getParentId());
        $requete->bindValue(':auteur', $commentaire->getAuteur());
        $requete->bindValue(':contenu', $commentaire->getContenu());

        $requete->execute();
    }

    /* SUPPRIMER UN COMMENTAIRE */

    public function delete($id)
    {
        $this->db->exec('DELETE FROM commentaires WHERE id = '.(int)$id);
    }

    /* NOMBRE DE COMMENTAIRE TOTAL */

    public function count()
    {
        return $this->db->query('SELECT COUNT(*) FROM commentaires')->fetchColumn();
    }

    /* NOMBRE DE COMMENTAIRES PAR ID PARENT */

    public function countById()
    {

        return $this->db->query('SELECT COUNT(*) FROM commentaires WHERE parentId = :parentId')->fetchColumn();

    }

    /* LISTE DES COMMENTAIRES */

    public function getList($debut = -1, $limite = -1)
    {
        $sql = 'SELECT id, auteur, contenu, dateAjout From commentaires ORDER BY id DESC';

        if ($debut != -1 || $limite != -1)
        {
            $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;//ecrase
        }

        $requete = $this->db->query($sql);
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Commentaire');

        $listeCommentaires = $requete->fetchAll();


        foreach ($listeCommentaires as $commentaire)
        {
            $commentaire->setDateAjout(new DateTime($commentaire->getDateAjout()));
            //$commentaires->setDateModif(new DateTime($billets->dateModif()));
        }

        $requete->closeCursor();

        return $listeCommentaires;
    }

    /* RECUPERER UN COMMENTAIRE */

    public function getUnique($id)
    {
        $requete = $this->db->prepare('SELECT id, auteur, contenu, dateAjout FROM commentaires WHERE id = :id');
        $requete->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Commentaire');

        $commentaire = $requete->fetch();

        $commentaire->setDateAjout(new DateTime($commentaire->getDateAjout()));
        //$commentaires->setDateModif(new DateTime($news->dateModif()));

        return $commentaire;
    }

    /* RECUPERER LES COMMENTAIRES D UN BILLET */

    public function getCommentsByParentId ($parentId )
        {
            $requete = $this->db->prepare('SELECT id, auteur, contenu, dateAjout FROM commentaires WHERE parentId = :parentId AND depth = 0');
            $requete->bindValue(':parentId', (int) $parentId, PDO::PARAM_INT);
            $requete->execute();

            $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Commentaire');

            $listeCommentairesId = $requete->fetchAll();


            foreach ($listeCommentairesId as $commentaire)
            {
                $commentaire->setDateAjout(new DateTime($commentaire->getDateAjout()));
                $this->getSubComments($commentaire);
                //$commentaires->setDateModif(new DateTime($billets->dateModif()));
            }

            $requete->closeCursor();
            return $listeCommentairesId;
        }

        /* RECUPERER LES SOUS COMMENTAIRES D UN COMMENTAIRE */

    public function getSubComments($commentaire) {


        $requete = $this->db->prepare('SELECT id, auteur, contenu, dateAjout FROM commentaires WHERE parentId = :parentId AND depth > 0');
        $requete->bindValue(':parentId', (int) $commentaire->getId(), PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Commentaire');
        $listeSousCommentaires = $requete->fetchAll();

        foreach ($listeSousCommentaires as $sousCommentaire) {
            $sousCommentaire->setDateAjout(new DateTime($sousCommentaire->getDateAjout()));
            $this->getSubComments($sousCommentaire);
        }
        $commentaire->setSousCommentaire($listeSousCommentaires);
        $requete->closeCursor();
    }

    /* NOMBRE DE COMMENTAIRE PAR PARENT ID */

    public function getCountByParentId ($parentId )
    {
        $requete = $this->db->prepare('SELECT COUNT(*) FROM commentaires WHERE parentId = :parentId');
        $requete->bindValue(':parentId', (int) $parentId, PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Commentaire');

        $nbCommentaires= $requete->fetchColumn();

        $requete->closeCursor();

        return $nbCommentaires;
    }

    /* SAUVEGARDER UN COMMENTAIRE */

    public function save(Commentaire $commentaire)
    {
        if ($commentaire->isValid())
        {
            $commentaire->isNew() ? $this->add($commentaire) : $this->update($commentaire);
        }
        else
        {
            throw new RuntimeException('Le commentaire doit être valide pour être enregistré');
        }
    }

    /* METTRE A JOUR UN COMMENTAIRE */

    public function update(Commentaire $commentaire)
    {
        $requete = $this->db->prepare('UPDATE commentaires SET auteur = :auteur, contenu = :contenu /*dateModif = NOW()*/ WHERE id = :id');

        $requete->bindValue(':auteur', $commentaire->getAuteur());
        $requete->bindValue(':contenu', $commentaire->getContenu());
        $requete->bindValue(':id', $commentaire->getId(), PDO::PARAM_INT);

        $requete->execute();
    }

    /* RECUPERE LES COMMENTAIRES SIGNALES */

    public function getListeSignale($debut = -1, $limite = -1)
    {
        $sql = 'SELECT * FROM `commentaires` WHERE estSignale = 1';

        if ($debut != -1 || $limite != -1)
        {
            $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
        }

        $requete = $this->db->query($sql);
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Commentaire');
        $listeSignale = $requete->fetchAll();

        $requete->closeCursor();

        return $listeSignale;
    }

    /* SIGNALER UN COMMENTAIRE */

    public function signale($id)
    {
        $this->db->exec('UPDATE commentaires SET estSignale = TRUE WHERE id = '.(int)$id);
    }

    /* VALIDER UN COMMENTAIRE */

    public function valide($id)
    {
        $this->db->exec('UPDATE commentaires SET estSignale = FALSE WHERE id = '.(int)$id);
    }
}
