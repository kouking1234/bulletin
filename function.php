<?php

// function get_post($id){
//   try{
//     $pdo=new PDO('mysql:charset=UTF8;dbname=board4;host=localhost','root','kouking2001');
//     $sql="SELECT * FROM message where id = :id";
//     $stmt=$pdo->prepare($sql);
//     $stmt->execute(array(':id'=>$id));
//     return $stmt->fetchAll();
// }catch(PDOException $e){
//   $error_message[]=$e->getMessage();
//   }
// }

    function get_post($post_id){
      try{
        $pdo=new PDO('mysql:charset=UTF8;dbname=board4;host=localhost','root','kouking2001');

        $sql="SELECT * FROM message where post_id = :post_id order by post_date desc";
        $stmt=$pdo->prepare($sql);
        $stmt->execute(array(':post_id'=>$post_id));
        return $stmt->fetchAll(); 
    }catch(PDOException $e){
      $error_message[]=$e->getMessage();
      }
    }

    function get_comment($id){
      try{
        $pdo=new PDO('mysql:charset=UTF8;dbname=board4;host=localhost','root','kouking2001');

        $sql="SELECT * from message where id = :id";
        $stmt=$pdo->prepare($sql);
        $stmt->execute(array(':id'=>$id));
        return $stmt->fetchAll();
  }catch(PDOException $e){
    $error_message[]=$e->getMessage();
    }
  }

  function get_my_post($view_name){
    try{
      $pdo=new PDO('mysql:charset=UTF8;dbname=board4;host=localhost','root','kouking2001');

      $sql="SELECT * from message where view_name = :view_name";
      $stmt=$pdo->prepare($sql);
      $stmt->execute(array(':view_name'=>$view_name));
      return $stmt->fetchAll();
}catch(PDOException $e){
  $error_message[]=$e->getMessage();
  }
  }

  function get_maxid(){
    $pdo=new PDO('mysql:charset=UTF8;dbname=board4;host=localhost','root','kouking2001');
    $sql ="SELECT MAX(id) FROM message";
    $stmt=$pdo->query($sql);
    return $stmt->fetch();
  }
?>
