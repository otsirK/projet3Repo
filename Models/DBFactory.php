<?php
class DBFactory
{
  public static function getMysqlConnexionWithPDO()
  {
      $ini_array = parse_ini_file("conf_db.ini");
    $db = new PDO('mysql:host=localhost;dbname=projet3', $ini_array['username'], $ini_array['password']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    return $db;
  }
}  
