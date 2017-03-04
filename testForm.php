<?php
require 'form.php';

$form = new Form($_POST);

?>

<form action="#" method="post">
    <?php
echo $form->input('Pseudo');
echo $form->inputContenu('Commentaire');
echo $form->submit();

?>
</form>
