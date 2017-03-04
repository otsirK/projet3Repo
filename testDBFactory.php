<?php
//require 'DBFactory.php';
function chargerClasse($DBFactory)
{
  require $DBFactory . '.php'; // On inclut la classe correspondante au paramètre passé.
}

spl_autoload_register('chargerClasse');



$db = DBFactory::getMysqlConnexionWithPDO();
$reponse = $db->query('SELECT * FROM billets');
while ($donnees = $reponse->fetch())
{
    echo '<p>Le ' . $donnees['dateAjout'] . '</p>';
    echo $donnees['titre'] . '</br>';
    echo $donnees['contenu'];

}

$reponse->closeCursor();