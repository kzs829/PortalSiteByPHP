<?php include('header.php'); ?>
<?php
  //DB接続
  list($pdo, $err_message, $bool) = connection_db_2();
  if($bool){
    list($err_message, $bool) = update_userinfo($pdo);
  }
  
  if($bool){
    $alert = "<script type='text/javascript'>alert('アカウント情報の変更が完了しました。');</script>";
    echo $alert;
  }else{
    $alert = "<script type='text/javascript'>alert('アカウント情報の変更が失敗しました。');</script>";
    echo $alert;
  }
?>
<form action = "user_detail.php" method = "post" name = "post">
  <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  <input type = "hidden" name = "user_no" value = "<?php echo $_POST['user_no']; ?>">
</form>
<?php
//ユーザー情報の更新
function update_userinfo($pdo){
  $err_message = null;
  try{
    $authority = $_POST['authority'];
    $user_no = $_POST['user_no'];

    if($authority == '管理者'){
      $admin_flg = 1;
    }else{
      $admin_flg = 0;
    }
    $sql = "update user_info set admin_flg = ? where user_no = ?";
    $stmt = $pdo -> prepare($sql);
    $params = array($admin_flg, $user_no);
    $stmt -> execute($params);
    return [$err_message, TRUE];
  } catch (Exception $e){
    $err_message = '[ERR-07]ユーザー情報の更新に失敗しました。（' . $e->getMessage() . '）';
    return [$err_message, FALSE];
  }
}
?>
<?php include('footer.php'); ?>