<?php
$dsn = "mysql:host=localhost;dbname=four";
$user= 'root';
$pass= "";
$option = array(
PDO::MYSQL_ATTR_INIT_COMMAND=> "set names utf8"
);
try{
    $connection = new PDO($dsn,$user,$pass,$option);
    $connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo "Failed to connect". $e->getMessage();
}
?>
