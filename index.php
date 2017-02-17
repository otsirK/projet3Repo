<?php
require 'autoload.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new BilletsManager($db);
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Accueil du site</title>
        <meta charset="utf-8" />
    </head>

    <body>
    <p><a href="admin.php">Accéder à l'espace d'administration</a></p>
<?php
$billets = $manager->getUnique(1);

echo '<p>le ', $billets->getDateAjout()->format('d/m/Y à H\hi'), '</p>', "\n",
'<h2>', $billets->getTitre(), '</h2>', "\n",
'<p>', nl2br($billets->getContenu()), '</p>', "\n";

?>
    </body>
</html>