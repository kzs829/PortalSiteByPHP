<?php include('header.php'); ?>
<?php
  //DB接続
  list($pdo, $err_message, $bool) = connection_db_2();

  if($bool){
    //ユーザー情報の更新
    list($err_message, $bool) = update_user_info($pdo);
  }

  if($bool){
    $alert = "<script type='text/javascript'>alert('アカウント情報の変更が完了しました。');</script>";
    echo $alert;
  }else{
    $alert = "<script type='text/javascript'>alert('アカウント情報の変更が失敗しました。');</script>";
    echo $alert;
  }
?>
<form action = "mypage.php" method = "post" name = "post">
    <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
</form>
<?php
  //アカウント情報の更新
  function update_user_info($pdo){
    $err_message = null;
    try{
      //postの値を取得
      $name = $_POST['name'];
      $mail = $_POST['mail'];
    
      $sql = "UPDATE user_info SET name = ?, mail = ? WHERE mail = ?";
      $stmt = $pdo -> prepare($sql);
      $params = array($name, $mail, $_SESSION['MAIL']);
      $stmt -> execute($params);
    
      $_SESSION['NAME'] = $name;
      $_SESSION['MAIL'] = $mail;      

      return [$err_message, TRUE];
    } catch (Exception $e){
      $err_message = '[ERR-07]ユーザー情報の更新に失敗しました。（' . $e->getMessage() . '）';
      return [$err_message, FALSE];
    }
  }
?>
<?php include('footer.php'); ?>