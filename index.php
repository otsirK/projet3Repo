<?php
require 'autoload.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new BilletsManager($db); //$billetManager
$manager2 = new CommentairesManager($db); //$commentaireManager

if (isset($_POST['auteur']))
{
    $commentaires = new Commentaires(
        [
            'auteur' => $_POST['auteur'],
            'contenu' => $_POST['contenu'],
            'parentId' => $_POST['parentId']
        ]
    );

    if (isset($_POST['id']))
    {
        $commentaires->setId($_POST['id']);
    }

    if ($commentaires->isValid())
    {
        $manager2->save($commentaires);
        $message = $commentaires->isNew() ? 'Le commentaire a bien été ajouté !' : 'La news a bien été modifiée !';
    }
    else
    {
        $erreurs = $commentaires->getErreurs();
    }
}

if (isset($_GET['signaler']))
{
    $manager2->signale((int) $_GET['signaler']);
    $message = 'Le billet a bien été signalé !';
}

?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Accueil du site</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="style.css">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">


    </head>

    <body>

    <div class="navbar navbar-default">
        <ul class="nav navbar-nav">
            <li class="active"> <a href="index.php">Accueil</a> </li>
            <li> <a href="admin.php">Accéder à l'espace d'administration</a> </li>
        </ul>
    </div>

    <div class="cntainer">
        <div class="row top">
            <div class="col-lg-3"></div>
            <div class="col-lg-6 titreSite"><h1> Billet simple pour l'alaska</h1>
                <p>Par Jean Forteroche</p></div>
            <div class="col-lg-3"></div>
        </div>


            <div class="container">

<?php
if (isset($_GET['id']))
{

    $billets = $manager->getUnique((int)$_GET['id']);
    $parentId = $billets->getId();
    //$commentaires2 = $manager2->getList();
    var_dump($parentId);
    $form = new Form($_POST);

    echo '<h2>', $billets->getTitre(), '</h2>', "\n",
         '<p>', nl2br($billets->getContenu()), '</p>', "\n",
         '<p>Publié le ', $billets->getDateAjout()->format('d/m/Y à H\hi'), '</p>', "\n";

    ?>
    <h2>Commentaires :</h2>

    <form action="#" method="post">
        <?php

        if (isset($message))
        {
            echo '<div class ="alert-danger">', $message, '</div>';
        }
        ?>

        <?php if (isset($erreurs) && in_array(Commentaires::AUTEUR_INVALIDE, $erreurs)) echo '<div class ="alert-danger">Le pseudo est invalide.</div>'; ?>
        <label>Pseudo :</label><br /> <input type="text" name="auteur" value="<?php if (isset($commentaires)) echo $commentaires->getAuteur(); ?>" /><br />

        <?php if (isset($erreurs) && in_array(Commentaires::CONTENU_INVALIDE, $erreurs)) echo '<br /><div class ="alert-danger">Le contenu est invalide.</div>'; ?>
        <label>Commentaire :</label><br /><textarea rows="4" cols="60" name="contenu"><?php if (isset($commentaires)) echo $commentaires->getContenu(); ?></textarea><br />

        <input type="hidden" name="parentId" value="<?= $billets->getId() ?>" />
        <input type="submit" class="btn btn-default" value="Ajouter" /></form>

       <orm action="#" method="post">

        <?php
        echo $form->input('Pseudo');
        echo $form->inputContenu('Commentaire');
        echo $form->submit();

        if (isset($erreurs) && in_array(Commentaires::AUTEUR_INVALIDE, $erreurs)) {
        $message = 'Le titre est invalide.<br />';
            var_dump($message);}

        ?>
    </orm>
    <br/>

    <div class="media-list col-lg-7">

    <?php foreach ($manager2->getCommentsByParentId($parentId) as $commentaires) {
        echo '<li class="commentaire media thumbnail "><img src="Web/images/avatar92.png" alt="Avatar">','<strong>', $commentaires->getAuteur(), '</strong> Le ',
        $commentaires->getDateAjout()->format('d/m/Y à H\hi'),'<br/>',substr ($commentaires->getContenu(), 0, 250),
        '<div class="lienCommentaire"><a id="myBtn" href="#" >Répondre</a> | <a href="?id=',$billets->getId(),'&signaler=',$commentaires->getId(),'">Signaler</a>
        </div></li>';?>


            <?php

        foreach ($commentaires->getSousCommentaire() as $sousCommentaire) {

            //var_dump($sousCommentaire);
            echo '<li class="media thumbnail sousCommentaire">', '<strong>', $sousCommentaire->getAuteur(), '</strong> Le ',
            $sousCommentaire->getDateAjout()->format('d/m/Y à H\hi'), '<br/>', substr($sousCommentaire->getContenu(), 0, 250),
            '<div class="lienCommentaire"><a href="#" onclick="toggleForm()">Commenter</a><a id="myBtn">Répondre</a> | <a href="?id=', $billets->getId(), '&signaler=', $commentaires->getId(), '">Signaler</a>
        </div></li>';

            foreach ($sousCommentaire->getSousCommentaire() as $sousCommentaire1) {

                //var_dump($sousCommentaire);
                echo '<li class="media thumbnail sousCommentaire1">', '<strong>', $sousCommentaire1->getAuteur(), '</strong> Le ',
                $sousCommentaire1->getDateAjout()->format('d/m/Y à H\hi'), '<br/>', substr($sousCommentaire1->getContenu(), 0, 250),
                '<div class="lienCommentaire"><a href="#" onclick="toggleForm()">Commenter</a><a id="myBtn">Répondre</a> | <a href="?id=', $billets->getId(), '&signaler=', $commentaires->getId(), '">Signaler</a>
        </div></li>';

                foreach ($sousCommentaire1->getSousCommentaire() as $sousCommentaire2) {

                    //var_dump($sousCommentaire);
                    echo '<li class="media thumbnail sousCommentaire2">', '<strong>', $sousCommentaire2->getAuteur(), '</strong> Le ',
                    $sousCommentaire2->getDateAjout()->format('d/m/Y à H\hi'), '<br/>', substr($sousCommentaire2->getContenu(), 0, 250),
                    '<div class="lienCommentaire"><a href="#" onclick="toggleForm()">Commenter</a><a id="myBtn">Répondre</a> | <a href="?id=', $billets->getId(), '&signaler=', $commentaires->getId(), '">Signaler</a>
        </div></li>';
                    ?>

                    <form id="formulaire" method="POST" action="">


                        <label>Pseudo :</label><br/> <input type="text" name="auteur"
                                                            value="<?php if (isset($commentaires)) echo $commentaires->getAuteur(); ?>"/><br/>

                        <?php if (isset($erreurs) && in_array(Commentaires::CONTENU_INVALIDE, $erreurs)) echo '<br /><div class ="alert-danger">Le contenu est invalide.</div>'; ?>
                        <label>Commentaire :</label><br/><textarea rows="8" cols="60"
                                                                   name="contenu"><?php if (isset($commentaires)) echo $commentaires->getContenu(); ?></textarea><br/>

                        <input type="hidden" name="parentId" value=""/>
                        <input type="submit" class="btn btn-default" value="Ajouter"/></form>

                    <div id="myModal" class="modal">

                        <!-- Modal content -->
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <p>Ajouter un commentaire</p>

                            <form action="http://127.0.0.1/projet%203/index.php?id=<?php $billets->getId() ?> method="
                                  post
                            ">
                            <?php

                            if (isset($message)) {
                                echo '<div class ="alert-danger">', $message, '</div>';
                            }
                            ?>

                            <?php if (isset($erreurs) && in_array(Commentaires::AUTEUR_INVALIDE, $erreurs)) echo '<div class ="alert-danger">Le pseudo est invalide.</div>'; ?>
                            <label>Pseudo :</label><br/> <input type="text" name="auteur" value=""/><br/>

                            <?php if (isset($erreurs) && in_array(Commentaires::CONTENU_INVALIDE, $erreurs)) echo '<br /><div class ="alert-danger">Le contenu est invalide.</div>'; ?>
                            <label>Commentaire :</label><br/><textarea rows="4" cols="40"
                                                                       name="contenu"><?php if (isset($commentaires)) echo $commentaires->getContenu(); ?></textarea><br/>

                            <input type="hidden" name="parentId" value=""/>
                            <input type="submit" class="btn btn-default" value="Ajouter"/></form>


                        </div>

                    </div>

                    <?php
                }
            }
        }
    }


}

else {
    echo '<h2> Liste des derniers billets</h2>';

    foreach ($manager->getList(0, 5) as $billets) {
        if (strlen($billets->getContenu()) <= 200) {
            $contenu = $billets->getContenu();
        } else {
            $debut = substr($billets->getContenu(), 0, 800);
            $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

            $contenu = $debut;
            $parentId = $billets->getId();
        }

        echo '<div class="jumbotron"><h3><a href="?id=', $billets->getId(), '">', $billets->getTitre(), '</a></h3>', "\n",
        '<p>', nl2br($contenu), '</p>', '<a class="btn btn-info btn-lg" role="button" href="?id=' , $billets->getId(), '">',
        "Lire la suite", '</a>','<p>','<br />',  $manager2->getCountByParentId($parentId) , ' Commentaires','</p>','</div>';
    }
}
?>
            </ul>
        </div>

    <script>

        function toggleForm(){
            // on réccupère l'élément form.
            var formulaire = document.getElementById('formulaire');

            // Condition pour afficher/cacher le formulaire en fonction de son état
            if(formulaire.style.display == 'block'){
                formulaire.style.display = 'none';
            }else{
                formulaire.style.display = 'block';
            }
        }



        // Get the modal
        var modal = document.getElementById('myModal');

        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
    </body>
</html>