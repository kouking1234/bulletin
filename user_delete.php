<?php
require('function.php');

if(isset($_POST['delete'])){
  foreach($_POST['deleid']  as $key => $deleid){
      $pdo=new PDO('mysql:charset=UTF8;dbname=board4;host=localhost','root','kouking2001');
    $stmt=$pdo->prepare("DELETE FROM message where id = :id");
    $stmt->bindValue(':id',$deleid,PDO::PARAM_INT);
    $res=$stmt->execute();
    
      if($res){
        header('Location:index.php');
      }else{
        $msg[]='消せませんでした。';
      }
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
  <title>Document</title>
</head>
<body> 
  <?php if(!empty($msg)){
    echo $msg;
  } ?>
  <?php $delete_comment = get_comment($_GET['message_id']); ?>
  <?php  foreach($delete_comment as $dele): ?>
          <?= $dele['profile']; ?>
          <?= $dele['view_name']; ?><br>
          <?= $dele['message']; ?><br>
          <?= $dele['post_date']; ?><br>
    <form action="" method="post">
         <input type="hidden" type="checkbox" name="deleid[]" value="<?php echo $dele['id'] ?>" checked>
          <hr>
          <?php $comes=get_post($dele['id']); ?>

          <p>このコメントも同時に消されます</p>
        <?php foreach($comes as $come):
              echo 'comment';
              echo '<br>';
              echo $come['profile']; 
              echo $come['view_name'].',';
              echo $come['message'].',';
              echo $come['post_date'];?>
              <input type="hidden" type="checkbox" name="deleid[]" value="<?php echo $come['id']; ?>" checked>
              <hr>
          <?php endforeach; ?>
   
      <?php
     if(isset($come)){
       $reps=get_post($come['id']);
     }
          
if(isset($reps)):
        foreach($reps as $rep):
    
              echo 'reply';
              echo '<br>';
              echo $rep['profile'];
              echo $rep['view_name'].',';
              echo $rep['message'].',';
              echo $rep['post_date'];?>
              <input type="hidden" type="checkbox" name="deleid[]" value="<?php echo $rep['id']; ?>" checked>
              <hr>
          <?php endforeach; ?>
   <?php endif; ?>
  <?php endforeach; ?>
  
      <button type="submit" name="delete">削除</button>
    </form>
    <a href="index.php">キャンセル</a>
</body>
</html>