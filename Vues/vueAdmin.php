<?php

class ViewAdmin
{

    public function __construct($listeDerniersBillets,$listeSignale,$listeDerniersCom)
    {
        $this->listeDerniersBillets = $listeDerniersBillets;
        $this->listeSignale = $listeSignale;
        $this->listeDerniersCom = $listeDerniersCom;

    }

    public function display($message)
    {
        ?>
        <!DOCTYPE html>
        <html>
    <head>
        <title>Administration</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="../../Web/css/style.css">
        <link href="../../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>

    <div class="admin-top">
        <h1 class="admin-title">Interface d'administration</h1>
    </div>

    <div class="navbar navbar-default">
        <ul class="nav navbar-nav">
            <li class="active"> <a href="admin.php">Accueil administration</a>
            <li> <a href="http://127.0.0.1/projet3Repo/Web/admin/admin.php?num=2">Gestion des billets</a> </li>
            <li> <a href="http://127.0.0.1/projet3Repo/Web/admin.php?num=3">Gestion des commentaires</a> </li>
            <li> <a href="../index.php">Retour au blog</a> </li>

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
                echo '<tr ><td>',$commentaires->getAuteur(), '</td><td>', substr ($commentaires->getContenu(), 0, 250), '</td><td>',
                $commentaires->getDateAjout(),'</td><td><a href="?num=3&modifierCom=', $commentaires->getId(),
                '">Modifier</a> | <a href="http://127.0.0.1/projet3Repo/Web/admin/admin.php?num=3&supprimerCom=\', $commentaires->getId(), \'">Supprimer</a></td></tr>', "\n";
            }
            ?>
        </table>


        <span class="titreAdmin"><h2>Les 5 derniers billets publiés</h2></span>

        <table class="table table-striped">
            <tr><th>Titre</th><th>Contenu</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
              <?php
        foreach ($this->listeDerniersBillets as $billets) {
            echo '<tr><td>', $billets->getTitre(), '</td><td>', substr ($billets->getContenu(), 0, 250), ' ...', '</td><td>',
            $billets->getDateAjout()->format('d/m/Y à H\hi'), '</td><td>', ($billets->getDateAjout()->format('d/m/Y à H\hi')),
            '</td><td><a href="?num=2&modifier=', $billets->getId(), '">Modifier</a> | <a href="?num=2&supprimer=',$billets->getId(),'">Supprimer</a></td></tr>', "\n";
        }
?>
</table>

        <!-- TABLEAU DES 5 DERNIERS COMMENTAIRE -->

        <h2>Les 5 derniers commentaires</h2>

        <table class="table table-striped">
            <tr><th>Auteur</th><th>Commentaire</th><th>Date d'ajout</th><th>Action</th></tr>

            <?php
            foreach($this->listeDerniersCom as $commentaires)
            {
                echo '<tr ><td>',$commentaires->getAuteur(), '</td><td>', substr ($commentaires->getContenu(), 0, 250), '</td><td>',
                ($commentaires->getDateAjout()->format('d/m/Y à H\hi')),'</td><td><a href="?num=3&modifierCom=', $commentaires->getId(),
                '">Modifier</a> | <a href="http://127.0.0.1/projet3Repo/Web/admin/admin.php?num=3&supprimerCom=', $commentaires->getId(), '">Supprimer</a></td></tr>', "\n";
            }
            ?>
        </table>
    </div>
        </body>
        </html>
        <?php
    }
}