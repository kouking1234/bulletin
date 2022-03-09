<?php 
require('function.php');
session_start();

if(isset($_POST['submit_edit'])){
  $pdo = new PDO('mysql:charset=UTF8;dbname=board4;host=localhost','root','kouking2001');
$sql = 'UPDATE message SET message=:message WHERE id=:id';
$stmt=$pdo->prepare($sql);
$res=$stmt->execute(array(':message'=>$_POST['message_edit'],':id'=>$_POST['edit_id']));
if($res){
  $msg='編集できました。';
}else{
  $msg='編集できませんでした。';
}
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <script src="../jquery-3.6.0.min.js"></script>

  <title>Document</title>
</head>
<body>
  <h1><?php echo $_SESSION['name']; ?>'s Page </h1>

  <a href="index.php">back bulletine</a>
  <?php $my_post=get_my_post($_SESSION['name']); ?>
  <?php foreach($my_post as $value): ?>

    <img src="../image/<?= $value['profile']; ?>" alt="">
    <p><?= $value['view_name']; ?></p>
    <p><?= $value['message']; ?></p>
    <p><?= $value['post_date']; ?></p>
    <a href="#" id="edit_btn" data-target="#edit<?= $value['id'] ?>">編集</a>
    
    <div class="edit" data-form="#edit<?= $value['id'] ?>">
      <form method="post">
        <img src="../image/<?= $value['profile'] ?>" alt="">
        <p><?= $value['view_name']; ?></p>
        <textarea name="message_edit"><?= $value['message'] ?></textarea>
        <input type="hidden" name="edit_id" value="<?= $value['id']; ?>">
        <input type="submit" id="editing" value="変更する" name="submit_edit">
        <button class="cancel">cancel</button>
      </form>
    </div>
    <hr>

    <!-- <div class="modal"></div> -->
  
  <?php endforeach; ?>
 
  <script src="post.js"></script>
</body>
</html>