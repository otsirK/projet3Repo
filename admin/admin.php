<?php

require 'autoload.php';
/*
$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new BilletsManager($db);
$manager2 = new CommentairesManager($db);
*/
require 'Billets.php';
//require 'BilletsManager.php';
require 'controller.php';
require 'vueIndex.php';
require 'vueAdminBillets.php';
require 'vueAdminCommentaires.php';

$controller = new Controller();
$controller->execute();
$controller->executeCommentaires();