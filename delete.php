<?php 
$stmt=null;
$pdo=null;
$res=null;
$option=null;
$error_message=array();
$success_message=array();
$message_array=array();
$select_id=array();
session_start();

try{
  $option=array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  );

  $pdo = new PDO('mysql:charset=UTF8;dbname=board4;host=localhost','root','kouking2001',$option);
}catch(PDOException $e){
  $error_message[] = $e->getMessage();
}

if(!empty($_GET['message_id'])){
  $stmt=$pdo->prepare("SELECT * FROM message where id = :id");
  $stmt->bindValue(':id',$_GET['message_id'],PDO::PARAM_INT);
  $stmt->execute();
  $message_data=$stmt->fetch();
  if(empty($message_data)){
    header("Location:./admin.php");
    exit;
  }
}

if(!empty($_GET['message_id']) && empty($_POST['message_id'])){
  $stmt=$pdo->prepare("SELECT * FROM message where id = :id");
  $stmt->bindValue(':id',$_GET['message_id'],PDO::PARAM_INT);
  $stmt->execute();
  $message_data=$stmt->fetch();
  if(empty($message_data)){
    header("Location:./admin.php");
    exit;
  }

}elseif(!empty($_POST['message_id'])){
  $pdo->beginTransaction();
  try{
    $stmt = $pdo->prepare("DELETE FROM message where id = :id");
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
//admin.phpからactionでポストしたやつ。
// empty($_POST['id'])がないとボタン押してもこっちが動いて、!empty($_POST['id'])が動かない。
}elseif(!empty($_POST['select_id']) && empty($_POST['id'])){

  foreach($_POST['select_id'] as $key => $value){
      $stmt=$pdo->prepare("SELECT * from message where id=:id");
      $stmt->bindValue(':id',$value,PDO::PARAM_INT);
      $stmt->execute();
      $select_id[]=$stmt->fetch();
  }

}elseif(!empty($_POST['id'])){
//commented tableからも消す
  foreach($_POST['id'] as $key => $value){
    $pdo->beginTransaction();
    try{
      $stmt=$pdo->prepare("DELETE FROM message where id = :id");
      $stmt->bindValue(':id',$value,PDO::PARAM_INT);
      $stmt->execute();
      $res=$pdo->commit();
    }catch(Exception $e){
      $pdo->rollBack();
    }
}
    if($res){
      header('Location:./admin.php');
      exit;
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
  <title>Delete</title>
</head>
<body>

  <h1>bulletin Delete</h1>
  <?php if(!empty($error_message)): ?>
    <?php foreach($error_message as $value):?>
      <?php echo $value;?>
    <?php endforeach;?>
  <?php endif;?>

  <?php print_r($_POST['select_id']); ?>
  <?php print_r($message_data); ?>
  <?php print_r($select_id); ?>
  <br>
  <?php foreach($select_id as $key => $value): ?>
  <?php echo $value['view_name']; ?>
  <?php endforeach; ?>
  <br>
  <?php print_r($_POST['id']) ?>
      <p>delete Are you ok?</p><br>
      <p>if you want not to delete message ,please remove check on the CheckBox</p>
<?php if(empty($select_id) && !empty($_GET['message_id'])): ?>
  <?php foreach($message_data as $mess): ?>
  <form method="post">
      <div>
        <label for="">Name</label><br>
        <input type="text" name="view_name" value="<?= $mess['view_name']; ?>" disabled>
      </div>
      <div>
        <label for="">Message</label><br>
        <textarea name="message" id="" cols="30" rows="10" disabled><?= $mess['message'] ?></textarea>
      </div>
      <input type="submit" name="submit" value="delete">
      <input type="hidden" name="message_id" value="<?php if(!empty($message_data['id'])){ echo $message_data['id'];}?>">
  </form>
  <?php endforeach; ?>
    <a href="./admin.php">cancel</a>
<?php elseif(!empty($select_id)): ?>
  <?php foreach($select_id as $key => $value): ?>
  <form action="" method="post">
    <div>
      <label for="">Name</label>
      <input name="view_name" type="text" value="<?php if(!empty($value['view_name'])){echo $value['view_name'];} ?>" disabled>
    </div>
    <div>
      <label for="">message</label>
      <textarea name="message" id="" cols="30" rows="10" disabled><?php if(!empty($value['message'])){echo $value['message'];} ?></textarea>
    </div>
    <input type="checkbox" name="id[]" value="<?php if(!empty($value['id'])){echo $value['id'];} ?>" checked>
    <?php if(!empty($value['id'])){echo $value['id'];} ?>
  
<?php endforeach; ?>
<input type="submit" value="manyDelete">
</form>
<a href="admin.php">cancel</a>
<?php elseif(empty($_POST['select_id']) && empty($_GET['message_id'])): ?>
  <p>Please check checkbox</p>
  <a href="admin.php">Back page</a>
<?php endif; ?>
</body>
</html>