<?php
include 'Billets.php';

$id = 2;
$titre = "test";
$contenu = "ca fonctionne";
$dateAjout = "02/02/2017";

$billet = new Billets($id, $titre, $contenu, $dateAjout);

if ($billet->getId() == $id && $billet->getTitre() == $titre && $billet->getContenu() == $contenu && $billet->getDateAjout() == $dateAjout)
{
	echo 'Constructeur ok';
}
else{
	echo'Erreur dans ton code';
}
