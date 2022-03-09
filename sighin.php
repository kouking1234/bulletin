<?php
require('dbconnect.php');
$error_message=array();
//session使って失敗したら先ほどうったやつを表示させ、せいこうしたらsession なくす
session_start();

if(empty($_POST['mail'])){
  $error_message[]='empty mail';
}
if(empty($_POST['name'])){
  $error_message[]='empty name';
}
if(empty($_POST['pass'])){
  $error_message[]='empty pass';
}
if(!empty($_POST['mail'])){
    $sql="SELECT * from users where mail = :mail";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':mail',$_POST['mail']);
  $stmt->execute();
  $member=$stmt->fetch();
  if($member['mail'] === $_POST['mail']){
    $error_message[]='Already exit mail addres';
  }
}




if(empty($error_message)){
  $sql="INSERT INTO users (name,mail,pass) VALUES (?,?,?)";
  $stmt=$pdo->prepare($sql);
  $data[]=$_POST['name'];
  $data[]=$_POST['mail'];
  $data[]=password_hash($_POST['pass'],PASSWORD_DEFAULT);
  $res=$stmt->execute($data);

  if($res){
    $success='会員登録 success';
  }
  
}
$pdo=null;
?>

<?php
if(isset($error_message)){
    foreach($error_message as $error){
    echo $error.'<br>';
  }
}

?>
  <?php if(isset($success)): ?>
     <?php echo $success; ?>
     <a href="log.php">LOGIN Page</a>
  <?php endif ?>
 

<?php if(empty($success)): ?>
<h1>新規会員登録</h1>
<form action="" method="post">
  <label for="">Name</label>
  <input type="text" name="name" required>
  <label for="">Mail address</label>
  <input type="text" name="mail" required><br>
  <label for="">Password</label>
  <input type="password" name="pass" required>
  <button type="submit">新規登録</button>
</form>
<p>会員登録済みの方は<a href="log.php">こちら</a></p>
<?php endif; ?>
