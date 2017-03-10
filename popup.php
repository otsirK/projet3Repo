<?php
require 'autoload.php';

$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new BilletsManager($db); //$billetManager
$manager2 = new CommentairesManager($db); //$commentaireManager



$form = new Form($_POST);

?>

<!DOCTYPE html>

<html>
<head>
    <style>
        /* The Modal (background) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
        }

        /* The Close Button */
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
        #formulaire{
            display:none;
        }
    </style>
</head>
<body>

<h2>Modal Example</h2>

<!-- Trigger/Open The Modal -->
<a href="#" id="myBtn">Répondre</a>

<!-- The Modal -->
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>Ajouter un commentaire</p>

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

            <input type="hidden" name="parentId" value="" />
            <input type="submit" class="btn btn-default" value="Ajouter" /></form>



    </div>

</div>

<a href="#" onclick="toggleForm()">Commenter</a>


    <form id="formulaire" method="POST" action="post_commetaire.php">



        <label>Pseudo :</label><br /> <input type="text" name="auteur" value="<?php if (isset($commentaires)) echo $commentaires->getAuteur(); ?>" /><br />

        <?php if (isset($erreurs) && in_array(Commentaires::CONTENU_INVALIDE, $erreurs)) echo '<br /><div class ="alert-danger">Le contenu est invalide.</div>'; ?>
        <label>Commentaire :</label><br /><textarea rows="8" cols="60" name="contenu"><?php if (isset($commentaires)) echo $commentaires->getContenu(); ?></textarea><br />

        <input type="hidden" name="parentId" value="" />
        <input type="submit" class="btn btn-default" value="Ajouter" /></form>

<script>
    // Get the modal
    var modal = document.getElementById('myModal');

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    function toggleForm(){
        // on réccupère l'élément form.
        var formulaire = document.getElementById('formulaire');

        // Condition pour afficher/cacher le formulaire en fonction de son état
        if(formulaire.style.display == 'block'){
            formulaire.style.display = 'none';
        }else{
            formulaire.style.display = 'block';
        }
    }
</script>

</body>
</html>
