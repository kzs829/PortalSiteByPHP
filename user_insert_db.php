<?php include('header.php'); ?>
<?php
  //DBとの接続
  list($pdo, $err_message, $bool) = connection_db_2();

  if($bool){
    //ユーザ情報をDBにinsert
    list($err_message, $bool) = insert_user($pdo);
  }
?>

<?php
if($bool){
  if(isset($_SESSION['ADMIN_FLG'])){
    unset($_SESSION['ADMIN_FLG']);
  }
  $alert = "<script type='text/javascript'>alert('会員登録が完了しました。');</script>";
  echo $alert;
  //登録完了メールを送信
  $name = $_POST['name'];
  $mail = $_POST['mail'];
  $to = $_POST['mail'];
  $subject = subject_register;
  $message = "※このメールはシステムからの自動配信です。\n"
             ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
             .$name."様\n\n"
             ."下記メールアドレスのSC戦略HPへの会員登録が完了しました。\n"
             ."ID：".$mail."\n\n"
             ."HPはこちらからアクセス\n"
             .url."index.php\n\n"
             ."―――――――――\n"
             ."SC戦略プロセス改善G：sctool@sample.com";
  list($err_message, $bool) = send_mail($to, $subject, $message);
?>
  <form action = "login.php" method = "post" name = "post">
    <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  </form>
<?php
} else{ 
  $alert = "<script type='text/javascript'>alert('会員登録に失敗しました。');</script>";
  echo $alert;
?>
  <form action = "register.php" method = "post" name = "post">
    <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  </form>
<?php } ?>

<?php
  function insert_user($pdo){
    $err_message = null;
    try{
      $name = $_POST['name'];
      $mail = $_POST['mail'];
      $password = $_POST['password'];
      $admin_flg = 0;
      if(isset($_SESSION['ADMIN_FLG'])){
        if($_SESSION['ADMIN_FLG'] == 1){
          $admin_flg = 1;
        }
      }
  
      $tsql = "INSERT INTO user_info (name, mail, password, admin_flg) VALUES (?, ?, ?, ?);";
      $stmt = $pdo->prepare($tsql);
      $params = array($name, $mail, $password, $admin_flg);
      $stmt->execute($params);

      //仮登録のデータ無効にする
      $tsql = "UPDATE pre_register SET invalid_flg = 1 WHERE mail = ?";
      $stmt = $pdo->prepare($tsql);
      $params = array($mail);
      $stmt->execute($params);

      return [$err_message, TRUE];
    } catch (\Exception $e){
      $err_message = '[ERR-05]ユーザ情報の登録に失敗しました。（' . $e->getMessage() . '）';
      return [$err_message, FALSE];
    }
  }
?>
<?php include('footer.php'); ?>