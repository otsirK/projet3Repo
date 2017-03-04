<?php
require 'autoload.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new BilletsManager($db);
$manager2 = new CommentairesManager($db);

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
            <li> <a href="admin/admin.php">Accéder à l'espace d'administration</a> </li>
        </ul>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-6"><h1> Billet simple pour l'Alaska</h1>
                <p>Par Jean Forteroche</p></div>
            <div class="col-lg-2"></div>
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
        <label>Commentaire :</label><br /><textarea rows="8" cols="60" name="contenu"><?php if (isset($commentaires)) echo $commentaires->getContenu(); ?></textarea><br />

        <input type="hidden" name="parentId" value="<?= $billets->getId() ?>" />
        <input type="submit" class="btn btn-default" value="Ajouter" /></form>

        <form action="#" method="post">

        <?php
        echo $form->input('Pseudo');
        echo $form->inputContenu('Commentaire');
        echo $form->submit();

        if (isset($erreurs) && in_array(Commentaires::AUTEUR_INVALIDE, $erreurs)) {
        $message = 'Le titre est invalide.<br />';
            var_dump($message);}

        ?>
    </form>
    <br/>
    <ul class="media-list col-lg-7">

    <?php foreach ($manager2->getCommentsByParentId($parentId) as $commentaires) {
        echo '<li class="media thumbnail">','<strong>', $commentaires->getAuteur(), '</strong> Le ',
        $commentaires->getDateAjout()->format('d/m/Y à H\hi'),'<br/>',substr ($commentaires->getContenu(), 0, 250),
        '<div class="lienCommentaire">','<a href="?répondre=', $billets->getId(),'">Répondre</a> | <a href="?signaler=',$manager2->signale($commentaires),'">Signaler</a>
        </div></li>';}

    ?></ul>

    <?php
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
            </div>
        </div>
    </body>
</html>