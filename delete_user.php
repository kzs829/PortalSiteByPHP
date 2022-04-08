<?php include('header.php'); ?>
<?php
//DBと接続
list($pdo, $err_message, $bool) = connection_db_2();

//管理者の場合管理者が２人以上いるかチェック
if($_POST['admin_flg'] == 1){
  $count = $pdo -> query('SELECT COUNT(*) AS count FROM user_info WHERE admin_flg = 1');
  $count = $count -> fetch(PDO::FETCH_ASSOC);
  if($count['count'] == 1){
    $alert = "<script type='text/javascript'>alert('管理者アカウントが１つしかない場合、管理者アカウントを削除することはできません。');</script>";
    echo $alert;
    $bool = False;
?>
    <form action = "mypage.php" method = "post" name = "post"></form>
<?php
    exit;
  }
}

if($bool){
  //アカウント削除
  list($err_message, $bool) = delete_user($pdo);
}

if($bool){
  //アカウント削除成功時
  $alert = "<script type='text/javascript'>alert('アカウント削除が完了しました。');</script>";
  echo $alert;
  if($_SESSION['USER_NO'] == $_POST['user_no']){
?>
    <form action = "logout.php" method = "post" name = "post"></form>
<?php
  }else{
?>
    <form action = "user_list.php" method = "post" name = "post"></form>
<?php
  }
}else{
  //アカウント削除失敗時
  $alert = "<script type='text/javascript'>alert('アカウント削除に失敗しました。');</script>";
  echo $alert;
?>
  <form action = "account_deletion.php" method = "post" name = "post">
    <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  </form>
<?php
  }
?>

<?php
//アカウント削除関数
function delete_user($pdo){
  $err_message = null;
  try{
    //postの値を取得
    $user_no = $_POST['user_no'];

    //ユーザー情報を削除
    $tsql = "UPDATE user_info SET deleted_flg = 1 WHERE user_no = ?;";
    $stmt = $pdo -> prepare($tsql);
    $params = array($user_no);
    $stmt -> execute($params);
    return [$err_message, TRUE];
  } catch (Exception $e){
    $err_message = '[ERR-02]DBの更新に失敗しました。（' . $e->getMessage() . '）';
    return [$err_message, FALSE];
  }
}
?>
<?php include('footer.php'); ?>
