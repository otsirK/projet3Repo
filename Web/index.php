<?php
require 'autoload.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$managerBillet = new BilletManager($db);
$managerCommentaire = new CommentaireManager($db);

/* AJOUT D UN COMMENTAIRE */

if (isset($_POST['auteur']))
{
    $commentaire = new Commentaire(
        [
            'auteur' => htmlspecialchars($_POST['auteur']),
            'contenu' => htmlspecialchars($_POST['contenu']),
            'parentId' => htmlspecialchars($_POST['parentId']),
            'depth' => htmlspecialchars($_POST['depth'])
        ]
    );

    if (isset($_POST['id']))
    {
        $commentaire->setId($_POST['id']);
    }

    if ($commentaire->isValid())
    {
        $managerCommentaire->save($commentaire);
        $message = $commentaire->isNew() ? 'Le commentaire a bien été ajouté !' : 'Le commentaire a bien été modifiée !';
    }
    else
    {
        $erreurs = $commentaire->getErreurs();
    }
}

/* SIGNALER UN COMMENTAIRE */

if (isset($_GET['signaler']))
{
    $managerCommentaire->signale((int) $_GET['signaler']);
    $message = 'Le commentaire a bien été signalé !';
}

?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Accueil du site</title>
        <meta charset="utf-8" />

        <link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="css/style.css">


        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
            $( function() {
                var dialog, form,

                    auteur = $( "#auteur" ),
                    contenu = $( "#contenu" ),
                    parentId = $("#parentId"),
                    depth = $(".depthCmt"),
                    allFields = $( [] ).add( name ).add( contenu ),
                    tips = $( ".validateTips" );

                function updateTips( t ) {
                    tips
                        .text( t )
                        .addClass( "ui-state-highlight" );
                    setTimeout(function() {
                        tips.removeClass( "ui-state-highlight", 1500 );
                    }, 500 );
                }

                function checkLength( o, n, min, max ) {
                    if ( o.val().length > max || o.val().length < min ) {
                        o.addClass( "ui-state-error" );
                        updateTips( "Le " + n + " doit contenir entre " +
                            min + " et " + max + " caractères." );
                        return false;
                    } else {
                        return true;
                    }
                }

                function addCom() {
                    var valid = true;
                    allFields.removeClass( "ui-state-error" );

                    valid = valid && checkLength( auteur, "pseudo", 3, 16 );
                    valid = valid && checkLength( contenu, "commentaire", 6, 250 );

                    valid = valid && checkRegexp( auteur, /^[a-z]([0-9a-z_\s])+$/i, "Username may consist of a-z, 0-9, underscores, spaces and must begin with a letter." );
                    valid = valid && checkRegexp( contenu, /^[a-z]([0-9a-z_\s])+$/i , "eg. ui@jquery.com" );

                    if ( valid ) {

                        dialog.dialog( "close" );
                    }
                    return valid;
                }

                dialog = $( "#dialog-form" ).dialog({
                    autoOpen: false,
                    height: 400,
                    width: 350,
                    modal: true,

                });

                form = dialog.find( "form" ).on( "submit", function( event ) {
                    if (!addCom())
                    {
                        event.preventDefault(); /* Annule l'action déclenché par un submit */
                    }
                });



                $( ".btnReponse" ).button().on( "click", function() {
                    parentId.attr("value", $(this).attr("parentId"));
                    depth.attr("value", $(this).attr("depth"));
                    dialog.dialog( "open" );

                });


            } );
        </script>
    </head>

    <body>

    <div class="navbar navbar-default">
        <ul class="nav navbar-nav">
            <li class="active"> <a href="index.php">Accueil</a> </li>
            <li> <a href="admin/admin.php">Accéder à l'espace d'administration</a> </li>
        </ul>
    </div>

    <div class="index">
        <div class="imgTop top">
            <div class="col-lg-3"></div>
            <div class="col-lg-6 titreSite"><h1> Billet simple pour l'alaska</h1>
                <p>Par Jean Forteroche</p></div>
            <div class="col-lg-3"></div>
        </div>


            <div class="container">
<div class="contenuBillet">
<?php

if (isset($_GET['id']))
{

    /* AFFICHAGE D UN BILLET ET DE SES COMMENTAIRES */

    $billet = $managerBillet->getUnique((int)$_GET['id']);
    $parentId = $billet->getId();

    echo '<h2>', $billet->getTitre(), '</h2>', "\n",
         '<p>', nl2br($billet->getContenu()), '</p>', "\n",
         '<div class="publie">Publié le ', $billet->getDateAjout()->format('d/m/Y à H\hi'), '</div>', "\n";

    ?>
    <h2>Commentaires :</h2></div>
                <?php
                if (isset($message))
                {
                    echo '<div class ="alert-danger">', $message, '</div>';
                }
                ?>
                <div class="formCommentaire">
    <form action="#" method="post">
<h3>Ajouter un commentaire</h3>
        <!-- Formulaire pour ajouter un commentaire -->

        <?php if (isset($erreurs) && in_array(Commentaire::AUTEUR_INVALIDE, $erreurs)) echo '<div class ="alert-danger">Le pseudo est invalide.</div>'; ?>
        <label>Pseudo :</label><br /> <input type="text" name="auteur" value="<?php if (isset($commentaires)) echo $commentaires->getAuteur(); ?>" /><br />

        <?php if (isset($erreurs) && in_array(Commentaire::CONTENU_INVALIDE, $erreurs)) echo '<br /><div class ="alert-danger">Le contenu est invalide.</div>'; ?>
        <label>Commentaire :</label><br /><textarea rows="4" cols="60" name="contenu"><?php if (isset($commentaires)) echo $commentaires->getContenu(); ?></textarea><br />

        <input type="hidden" name="parentId" value="<?= $billet->getId() ?>" />
        <input type="hidden" id ="depth" name="depth" value="0"/>
               <p><input type="submit" class="btn btn-default" value="Ajouter" /></p> </form></div>

    <br/>

    <div class="media-list col-lg-7 listeCommentaire">

        <!-- AFFICHAGE DES COMMENTAIRES -->

    <?php foreach ($managerCommentaire->getCommentsByParentId($parentId) as $commentaires) {
        echo '<li class="commentaire media thumbnail "><img src="images/avatar92.png" alt="Avatar">','<p><span class="pseudo">', $commentaires->getAuteur(), '</span> - Le ',
        $commentaires->getDateAjout()->format('d/m/Y à H\hi'),'</p>',substr ($commentaires->getContenu(), 0, 250),
        '<div class="lienCommentaire"><button depth = "1" class="btnReponse" parentId="',$commentaires->getId(),'" >Répondre</button> | <a class="ui-button ui-corner-all ui-widget" href="?id=',$billet->getId(),'&signaler=',$commentaires->getId(),'">Signaler</a>
        </div></li>';?>


            <?php
        /* AFFICHAGE DES SOUS COMMENTAIRES DE NIVEAU 1 */

        foreach ($commentaires->getSousCommentaire() as $sousCommentaire) {

            echo '<li class="media thumbnail sousCommentaire"><img src="images/avatar92.png" alt="Avatar">', '<p><span class="pseudo">', $sousCommentaire->getAuteur(), '</span> - Le ',
            $sousCommentaire->getDateAjout()->format('d/m/Y à H\hi'),'</p>', substr($sousCommentaire->getContenu(), 0, 250),
            '<div class="lienCommentaire"><button depth = "2" class="btnReponse" parentId="',$sousCommentaire->getId(),'" >Répondre</button> | <a class="ui-button ui-corner-all ui-widget" href="?id=', $billet->getId(), '&signaler=', $commentaires->getId(), '">Signaler</a>
        </div></li>';

            /* AFFICHAGE DES SOUS COMMENTAIRES DE NIVEAU 2 */

            foreach ($sousCommentaire->getSousCommentaire() as $sousCommentaire1) {

                echo '<li class="media thumbnail sousCommentaire1"><img src="images/avatar92.png" alt="Avatar">', '<p><span class="pseudo">', $sousCommentaire1->getAuteur(), '</span> - Le ',
                $sousCommentaire1->getDateAjout()->format('d/m/Y à H\hi'), '</p>', substr($sousCommentaire1->getContenu(), 0, 250),
                '<div class="lienCommentaire"><button depth = "3" class="btnReponse" parentId="',$sousCommentaire1->getId(),'" >Répondre</button> | <a class="ui-button ui-corner-all ui-widget" href="?id=', $billet->getId(), '&signaler=', $commentaires->getId(), '">Signaler</a>
        </div></li>';

                /* AFFICHAGE DES SOUS COMMENTAIRES DE NIVEAU 3 */

                foreach ($sousCommentaire1->getSousCommentaire() as $sousCommentaire2) {


                    echo '<li class="media thumbnail sousCommentaire2"><img src="images/avatar92.png" alt="Avatar">', '<p><span class="pseudo">', $sousCommentaire2->getAuteur(), '</span> - Le ',
                    $sousCommentaire2->getDateAjout()->format('d/m/Y à H\hi'), '</p>', substr($sousCommentaire2->getContenu(), 0, 250),
                    '<div class="lienCommentaire"> <a class="ui-button ui-corner-all ui-widget" href="?id=', $billet->getId(), '&signaler=', $commentaires->getId(), '">Signaler</a>
        </div></li>';
                }
            }
        }
    }
}

else {
    /* AFFICHAGE DES DERNIERS BILLETS */

    foreach ($managerBillet->getList() as $billets) {
        if (strlen($billets->getContenu()) <= 200) {
            $contenu = $billets->getContenu();
        } else {
            $debut = substr($billets->getContenu(), 0, 800);
            $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

            $contenu = $debut;
            $parentId = $billets->getId();
        }
        $parentId = $billets->getId();

        echo '<div class="jumbotron billet"><h2><a href="?id=', $billets->getId(), '">', $billets->getTitre(), '</a></h2>', "\n",
        '<p><div class="contenu">', nl2br($contenu), '</div></p>', '<a class="btn btn-info btn-lg" role="button" href="?id=' , $billets->getId(), '">',
        "Lire la suite", '</a>','<p>','<br /><div class="nbCommentaire">',  $managerCommentaire->getCountByParentId($parentId) , ' Commentaires','</div></p>','</div>';
    }
}
?>
            </ul>
        </div>

                <div id="dialog-form" title="Ajouter un commentaire">
                    <p class="validateTips">Tous les champs sont requis.</p>

                    <form action="#" method="post">


                        <fieldset>

                            <label for="auteur">Pseudo</label>
                            <input type="text" name="auteur" id="auteur" value="" class="text ui-widget-content ui-corner-all"/><br/>
                            <label for="contenu">Commentaire</label>
                            <textarea rows="6" cols="38" name="contenu" id="contenu" value="" class="text ui-widget-content ui-corner-all"></textarea>

                            <input type="hidden" id ="parentId" name="parentId" value=""/>
                            <input type="hidden" class="depthCmt" id ="depth" name="depth" value=""/>
                            <p><input type="submit" class="btn btn-default" value="Ajouter"/></p>

                    </fieldset>
                    </form>
                </div>
            </div>
    </div>
    </body>
</html>