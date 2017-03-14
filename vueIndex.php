<?php

class ViewAdmin
{
    private $listeBillets,
            $listeSignale,
            $message;


    public function __construct($listeBillets,$listeSignale,$manager)
    {
        $this->listeBillets = $listeBillets;
        $this->listeSignale = $listeSignale;
        $this->manager = $manager;
        //$this->setMessage($message);
        //var_dump($message);
    }

    public function display($message)
    {
        ?>
        <!DOCTYPE html>
        <html>
    <head>
        <title>Administration</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>

    <div class="admin-top">
        <h1 class="admin-title">Interface d'administration</h1>
    </div>

    <div class="navbar navbar-default">
        <ul class="nav navbar-nav">
            <li class="active"> <a href="admin.php">Accueil administration</a>
            <li> <a href="http://127.0.0.1/projet3Repo/admin.php?num=2">Gestion des billets</a> </li>
            <li> <a href="http://127.0.0.1/projet3Repo/admin.php?num=3">Gestion des commentaires</a> </li>
            <li> <a href="index.php">Retour au blog</a> </li>

        </ul>
    </div>

    <div class="container">
                <?php
                var_dump($message);
                if (isset($message))
                    //if ($message != null)
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
                $commentaires->getDateAjout(),'</td><td><a href="?modifier=', $commentaires->getId(), '" target="_blank">Modifier</a> | <a href="?supprimer=', $commentaires->getId(), '">Supprimer</a></td></tr>', "\n";
            }
            ?>
        </table>

        <span class="titreAdmin"><h2>Ajouter un billet</h2></span>

                <form class="formulaire" action="admin.php" method="post">
            <p>

                Titre : <input type="text" name="titre" value="<?php if (isset($billets)) echo $billets->getTitre(); ?>" /><br />

                Contenu :<br /><textarea rows="8" cols="60" name="contenu"><?php if (isset($billets)) echo $billets->getContenu(); ?></textarea><br />

                <?php
                if(isset($billets) && !$billets->isNew())
                {
                    ?>
                    <input type="hidden" name="id" value="<?= $billets->getId() ?>" />
                    <input type="submit" value="Modifier" name="modifier" />
                    <?php
                }
                else
                {
                    ?>
                    <input type="submit" class="btn btn-default" value="Ajouter" />
                    <?php
                }
                ?>
            </p>
        </form>
        <span class="titreAdmin"><h2>Les 8 derniers billets publiés</h2></span>

        <table class="table table-striped">
            <tr><th>Titre</th><th>Contenu</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
              <?php
        foreach ($this->listeBillets as $billets) {
            echo '<tr><td>', $billets->getTitre(), '</td><td>', substr ($billets->getContenu(), 0, 250), ' ...', '</td><td>',
            $billets->getDateAjout()->format('d/m/Y à H\hi'), '</td><td>', ($billets->getDateAjout()->format('d/m/Y à H\hi')),
            '</td><td><a href="?modifier=', $billets->getId(), '">Modifier</a> | <a href="?supprimer=', $billets->getId(), '">Supprimer</a></td></tr>', "\n";
        }
?>
</table>


        <h2>Commentaires signalés</h2>

        <table class="table table-striped">
            <tr><th>Auteur</th><th>Commentaire</th><th>Date d'ajout</th><th>Action</th></tr>

            <?php
            foreach($this->listeSignale as $commentaires)
            {
                echo '<tr ><td>',$commentaires->getAuteur(), '</td><td>', substr ($commentaires->getContenu(), 0, 250), '</td><td>',
                $commentaires->getDateAjout(),'</td><td><a href="?modifier=', $commentaires->getId(), '" target="_blank">Modifier</a> | <a href="?supprimer=', $commentaires->getId(), '">Supprimer</a></td></tr>', "\n";
            }
            ?>
        </table>
    </div>
        </body>
        </html>
        <?php
    }
}