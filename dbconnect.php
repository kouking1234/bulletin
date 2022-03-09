<?php
try{
  $pdo=new PDO("mysql:host=localhost;dbname=board4;charset=utf8","root","kouking2001");
}catch(PDOException $e){
  $error_message[]= $e->getMessage();
}
?>