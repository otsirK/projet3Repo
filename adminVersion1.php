<?php
require 'autoload.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new BilletsManager($db);
$manager2 = new CommentairesManager($db);

if (isset($_GET['modifier']))
{
    $billets = $manager->getUnique((int) $_GET['modifier']);
}

if (isset($_GET['supprimer']))
{
    $manager->delete((int) $_GET['supprimer']);
    $message = 'Le billet a bien été supprimé !';
}

if (isset($_POST['titre']))
{
    $billets = new Billets(
        [
            'titre' => $_POST['titre'],
            'contenu' => $_POST['contenu']
        ]
    );

    if (isset($_POST['id']))
    {
        $billets->setId($_POST['id']);
    }

    if ($billets->isValid())
    {
        $manager->save($billets);

        $message = $billets->isNew() ? 'La news a bien été ajoutée !' : 'La news a bien été modifiée !';
    }
    else
    {
        $erreurs = $billets->getErreurs();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Administration</title>
    <meta charset="utf-8" />
    <link href="style.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style type="text/css">
        table, td {
            border: 1px solid black;
        }

        table {
            margin:auto;
            text-align: center;
            border-collapse: collapse;
        }

        td {
            padding: 3px;
        }
    </style>
</head>

<body>

<div class="admin-top">
    <h1 class="admin-title">Interface d'administration</h1>
</div>

<div class="navbar navbar-default">
    <ul class="nav navbar-nav">
        <li class="active"> <a href="admin.php">Accueil administration</a>
        <li> <a href="#">Gestion des billets</a> </li>
        <li> <a href="#">Gestion des commentaires</a> </li>
        <li> <a href="index.php">Retour au blog</a> </li>

    </ul>
</div>

<div class="container">
<h2>Ajouter un billet</h2>

<p><a href="#">Nouveau billet </a></p>

<form action="admin.php" method="post">
    <p>
        <?php
        if (isset($message))
        {
            echo '<div class ="alert-danger">', $message, '</div>';
        }
        ?>

        <?php if (isset($erreurs) && in_array(Billets::TITRE_INVALIDE, $erreurs)) echo 'Le titre est invalide.<br />'; ?>
        Titre : <input type="text" name="titre" value="<?php if (isset($billets)) echo $billets->getTitre(); ?>" /><br />

        <?php if (isset($erreurs) && in_array(Billets::CONTENU_INVALIDE, $erreurs)) echo 'Le contenu est invalide.<br />'; ?>
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


<h2>Les 3 derniers billets publiés</h2>
<p style="text-align: center">Il y a actuellement <?= $manager->count() ?> billets. En voici la liste :</p>

<table>
    <tr><th>Titre</th><th>Contenu</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
    <?php
    foreach ($manager->getList() as $billets)
    {
        echo '<tr><td>', $billets->getTitre(), '</td><td>', substr ($billets->getContenu(), 0, 250), ' ...', '</td><td>', $billets->getDateAjout()->format('d/m/Y à H\hi'), '</td><td>', ($billets->getDateAjout()->format('d/m/Y à H\hi')), '</td><td><a href="?modifier=', $billets->getId(), '" target="_blank">Modifier</a> | <a href="?supprimer=', $billets->getId(), '">Supprimer</a></td></tr>', "\n";
    }
    ?>
</table>
    <h2>Commentaires signalés</h2>

    <table>
        <tr><th>Auteur</th><th>Commentaire</th><th>Date d'ajout</th><th>Action</th></tr>

    <?php
    foreach($manager2->getListeSignale() as $commentaires)
    {
        echo '<tr><td>',$commentaires->getAuteur(), '</td><td>', substr ($commentaires->getContenu(), 0, 250), '</td><td>',
        $commentaires->getDateAjout();
    }

    ?>
    </table>
</div>
</body>
</html>