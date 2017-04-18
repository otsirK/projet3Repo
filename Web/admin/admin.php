<?php

require '../autoload.php';
//require 'Billet.php';
//require 'BilletsManager.php';
require '../../Controller/controller.php';
require '../../Vues/vueAdmin.php';
require '../../Vues/vueAdminBillets.php';
require '../../Vues/vueAdminCommentaires.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$managerBillet = new BilletManager($db);
$managerCommentaire = new CommentaireManager($db);

$controller = new Controller($db,$managerBillet,$managerCommentaire);
$controller->execute();