<?php include('header.php'); ?>
<?php
$new_pass = $_POST['new_pass'];

//DB接続
list($pdo, $err_message, $bool) = connection_db_2();

//パスワードが正しいか確認
if($bool){
  list($err_message, $bool) = check_pass($pdo, $new_pass);
}

if($bool == FALSE){
?>
  <form action = "change_password.php" method = "post" name = "post">
    <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  </form>
<?php
}else if($bool == TRUE){
?>
  <form action = "update_password.php" method = "post" name = "post">
    <input type = "hidden" name = "new_pass" value = "<?php echo $new_pass; ?>">
  </form>
<?php
}
?>
<?php
function check_pass($pdo, $new_pass){
  $err_message = null;
  $current_pass = $_POST['current_pass'];
  $new_pass2 = $_POST['new_pass2'];
  
  if(!($new_pass == $new_pass2)){
    $err_message = '新しいパスワードと新しいパスワード(確認用)の値が異なります。';
    return [$err_message, FALSE];
  }

  $stmt = $pdo -> prepare("SELECT * FROM user_info WHERE mail = ?;");
  $params = array($_SESSION['MAIL']);
  $stmt -> execute($params);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  var_dump($current_pass);
  var_dump($result['password']);

  if($result['password'] != $current_pass){
    $err_message = '現在のパスワードが間違っています。';
    return [$err_message, FALSE];
  }

  return [$err_message, TRUE];
}
?>
<?php include('footer.php'); ?>
