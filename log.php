<?php
require('dbconnect.php');
session_start();
if(isset($_POST['cancel'])){
  header("Location:index.php");
}

if(isset($_POST['submit'])){
    $sql="SELECT * FROM users where mail = :mail";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':mail',$_POST['mail']);
  $stmt->execute();
  $member = $stmt->fetch();



  if(password_verify($_POST['pass'],$member['pass'])){
    $_SESSION['id'] = $member['id'];
    $_SESSION['name'] = $member['name'];
    header("Location:./index.php");
  }else{
    $error_message='メールアドレスか、パスワードが違います。';
  }
}
?>

<?php 

if(isset($error_message)){
  echo $error_message;
} 

?>


<h1>login page</h1>
<form action="" method="post">
  <label for="">mail</label>
  <input type="text" name="mail">
  <label for="">pass</label>
  <input type="password" name="pass">
  <input type="submit" name="submit">
</form>
<form method="post">
  <button type="submit" name="cancel">cnacel</button>
</form>
<p>新規登録は<a href="./sighin.php">こちら</a></p>