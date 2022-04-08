<?php include('header.php'); ?>  
<?php
//DB接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  list($err_message, $bool) = search_user($pdo);
}
?>

<?php if($bool){
  header('Location: index.php');
} else{ ?>
  <form action = "login.php" method = "post" name = "post">
    <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  </form>
<?php } ?>

<?php
function search_user($pdo){
  $err_message = null;

  $mail = $_POST['mail'];
  $password = $_POST['password'];

  $stmt = $pdo -> prepare("SELECT * FROM user_info WHERE mail = ?;");
  $params = array($mail);
  $stmt -> execute($params);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if(!(isset($result['mail']))){
    $err_message = 'メールアドレスかパスワードが間違っています。';
    return [$err_message, FALSE];
  }else if($result['deleted_flg'] == 1){
    $err_message = 'このアカウントは削除されています。';
    return [$err_message, FALSE];
  }else if(!($result['password'] == $password)){
    $err_message = 'メールアドレスかパスワードが間違っています。';
    return [$err_message, FALSE];
  }
  $_SESSION['USER_NO'] = $result['user_no'];
  $_SESSION['NAME'] = $result['name'];
  $_SESSION['MAIL'] = $result['mail'];
  $_SESSION['PASSWORD'] = $result['password'];
  $_SESSION['ADMIN_FLG'] = $result['admin_flg'];
  return [$err_message, TRUE];
}
?>
<?php include('footer.php'); ?>