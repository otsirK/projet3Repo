<?php

class Form {

    private $donnees;


    public function __construct($donnees = array())
    {
        $this->donnees = $donnees;
    }

    private function getValue($index)
    {
        return isset($this->donnees[$index]) ? $this->donnees[$index] : null;
    }


    public function input($name)
    {
        echo '<p><label>' . $name . '</label></p>';
        echo '<input type="text" name="' . $name . $this->getValue($name) . '"><br/>';


    }

    public function inputContenu($name)
    {
        echo '<p><label>' . $name . '</label></p>';
        echo '<textarea rows="3" cols="60" name="' . $name . $this->getValue($name) . '"></textarea><br/>';

    }

    public function submit(){
        echo '<button type="submit">Envoyer</button>';
    }
}