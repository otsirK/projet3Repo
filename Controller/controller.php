<?php

class Controller {

    private $db,
            $managerBillet,
            $managerCommentaire;

    public  $message = null ,
            $commentaire = null,
            $billet = null,
            $erreurs = null;

    public function __construct($db,$managerBillet,$managerCommentaire)
    {
        $this->db = $db;
        $this->managerBillet = $managerBillet;
        $this->managerCommentaire = $managerCommentaire;
    }

    public function execute()
    {
        /* MODIFIER UN COMMENTAIRE */

        if (isset($_GET['modifierCom']))
        {
            $this->modifierCommentaire();
        }

        /* SUPPRIMER UN COMMENTAIRE */

        if (isset($_GET['supprimerCom']))
        {
           $this->message = $this->deleteCommentaire();
        }

        /* AJOUTER UN COMMENTAIRE */

        if (isset($_POST['auteur']))
        {
            $this->ajouterCommentaire();
        }

        /* VALIDER UN COMMENTAIRE */

        if (isset($_GET['valider']))
        {
            $this->message = $this->validerCommentaire();
        }

        /* MODIFIER UN BILLET */

        if (isset($_GET['modifier']))
        {
           $this->modifierBillet();
        }

        /* SUPPRIMER UN BILLET */

        if (isset($_GET['supprimer']))
        {
            $this->message = $this->supprimerBillet();
        }

        /* AJOUTER UN BILLET */

        if (isset($_POST['titre']))
        {
            $this->ajouterBillet();
        }

        /* GESTION DES ERREURS */

        if (isset($this->erreurs) && in_array(Billet::TITRE_INVALIDE, $this->erreurs)) {
            $this->message = 'Le titre est invalide.<br />';
        }

        if (isset($this->erreurs) && in_array(Billet::CONTENU_INVALIDE, $this->erreurs)) {
            $this->message = 'Le contenu est invalide.<br />';
        }

        $this->selectionVue();
    }

    public function deleteCommentaire() {

        $this->managerCommentaire->delete((int) $_GET['supprimerCom']);

        return "Le commentaire a bien été supprimé !";
}

    public function modifierCommentaire() {

        $this->commentaire = $this->managerCommentaire->getUnique((int) $_GET['modifierCom']);

}

    public function ajouterCommentaire() {
        $commentaire = new Commentaire(
            [
                'auteur' => htmlspecialchars($_POST['auteur']),
                'contenu' => htmlspecialchars($_POST['contenu'])
            ]
        );

        if (isset($_POST['id'])) {
            $commentaire->setId($_POST['id']);
        }

        if ($commentaire->isValid()) {
            $this->managerCommentaire->save($commentaire);

            $this->message = $commentaire->isNew() ? 'Le commentaire a bien été ajouté !' : 'Le commentaire a bien été modifié !';
        } else {
            $this->erreurs = $commentaire->getErreurs();
        }}

        public function validerCommentaire() {

            $this->managerCommentaire->valide((int) $_GET['valider']);
            return "Le commentaire a bien été validé !";
        }

    public function modifierBillet() {
       $this->billet = $this->managerBillet->getUnique((int) $_GET['modifier']);

    }

    public function supprimerBillet() {

        $this->managerBillet->delete((int) $_GET['supprimer']);

        return "Le billet a bien été supprimé !";
    }

    public function ajouterBillet() {
        $billet = new Billet(
            [
                'titre' => htmlspecialchars($_POST['titre']),
                'contenu' => $_POST['contenu']
            ]
        );

        if (isset($_POST['id']))
        {
            $billet->setId($_POST['id']);
        }

        if ($billet->isValid())
        {
            $this->managerBillet->save($billet);

            $this->message = $billet->isNew() ? 'Le billet a bien été ajouté !' : 'Le billet a bien été modifié !';
        }
        else
        {
            $this->erreurs = $billet->getErreurs();
        }


    }

    public function selectionVue() {
        /* DEFINITION DES VARIABLES */
        $message = $this->message;
        $commentaire = $this->commentaire;
        $billet = $this->billet;
        $listeBillets = $this->managerBillet->getList();
        $listeDerniersBillets = $this->managerBillet->getList(0, 5);
        $listeSignale = $this->managerCommentaire->getListeSignale();
        $listeDerniersCom = $this->managerCommentaire->getList(0, 5);

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
                $listeCommentaires = $this->managerCommentaire->getList();
                $viewAdminCommentaires = new viewAdminCommentaires($listeCommentaires, $listeSignale);
                $viewAdminCommentaires->display($message, $commentaire);
                break;
        }
    }

}