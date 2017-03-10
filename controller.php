<?php

class Controller {

    public function executeCommentaires()
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
    }


    public function execute()
    {

        $db = DBFactory::getMysqlConnexionWithPDO();
        $manager = new BilletsManager($db);
        $manager2 = new CommentairesManager($db);
        $message = null;
        $billets = null;


        if (isset($_GET['modifier']))
        {
            $billets = $manager->getUnique((int) $_GET['modifier']); //billet
        }

        if (isset($_GET['supprimer']))
        {
            $manager->delete((int) $_GET['supprimer']);
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
                $manager->save($billets);

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


        $listeBillets = $manager->getList();
        $listeSignale = $manager2->getListeSignale();
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

        $viewAdmin = new ViewAdmin($listeBillets, $listeSignale, $manager);
        $viewAdmin->display($message); //supprimer message
        break;

            case 2:
            $viewAdminBillets = new viewAdminBillets($listeBillets); //supprimer manager, ajouter $billets
            $viewAdminBillets->display($message,$billets);
            break;

            case 3:
                $listeCommentaires = $manager2->getList();
                $viewAdminCommentaires = new viewAdminCommentaires($listeCommentaires, $listeSignale);
                $viewAdminCommentaires->display($message, $commentaires);
                break;
        }
    }



}