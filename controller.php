<?php

class Controller {

   /* public function executeCommentaires()
    {
        $db = DBFactory::getMysqlConnexionWithPDO();
        $manager2 = new CommentairesManager($db);
        $commentaires = null;


        if (isset($_GET['modifier']))
        {
            $commentaires = $manager2->getUnique((int) $_GET['modifier']);
        }

        if (isset($_GET['supprimer']))
        {
            $manager2->delete((int) $_GET['supprimer']);
            $message = 'Le commentaire a bien été supprimé !';
        }
    }*/


    public function execute()
    {

        $db = DBFactory::getMysqlConnexionWithPDO();
        $managerBillet = new BilletsManager($db);
        $managerCommentaire = new CommentairesManager($db);
        $message = null;
        $billets = null;
        $commentaires = null;
        if (isset($_GET['modifierCom']))
        {
            $commentaires = $managerCommentaire->getUnique((int) $_GET['modifierCom']);
        }

        if (isset($_GET['supprimerCom']))
        {
            $managerCommentaire->delete((int) $_GET['supprimerCom']);
            $message = 'Le commentaire a bien été supprimé !';
        }

        if (isset($_POST['auteur'])) {
            $commentaires = new Commentaires(
                [
                    'auteur' => $_POST['auteur'],
                    'contenu' => $_POST['contenu']
                ]
            );

            if (isset($_POST['id'])) {
                $commentaires->setId($_POST['id']);
            }

            if ($commentaires->isValid()) {
                $managerCommentaire->save($commentaires);

                $message = $commentaires->isNew() ? 'Le commentaire a bien été ajouté !' : 'Le commentaire a bien été modifié !';
            } else {
                $erreurs = $commentaires->getErreurs();
            }
        }

        if (isset($_GET['valider']))
        {
            $managerCommentaire->valide((int) $_GET['valider']);
            $message = 'Le billet a bien été validé !';
        }

        if (isset($_GET['modifier']))
        {
            $billets = $managerBillet->getUnique((int) $_GET['modifier']); //billet
        }

        if (isset($_GET['supprimer']))
        {
            $managerBillet->delete((int) $_GET['supprimer']);
            $message = 'Le billet a bien été supprimé !';
        }

        if (isset($_POST['titre']))
        {
            $billets = new Billets(
                [
                    'titre' => $_POST['titre'],
                    'contenu' => $_POST['contenu']
                ]
            );

            if (isset($_POST['id']))
            {
                $billets->setId($_POST['id']);
            }

            if ($billets->isValid())
            {
                $managerBillet->save($billets);

                $message = $billets->isNew() ? 'Le billet a bien été ajouté !' : 'Le billet a bien été modifié !';
            }
            else
            {
                $erreurs = $billets->getErreurs();
            }
        }

        if (isset($erreurs) && in_array(Billets::TITRE_INVALIDE, $erreurs)) {
            $message = 'Le titre est invalide.<br />';
        }

        if (isset($erreurs) && in_array(Billets::CONTENU_INVALIDE, $erreurs)) {
            $message = 'Le contenu est invalide.<br />';
        }


        $listeBillets = $managerBillet->getList();
        $listeDerniersBillets = $managerBillet->getList(0, 5);
        $listeSignale = $managerCommentaire->getListeSignale();
        $listeDerniersCom = $managerCommentaire->getList(0, 5);
        //$id = $billets->getId();
        //$this->message = $message;

        if(isset($_GET['num'])) {
            $numOnglet = $_GET['num'];
        }
        else{
            $numOnglet=1;
        }

        switch($numOnglet) {
            case 1:

        $viewAdmin = new ViewAdmin($listeDerniersBillets, $listeSignale, $listeDerniersCom);
        $viewAdmin->display($message); //supprimer message
        break;

            case 2:
            $viewAdminBillets = new viewAdminBillets($listeBillets);
            $viewAdminBillets->display($message,$billets);
            break;

            case 3:
                $listeCommentaires = $managerCommentaire->getList();
                $viewAdminCommentaires = new viewAdminCommentaires($listeCommentaires, $listeSignale);
                $viewAdminCommentaires->display($message, $commentaires);
                break;
        }
    }



}