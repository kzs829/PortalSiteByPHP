<?php include('header.php'); ?>
<?php
  list($pdo, $err_message, $bool) = connection_db_2();
  if($bool){
    list($err_message, $bool) = update_password($pdo);
  }
  if($bool){
    $alert = "<script type='text/javascript'>alert('パスワードの変更が完了しました。');</script>";
    echo $alert;
  }else{
    $alert = "<script type='text/javascript'>alert('パスワードの変更が失敗しました。');</script>";
    echo $alert;
  }
?>
<form action = "mypage.php" method = "post" name = "post">
    <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
</form>
<?php
  function update_password($pdo){
    $err_message = null;
    try{
      //postの値を取得
      $new_pass = $_POST['new_pass'];
    
      $sql = "UPDATE user_info SET password = ? WHERE mail = ?";
      $stmt = $pdo -> prepare($sql);
      $params = array($new_pass, $_SESSION['MAIL']);
      //SQLを実行
      $stmt -> execute($params);
    
      $_SESSION['password'] = $new_pass;      

      return [$err_message, TRUE];
    } catch (Exception $e){
      $err_message = 'パスワードの変更に失敗しました。（' . $e->getMessage() . '）';
      return [$err_message, FALSE];
    }
  }
?>
<?php include('footer.php'); ?>