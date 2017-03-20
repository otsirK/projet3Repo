<?php

class Controller {

    public function execute()
    {

        $db = DBFactory::getMysqlConnexionWithPDO();
        $managerBillet = new BilletManager($db);
        $managerCommentaire = new CommentaireManager($db);
        $message = null;
        $billet = null;
        $commentaire = null;

        /* MODIFIER UN COMMENTAIRE */
        if (isset($_GET['modifierCom']))
        {
            $commentaire = $managerCommentaire->getUnique((int) $_GET['modifierCom']);
        }

        /* SUPPRIMER UN COMMENTAIRE */
        if (isset($_GET['supprimerCom']))
        {

            $managerCommentaire->delete((int) $_GET['supprimerCom']);
            $message = 'Le commentaire a bien été supprimé !';
        }

        /* AJOUTER UN COMMENTAIRE */
        if (isset($_POST['auteur'])) {
            $commentaire = new Commentaire(
                [
                    'auteur' => $_POST['auteur'],
                    'contenu' => $_POST['contenu']
                ]
            );

            if (isset($_POST['id'])) {
                $commentaire->setId($_POST['id']);
            }

            if ($commentaire->isValid()) {
                $managerCommentaire->save($commentaire);

                $message = $commentaire->isNew() ? 'Le commentaire a bien été ajouté !' : 'Le commentaire a bien été modifié !';
            } else {
                $erreurs = $commentaire->getErreurs();
            }
        }

        /* VALIDER UN COMMENTAIRE */
        if (isset($_GET['valider']))
        {
            $managerCommentaire->valide((int) $_GET['valider']);
            $message = 'Le commentaire a bien été validé !';
        }

        /* MOIDIFIER UN BILLET */
        if (isset($_GET['modifier']))
        {
            $billet = $managerBillet->getUnique((int) $_GET['modifier']); //billet
        }

        /* SUPPRIMR UN BILLET */
        if (isset($_GET['supprimer']))
        {
            $managerBillet->delete((int) $_GET['supprimer']);
            $message = 'Le billet a bien été supprimé !';
        }

        /* AJOUTER UN BILLET */
        if (isset($_POST['titre']))
        {
            $billet = new Billet(
                [
                    'titre' => $_POST['titre'],
                    'contenu' => $_POST['contenu']
                ]
            );

            if (isset($_POST['id']))
            {
                $billet->setId($_POST['id']);
            }

            if ($billet->isValid())
            {
                $managerBillet->save($billet);

                $message = $billet->isNew() ? 'Le billet a bien été ajouté !' : 'Le billet a bien été modifié !';
            }
            else
            {
                $erreurs = $billet->getErreurs();
            }
        }

        /* GESTION DES ERREURS */
        if (isset($erreurs) && in_array(Billet::TITRE_INVALIDE, $erreurs)) {
            $message = 'Le titre est invalide.<br />';
        }

        if (isset($erreurs) && in_array(Billet::CONTENU_INVALIDE, $erreurs)) {
            $message = 'Le contenu est invalide.<br />';
        }

        /* DEFINITION DES VARIABLES */
        $listeBillets = $managerBillet->getList();
        $listeDerniersBillets = $managerBillet->getList(0, 5);
        $listeSignale = $managerCommentaire->getListeSignale();
        $listeDerniersCom = $managerCommentaire->getList(0, 5);

        /* GESTION DES VUES */
        if(isset($_GET['num'])) {
            $numOnglet = $_GET['num'];
        }
        else{
            $numOnglet=1;
        }

        switch($numOnglet) {
            case 1:

        $viewAdmin = new ViewAdmin($listeDerniersBillets, $listeSignale, $listeDerniersCom);
        $viewAdmin->display($message);
        break;

            case 2:
            $viewAdminBillets = new viewAdminBillets($listeBillets);
            $viewAdminBillets->display($message,$billet);
            break;

            case 3:
                $listeCommentaires = $managerCommentaire->getList();
                $viewAdminCommentaires = new viewAdminCommentaires($listeCommentaires, $listeSignale);
                $viewAdminCommentaires->display($message, $commentaire);
                break;
        }
    }



}