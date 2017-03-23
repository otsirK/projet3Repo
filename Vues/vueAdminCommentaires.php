<?php

class ViewAdminCommentaires
{
    private $listeCommentaires,
            $listeSignale;


    public function __construct($listeCommentaires,$listeSignale)
    {
        $this->listeCommentaires = $listeCommentaires;
        $this->listeSignale = $listeSignale;
}

    public function display($message, $commentaires)
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Administration</title>
            <meta content="text/html; charset=UTF-8" http-equiv="Content-Type"/>
            <link rel="stylesheet" href="../../Web/css/style.css">
            <link href="../../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        </head>

        <body>

        <div class="admin-top">
            <h1 class="admin-title">Interface d'administration</h1>
        </div>

        <div class="navbar navbar-default">
            <ul class="nav navbar-nav">
                <li> <a href="admin.php">Accueil administration</a>
                <li> <a href="http://127.0.0.1/projet3Repo/Web/admin/admin.php?num=2">Gestion des billets</a> </li>
                <li class="active"> <a href="http://127.0.0.1/projet3Repo/Web/admin/admin.php?num=3">Gestion des commentaires</a> </li>
                <li> <a href="../index.php">Retour au blog</a> </li>

            </ul>
        </div>

        <div class="container">

            <?php
            /* AFFICHAGE DES MESSAGES D INFORMATIONS*/
            if (isset($message))
            {
                echo '<div class ="alert-danger">', $message, '</div>';
            }

            /* AFFICHAGE D UN FORMULAIRE SI 'modifierCom' EST PRESENT DANS L URL */
        if (isset($_GET['modifierCom'])) {
            ?>
            <div class="formAdminCommentaire">
            <form action="http://127.0.0.1/projet3Repo/Web/admin/admin.php?num=3" method="post">


                <label>Titre :</label> <br/><input type="text" class="champsTitre" name="auteur"
                                                   value="<?php if (isset($commentaires)) echo $commentaires->getAuteur(); ?>"/><br/>

                <label>Contenu :</label><br/><textarea id="mytextarea" rows="8" cols="60"
                                                       name="contenu"><?php if (isset($commentaires)) echo $commentaires->getContenu(); ?></textarea><br/>
                <?php
                if (isset($commentaires) && !$commentaires->isNew()) {
                    ?>
                    <input type="hidden" name="id" value="<?= $commentaires->getId() ?>"/>
                    <input type="submit" value="Modifier" name="modifier"/>
                    <?php
                } else {
                    ?>
                    <p><input type="submit" class="btn btn-default" value="Ajouter"/></p>
                    <?php
                }
                ?></form></div>
        <?php }
        ?>

            <span class="titreAdmin"><h2>Commentaires signalés</h2></span>

            <table class="table table-striped">
                <tr><th>Auteur</th><th>Commentaire</th><th>Date d'ajout</th><th>Action</th></tr>

                <?php
                /* RECUPERATION DES COMMENTAIRES SIGNALES */
                foreach($this->listeSignale as $commentaires)
                {
                    echo '<tr><td>',$commentaires->getAuteur(), '</td><td>', substr ($commentaires->getContenu(), 0, 250), '</td><td>',
                    $commentaires->getDateAjout(),'</td><td><a href="?num=3&modifierCom=', $commentaires->getId(), '">Modifier</a> | <a href="http://127.0.0.1/projet3Repo/Web/admin/admin.php?num=3&supprimerCom=', $commentaires->getId(), '">Supprimer</a> | <a href="http://127.0.0.1/projet3Repo/Web/admin/admin.php?num=3&valider=',$commentaires->getId(),'">Valider</a></td></tr>', "\n";
                }
                ?></table>

            <span class="titreAdmin"><h2>Tous les commentaires</h2></span>

            <table class="table table-striped">
                <tr><th>Auteur</th><th>Commentaires</th><th>Date d'ajout</th><th>Action</th></tr>
                <?php
                /* RECUPERATION DE TOUS LES COMMENTAIRES*/
                foreach ($this->listeCommentaires as $commentaires) {
                    echo '<tr><td>', $commentaires->getAuteur(), '</td><td>', substr ($commentaires->getContenu(), 0, 250),'</td><td>',
                    $commentaires->getDateAjout()->format('d/m/Y à H\hi'), '</td><td><a href="?num=3&modifierCom=', $commentaires->getId(), '">Modifier</a> | <a href="http://127.0.0.1/projet3Repo/Web/admin/admin.php?num=3&supprimerCom=', $commentaires->getId(), '">Supprimer</a></td></tr>', "\n";
                }
                ?>
            </table>

        </div>
        </body>
        </html>
        <?php
    }
}