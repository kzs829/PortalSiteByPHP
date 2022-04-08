<?php include('header.php'); ?>
<?php
  //DBとの接続
  list($pdo, $err_message, $bool) = connection_db_2();

  $_SESSION['token'] = bin2hex(random_bytes(32));
  $token = $_SESSION['token'];
  if(isset($_SESSION['ADMIN_FLG']) && $_SESSION['ADMIN_FLG'] == 1){
    $admin_flg = 1;
    $url = url."/register.php?token=".$token."&admin_flg=1";
  }else{
    $url = url."/register.php?token=".$token."&admin_flg=0";
  }

  if($bool){
    //メールアドレスが既存か確認
    list($err_message, $bool) = confirm_exist($pdo, $token);
  }

  if($bool == false){
?>
    <form action = "pre_register.php" method = "post" name = "post">
      <input type = "hidden" name = "err_message"  value = "<?php echo $err_message; ?>">
    </form>
<?php
    exit;
  }else{
    $to = $_POST['mail'];
    $subject = subject_preregister;
    $message = "※このメールはシステムからの自動配信です。\n"
               ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
               ."仮会員登録が完了いたしました。\n"
               ."24時間以内に下記URLから本登録の実施をお願いいたします。\n"
               .$url."\n\n"
               ."―――――――――\n"
               ."SC戦略プロセス改善G：sctool@sample.com\n"
               ."HP：".url."index.php\n\n";
    list($err_message, $bool) = send_mail($to, $subject, $message);
  }
?>
<main>
  <div style = "padding: 10px; text-align:center;">
    <h2 class = "subheading-center">仮登録が完了しました</h2>
    <p>
      ※まだ会員登録は完了していません<br>
      ご登録いただいたメールアドレスにメールをお送りいたしました。<br>
      24時間以内にメールに記載のURLから本登録を実施してください。
    </p>
  </div>
</main>
<?php
function confirm_exist($pdo, $token){
  $err_message = null;
  $mail = $_POST['mail'];
  $tsql = "SELECT * FROM user_info WHERE mail = ?;";
  $stmt = $pdo -> prepare($tsql);
  $params = array($mail);
  $stmt -> execute($params);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  if(isset($result['mail'])){
    $deleted_flg = $result['deleted_flg'];
    if($deleted_flg == 0){
      $err_message = 'このメールアドレスは既に登録されています。';
      return [$err_message, FALSE];
    }
  }

  $date = new DateTime();
  $date -> setTimeZone( new DateTimeZone('Asia/Tokyo'));
  $date = $date -> format('Y-m-d H:i');

  $tsql = "INSERT INTO pre_register (token, mail, date) VALUES (?, ?, ?);";
  $stmt = $pdo -> prepare($tsql);
  $params = array($token, $mail, $date);
  $stmt -> execute($params);

  return [$err_message, TRUE];
}
?>
<?php include('footer.php'); ?>
