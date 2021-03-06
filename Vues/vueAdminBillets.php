<?php

class ViewAdminBillets
{
    private $listeBillets;

    public function __construct($listeBillets)
    {
        $this->listeBillets = $listeBillets;
    }

    public function display($message,$billets)
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Administration</title>
            <meta charset="utf-8"/>
            <link rel="stylesheet" href="../../Web/css/style.css">
            <link href="../../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
            <script src="../../lib/tinymce/tinymce.min.js"></script>
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
                <li class="active"><a href="http://127.0.0.1/projet3Repo/Web/admin/admin.php?num=2">Gestion des billets</a></li>
                <li><a href="http://127.0.0.1/projet3Repo/Web/admin/admin.php?num=3">Gestion des commentaires</a></li>
                <li><a href="../index.php">Retour au blog</a></li>

            </ul>
        </div>

        <div class="container">

            <?php
            /* AFFICHAGE DES MESSAGES D INFORMATION */

            if (isset($message))
            {
                echo '<div class ="alert-danger">', $message, '</div>';
            }
            ?>

            <span class="titreAdmin"><h2>Ajouter un billet</h2></span>

            <!-- FORMULAIRE POUR AJOUTER UN BILLET -->

            <div class="formAdminBillet">
            <form action="http://127.0.0.1/projet3Repo/Web/admin/admin.php?num=2" method="post">


                    <label>Titre :</label> <br/><input type="text" class="champsTitre" name="titre"
                                   value="<?php if (isset($billets)) echo $billets->getTitre(); ?>"/><br/>

                    <label>Contenu :</label><br/><textarea id="mytextarea" rows="8" cols="60"
                                            name="contenu"><?php if (isset($billets)) echo $billets->getContenu(); ?></textarea><br/>
                    <?php
                    if (isset($billets) && !$billets->isNew()) {
                        ?>
                        <p><input type="hidden" name="id" value="<?= $billets->getId() ?>"/>
                        <input type="submit" class="btn btn-default" value="Modifier" name="modifier"/></p>
                        <?php
                    } else {
                        ?>
                <p><input type="submit" class="btn btn-default" value="Ajouter"/></p>
                        <?php
                    }
                    ?>

            </form></div>
            <span class="titreAdmin"><h2>Tous les billets publiés</h2></span>

            <table class="table table-striped">
                <tr>
                    <th>Titre</th>
                    <th>Contenu</th>
                    <th>Date d'ajout</th>
                    <th>Dernière modification</th>
                    <th>Action</th>
                </tr>
                <?php

                /* RECUPERATIONS DE TOUS LES BILLETS PUBLIES */

                foreach ($this->listeBillets as $billets) {
                    echo '<tr><td>', $billets->getTitre(), '</td><td>', substr($billets->getContenu(), 0, 250), ' ...', '</td><td>',
                    $billets->getDateAjout()->format('d/m/Y à H\hi'), '</td><td>', ($billets->getDateModif()->format('d/m/Y à H\hi')),
                    '</td><td><a href="?num=2&modifier=', $billets->getId(), '" >Modifier</a> | <a href="?num=2&supprimer=', $billets->getId(), '">Supprimer</a></td></tr>', "\n";
                }
                ?>


            </table>



        </div>
        </body>
        </html>
        <?php
    }
}