<?php
session_start();


$error_message=array();
$message_id=array();

try{
  $option=array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
  $pdo=new PDO('mysql:charset=UTF8;dbname=board4;host=localhost','root','kouking2001',$option);

 
}catch(PDOException $e){
$error_message[]= $e->getMessage();
}


if(empty($_POST['user_name'])){
  $error_message[]='please input user name.';
}

if(empty($_POST['text'])){
  $error_message[]='please input text comment.';
}
// $text=mb_convert_encoding($_POST['text'],"UTF-8");
// $user_name=mb_check_encoding($_POST['user_name',"UTF-8"]);

if(isset($_FILES['image'])){
  $file_image=$_FILES['image'];
  if($file_image['size']>0){
    if($file_image['size']>1000000){
      $error_message[]='picture is too big';
    }else{
      move_uploaded_file($file_image['tmp_name'],'../image/'.$file_image['name']);
    }
  }
}elseif(empty($_FILES['image'])){
  $file_image['name']='character_ebi_fry.png';
}

if(empty($_POST['connent_id'])){
  $_POST['connent_id']='';
}



if(empty($error_message)){

$current_date=date("Y-m-d H:i:s");
$stmt = $pdo->prepare("INSERT INTO message (message,profile,view_name,post_date,post_id) VALUES(:text,:image,:user_name,:created_at,:post_id)");
$stmt->bindParam(':text',$_POST['text'],PDO::PARAM_STR);
$stmt->bindParam(':image',$file_image['name'],PDO::PARAM_STR);
$stmt->bindParam(':user_name',$_POST['user_name'],PDO::PARAM_STR);
$stmt->bindParam(':created_at',$current_date,PDO::PARAM_STR);
$stmt->bindParam(':post_id',$_POST['connect_id'],PDO::PARAM_INT);


$res=$stmt->execute();

  
  if($res){
    header('Location:./index.php');
  }else{
    $error_message[]='insert できません';
  }

  $stmt=null;
}
$pdo=null;



function get_message($id){
    try{
      $dbc=new PDO('mysql:charset=UTF8;dbname=board4;host=localhost','root','kouking2001');
  $stmt=$dbc->prepare("SELECT * FROM message where id=:id");
  $stmt->bindValue(':id',$id,PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll();
  }catch(PDOException $e){
    $dbc->rollBack();
    $error_message[]='select not';
  }
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <div class="modal"></div>
  <div class="comment">
        
    <?php if(!empty($_GET['message_id'])): ?>
  
    <?php  $messages = get_message($_GET['message_id']); ?>
    <?php foreach($messages as $message): ?>
      <p><?= nl2br($message['message']); ?></p>
    <p>↑↑↑Want you comment this message?</p>
      <?php endforeach; ?>
      <form method="post" enctype="multipart/form-data" accept-charset="ASCII">
        <label for="">Name</label>
        <input type="text" name="user_name" value="<?= $_SESSION['name']; ?>" required><br>
        <label for="">comment</label>
        <textarea id="" cols="30" rows="10" name="text"></textarea><br>
        <input type="file" name="image" accept="image/*" multiple>
        <input type="submit" value="comment" name="comment">
        <input type="text" name="connect_id" value="<?= $_GET['message_id']; ?>"> 
      </form>
      <?php endif; ?>
    <a href="./index.php">Cancel</a>
  </div>
</body>
</html>
