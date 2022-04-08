<?php include('header.php'); ?>
<?php
  //DBとの接続
  list($pdo, $err_message, $bool) = connection_db_2();

  if($bool){
    //ユーザ情報を復活
    list($err_message, $bool) = restoration_user($pdo);
  }
?>

<?php
if($bool){
  $alert = "<script type='text/javascript'>alert('アカウント復活が完了しました。');</script>";
  echo $alert;
  //登録完了メールを送信
  $name = $_POST['name'];
  $to = $_POST['mail'];
  $mail = $_POST['mail'];
  $subject = subject_restoration;
  $message = "※このメールはシステムからの自動配信です。\n"
             ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
             .$name."様\n\n"
             ."下記アカウントのSC戦略HPへの再登録が完了しました。\n"
             ."ID：".$mail."\n\n"
             ."HPはこちらからアクセス\n"
             .url."index.php\n\n"
             ."―――――――――\n"
             ."SC戦略プロセス改善G：sctool@sample.com";
  list($err_message, $bool) = send_mail($to, $subject, $message);
  if(isset($_POST['user_no'])){
?>
    <form action = "user_detail.php" method = "post" name = "post">
      <input type = "hidden" name = "user_no" value = "<?php echo $_POST['user_no']; ?>">
    </form>
<?php
  }else{
?>
    <form action = "login.php" method = "post" name = "post">
      <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
    </form>
<?php
  }
} else{ 
  $alert = "<script type='text/javascript'>alert('アカウント復活に失敗しました。');</script>";
  echo $alert;
?>
  <form action = "register.php" method = "post" name = "post">
    <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  </form>
<?php } ?>

<?php
  function restoration_user($pdo){
    $err_message = null;
    try{
      $name = $_POST['name'];
      $mail = $_POST['mail'];
      $password = $_POST['password'];
  
      $tsql = "UPDATE user_info SET deleted_flg = 0, name = ?, password = ? WHERE mail = ?";
      $stmt = $pdo->prepare($tsql);
      $params = array($name, $password, $mail);
      $stmt->execute($params);

      return [$err_message, TRUE];
    } catch (\Exception $e){
      $err_message = '[ERR-10]アカウント復活に失敗しました。';
      return [$err_message, FALSE];
    }
  }
?>
<?php include('footer.php'); ?>