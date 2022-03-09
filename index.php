<?php
require('function.php');

$stmt=null;
$pdo=null;
$res=null;
$option=null;
$error_message=array();
$success_message=array();
$message_array=array();
$profile=array();
session_start();


if(isset($_SESSION['id'])){
  $msg='Hello!!!'.'  '.htmlspecialchars($_SESSION['name'],\ENT_QUOTES,'UTF-8');
}else{
  $msg='Are you login? ログインしないと投稿できません。';
}

if(!empty($_POST['logout'])){
  if($_POST['logout']){
  session_destroy();
  header('Location:index.php');
}

}

try{
  $option=array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  );

  $pdo = new PDO('mysql:charset=UTF8;dbname=board4;host=localhost','root','kouking2001',$option);
}catch(PDOException $e){
  $error_message[] = $e->getMessage();
}

if(isset($_FILES['profile'])){
   $profile=$_FILES['profile'];
     if($profile['size']>0){
  if($profile['size']>1000000){
    $error_message[]='picture size too big';
  }else{
    move_uploaded_file($profile['tmp_name'],'../image/'.$profile['name']);
  }
}
}elseif(empty($_FILES['profile'])){
  $profile['name']='character_ebi_fry.png';
}

if(!empty($_POST['send'])){

 
  if(empty($_POST['message'])){
    $error_message[]='Input message';
  }

  
  

  if(empty($error_message)){
    $current_date=date("Y-m-d H:i:s");
    
    $stmt = $pdo->prepare("INSERT INTO message (view_name,message,post_date,profile,post_id) VALUES(:view_name,:message,:current_date,:profile,:post_id)");

    $stmt->bindParam(':view_name', $_POST['view_name'],PDO::PARAM_STR);
    $stmt->bindParam(':message',$_POST['message'],PDO::PARAM_STR);
    $stmt->bindParam(':current_date',$current_date,PDO::PARAM_STR);
    $stmt->bindParam(':profile',$profile['name'],PDO::PARAM_STR);
    $stmt->bindParam(':post_id',$_POST['post_id'],PDO::PARAM_INT);
    $res=$stmt->execute();

    if($res){
      $_SESSION['success_message'] = '投稿しました';
    
    }else{
      $error_message[]='failure...';
    }

    $stmt=null;

    //  header('Location:index.php');

  }
  
}
    $pdo=null;
  
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <script src="../jquery-3.6.0.min.js"></script>
  <title>BULLETINE</title>


</head>
<body>
  <h1>BULLETINE</h1>
  adminの方はこちら<br>
  ↓↓<br>
  <a href="admin.php">Admin Page</a><br>

  <?php echo $msg; ?>
  <?php if(isset($_SESSION['id'])): ?>
    <form method="post">
      <input type="submit" name="logout" value="logout">
    </form>   
    <a href="mypage.php">mypage</a>

  <?php elseif(empty($_SESSION['id'])): ?>
    <a href="log.php">login</a><br>
    <?php endif; ?>

  <?php if(!empty($error_message)): ?>
      <p><?php foreach($error_message as $value): ?></p>
        <?php echo $value; ?>
        <?php endforeach; ?>
        <?php endif; ?>

    <?php if(isset($_SESSION['success_message'])){
        echo $_SESSION['success_message'];
    }
    ?>

 <?php if(isset($_SESSION['id'])): ?>
  <a href="#" class="post_process">投稿</a>
  <div class="modal"></div>
    <div class="post_window">

      <div class="empty_message"></div>
      <form method="post" enctype="multipart/form-data" accept-charset="ASCII">
        <div>
        
          <label for="">Name</label><br>
          <input type="text" name="view_name" id="text" value="<?= $_SESSION['name']; ?>" readonly>
          
        </div>
        <div>
          <label for="">Message</label><br>
          <textarea id="message"  name="message" cols="30" rows="10"></textarea>
        </div>
        <input type="file" name="profile" accept="image/*" multiple>
        <input type="hidden" name="post_id" value="">
        <input class="post_button" type="submit" name="send" value="Post" >
        <button class="cancel" >cancel</button>
      </form>
    </div>
 
  
  <?php endif; ?>
<hr>
  
<?php $message_array = get_post(''); ?>
  <?php foreach($message_array as $value): ?>
      <article>
          <img src="../image/<?= $value['profile']; ?>">
        <div>
          <label for="">[user]</label>
          <?php echo $value['view_name']; ?><br>
          <label for="">[Message]</label>
          <p><?php echo nl2br($value['message']); ?></p>
          <label for="">[Time]</label>
          <time><?php echo $value['post_date']; ?></time><br>
          id:<?php echo $value['id']; ?>
        </div>
      </article>
      <?php $message_id[]=$value['id']; ?>
      <?php if(isset($_SESSION['id'])): ?>
      <a class="comment_pro" href="comment_ad.php?message_id=<?php echo $value['id']; ?>">comment</a>
      <a href="user_delete.php?message_id=<?= $value['id']; ?>">delete</a>
      <?php endif; ?>
      <hr>


    <?php $comments=get_post($value['id']); ?>
    
      <?php foreach($comments as $come): ?>
          <h3>COMMENT</h3>
          <img src="../image/<?= $come['profile']; ?>">
          [USER NAME]
          <?= $come['view_name']; ?>
          [COMMENT]
          <?= $come['message']; ?>
          COMMENT AT <?= $come['post_date']; ?>
          <?php if(isset($_SESSION['id'])): ?>
            <a href="comment_ad.php?message_id=<?= $come['id']; ?>">comment_rep</a>
            
          <?php endif; ?>
        <hr>

        <?php $reps = get_post($come['id']);?>
        <?php foreach($reps as $rep): ?>

          <h4>comment rep</h4>
          <img src="../image/<?= $rep['profile']; ?>">
          username:
          <?= $rep['view_name']; ?>
          comment:
          <?= $rep['message']; ?>
          comment at:
          <?= $rep['post_date']; ?>
          <?php if(isset($_SESSION['id'])): ?>
            <a href="">comment_rep まだできない</a>
          <?php endif; ?>
          <hr>

        <?php endforeach; ?>  

      <?php endforeach; ?>

  <?php endforeach; ?>


<script src="post.js"></script>
</body>
</html>