<?php

require 'autoload.php';
//require 'Billet.php';
//require 'BilletsManager.php';
require 'controller.php';
require 'vueAdmin.php';
require 'vueAdminBillets.php';
require 'vueAdminCommentaires.php';

$controller = new Controller();
$controller->execute();