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

    public function display($message)
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Administration</title>
            <meta content="text/html; charset=UTF-8" http-equiv="Content-Type"/>
            <link rel="stylesheet" href="style.css">
            <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        </head>

        <body>

        <div class="admin-top">
            <h1 class="admin-title">Interface d'administration</h1>
        </div>

        <div class="navbar navbar-default">
            <ul class="nav navbar-nav">
                <li> <a href="admin.php">Accueil administration</a>
                <li> <a href="http://127.0.0.1/projet%203/admin.php?num=2">Gestion des billets</a> </li>
                <li class="active"> <a href="http://127.0.0.1/projet%203/admin.php?num=3">Gestion des commentaires</a> </li>
                <li> <a href="index.php">Retour au blog</a> </li>

            </ul>
        </div>

        <div class="container">

            <?php
            if (isset($message))
            {
                echo '<div class ="alert-danger">', $message, '</div>';
            }
            ?>

            <span class="titreAdmin"><h2>Commentaires signalés</h2></span>

            <table class="table table-striped">
                <tr><th>Auteur</th><th>Commentaire</th><th>Date d'ajout</th><th>Action</th></tr>

                <?php
                foreach($this->listeSignale as $commentaires)
                {
                    echo '<tr><td>',$commentaires->getAuteur(), '</td><td>', substr ($commentaires->getContenu(), 0, 250), '</td><td>',
                    $commentaires->getDateAjout(),'</td><td><a href="?modifier=', $commentaires->getId(), '" target="_blank">Modifier</a> | <a href="?supprimer=', $commentaires->getId(), '">Supprimer</a></td></tr>', "\n";
                }
                ?></table>

            <span class="titreAdmin"><h2>Les 10 derniers commentaires</h2></span>

            <table class="table table-striped">
                <tr><th>Auteur</th><th>Commentaires</th><th>Date d'ajout</th><th>Action</th></tr>
                <?php
                foreach ($this->listeCommentaires as $commentaires) {
                    echo '<tr><td>', $commentaires->getAuteur(), '</td><td>', substr ($commentaires->getContenu(), 0, 250),'</td><td>',
                    $commentaires->getDateAjout()->format('d/m/Y à H\hi'), '</td><td><a href="?modifier=', $commentaires->getId(), '" target="_blank">Modifier</a> | <a href="?supprimer=', $commentaires->getId(), '">Supprimer</a></td></tr>', "\n";
                }
                ?>
            </table>

        </div>
        </body>
        </html>
        <?php
    }
}