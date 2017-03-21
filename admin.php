<?php

require 'autoload.php';
//require 'Billet.php';
//require 'BilletsManager.php';
require 'Controller/controller.php';
require 'Vues/vueAdmin.php';
require 'Vues/vueAdminBillets.php';
require 'Vues/vueAdminCommentaires.php';

$controller = new Controller();
$controller->execute();