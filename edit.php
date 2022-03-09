<?php 
$stmt=null;
$pdo=null;
$res=null;
$option=null;
$error_message=array();
$success_message=array();
$message_array=array();
$message_data=null;

try{
  $option=array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  );

  $pdo = new PDO('mysql:charset=UTF8;dbname=board4;host=localhost','root','kouking2001',$option);
}catch(PDOException $e){
  $error_message[] = $e->getMessage();
}

if(!empty($_GET['message_id']) && empty($_POST['message_id'])){
  $stmt=$pdo->prepare("SELECT * FROM message where id = :id");
  $stmt->bindValue(':id',$_GET['message_id'],PDO::PARAM_INT);
  $stmt->execute();
  $message_data=$stmt->fetch();
  if(empty($message_data)){
     $error_message[] = 'cant select';
  }

}elseif(!empty($_POST['message_id'])){
  $pdo->beginTransaction();
  try{
    $stmt=$pdo->prepare("UPDATE message SET view_name=:view_name,message=:message where id=:id");
    $stmt->bindParam(':view_name',$_POST['view_name'],PDO::PARAM_STR);
    $stmt->bindParam(':message',$_POST['message'],PDO::PARAM_STR);
    $stmt->bindValue(':id',$_POST['message_id'],PDO::PARAM_INT);
    $stmt->execute();
    $res=$pdo->commit();
  }catch(Exception $e){
    $pdo->rollBack();
  }

  if($res){
    header("Location:./admin.php");
    exit;
  }else{
    $error_message[]="cant update";
  }
}
$stmt=null;
$pdo=null;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit</title>
</head>
<body>

  <h1>bulletin Edit</h1>
  <?php if(!empty($error_message)): ?>
    <?php foreach($error_message as $value):?>
      <?php echo $value;?>
    <?php endforeach;?>
  <?php endif;?>
  
  <form method="post">
      <div>
        <label for="">Name</label><br>
        <input type="text" name="view_name" value="<?php if(!empty($message_data['view_name'])) {echo $message_data['view_name'];}
        elseif(!empty($_POST['view_name'])){echo htmlspecialchars($_POST['view_name'],ENT_QUOTES,'UTF-8');}?>">
      </div>
      <div>
        <label for="">Message</label><br>
        <textarea name="message" id="" cols="30" rows="10"><?php if(!empty($message_data['message'])){echo $message_data['message'];}
        elseif(!empty($_POST['message'])){echo htmlspecialchars($_POST['message'],ENT_QUOTES,'UTF-8');} ?></textarea>
      </div>
      <input type="submit" name="submit" value="UPdate">
      <input type="hidden" name="message_id" value="<?php if(!empty($message_data['id'])){ echo $message_data['id'];}
      elseif(!empty($_POST['message_id'])){echo htmlspecialchars($_POST['message_id'],ENT_QUOTES,'UTF-8');}?>">
  </form>
    <a href="./admin.php">cancel</a>

</body>
</html>