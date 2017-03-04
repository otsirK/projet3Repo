<?php

class ViewAdminBillets
{
    private $listeBillets,
            $listeSignale;


    public function __construct($listeBillets, $listeSignale)
    {
        $this->listeBillets = $listeBillets;
        $this->listeSignale = $listeSignale;
    }

    public function display($message)
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Administration</title>
            <meta charset="utf-8"/>
            <link rel="stylesheet" href="style.css">
            <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
            <script src="lib/tinymce/tinymce.min.js"></script>
            <script>
                tinymce.init({
                    selector: '#mytextarea',
                    language : "fr_FR"
                    //themes : "modern"
                });
            </script>
        </head>

        <body>

        <div class="admin-top">
            <h1 class="admin-title">Interface d'administration</h1>
        </div>

        <div class="navbar navbar-default">
            <ul class="nav navbar-nav">
                <li><a href="admin.php">Accueil administration</a>
                <li class="active"><a href="http://127.0.0.1/projet%203/admin.php?num=2">Gestion des billets</a></li>
                <li><a href="http://127.0.0.1/projet%203/admin.php?num=3">Gestion des commentaires</a></li>
                <li><a href="index.php">Retour au blog</a></li>

            </ul>
        </div>

        <div class="container">

            <?php
            if (isset($message))
            {
                echo '<div class ="alert-danger">', $message, '</div>';
            }
            ?>

            <h2>Ajouter un billet</h2>
            <div class="formAdminBillet">
            <form action="#" method="post">
                <p>

                    <label>Titre :</label> <br/><input type="text" class="champsTitre" name="titre"
                                   value="<?php if (isset($billets)) echo $billets->getTitre(); ?>"/><br/>

                    <label>Contenu :</label><br/><textarea id="mytextarea" rows="8" cols="60"
                                            name="contenu"><?php if (isset($billets)) echo $billets->getContenu(); ?></textarea><br/>
                    <?php
                    if (isset($billets) && !$billets->isNew()) {
                        ?>
                        <input type="hidden" name="id" value="<?= $billets->getId() ?>"/>
                        <input type="submit" value="Modifier" name="modifier"/>
                        <?php
                    } else {
                        ?>
                        <input type="submit" class="btn btn-default" value="Ajouter"/>
                        <?php
                    }
                    ?>
                </p>
            </form></div>
            <h2>Tous les billets publiés</h2>

            <table class="table table-striped">
                <tr>
                    <th>Titre</th>
                    <th>Contenu</th>
                    <th>Date d'ajout</th>
                    <th>Dernière modification</th>
                    <th>Action</th>
                </tr>
                <?php
                foreach ($this->listeBillets as $billets) {
                    echo '<tr><td>', $billets->getTitre(), '</td><td>', substr($billets->getContenu(), 0, 250), ' ...', '</td><td>',
                    $billets->getDateAjout()->format('d/m/Y à H\hi'), '</td><td>', ($billets->getDateAjout()->format('d/m/Y à H\hi')),
                    '</td><td><a href="?modifier=', $billets->getId(), '" target="_blank">Modifier</a> | <a href="?supprimer=', $billets->getId(), '">Supprimer</a></td></tr>', "\n";
                }
                ?>


            </table>



        </div>
        </body>
        </html>
        <?php
    }
}