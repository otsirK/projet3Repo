 <?php
 
 class Router {
	 
	public function route() {
		
		$db = DBFactory::getMysqlConnexionWithPDO();
		$managerBillet = new BilletManager($db);
		$managerCommentaire = new CommentaireManager($db);
		
		$message = null;
		$controller = new Controller($db,$managerBillet,$managerCommentaire);
		
		if (isset($_GET['modifierCom']))
        {
            $controller->modifierCommentaire();
        }
        /* SUPPRIMER UN COMMENTAIRE */
        if (isset($_GET['supprimerCom']))
        {
           $message = $controller->deleteCommentaire();
        }
        /* AJOUTER UN COMMENTAIRE */
        if (isset($_POST['auteur']))
        {
            $message = $controller->ajouterCommentaire();
        }
        /* VALIDER UN COMMENTAIRE */
        if (isset($_GET['valider']))
        {
            $message = $controller->validerCommentaire();
        }
        /* MODIFIER UN BILLET */
        if (isset($_GET['modifier']))
        {
           $controller->modifierBillet();
        }
        /* SUPPRIMER UN BILLET */
        if (isset($_GET['supprimer']))
        {
            $message = $controller->supprimerBillet();
        }
        /* AJOUTER UN BILLET */
        if (isset($_POST['titre']))
        {
            $message = $controller->ajouterBillet();
        }
        /* GESTION DES ERREURS */
        if (isset($this->erreurs) && in_array(Billet::TITRE_INVALIDE, $this->erreurs)) {
            $message = 'Le titre est invalide.<br />';
        }
        if (isset($this->erreurs) && in_array(Billet::CONTENU_INVALIDE, $this->erreurs)) {
            $message = 'Le contenu est invalide.<br />';
        }
		
        $controller->selectionVue($message);
	}
 }