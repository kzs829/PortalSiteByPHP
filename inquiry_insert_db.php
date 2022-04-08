<?php include('header.php'); ?>
<?php
$body = mb_convert_encoding($_POST['body'], "UTF-8", "HTML-ENTITIES");
$inquiry_subject = mb_convert_encoding($_POST['subject'], "UTF-8", "HTML-ENTITIES");
$send_list = json_decode($_POST['send_list']);

//DBとの接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  //お問合せをDBにinsert
  list($err_message, $bool) = insert_inquiry($pdo);
}

if($bool == FALSE){
  $alert = "<script type='text/javascript'>alert('お問合せの登録に失敗しました。');</script>";
  echo $alert;
?>
  <form action = "inquiry.php" method = "post" name = "post">
    <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  </form>
<?php
}else{
  $alert = "<script type='text/javascript'>alert('お問合せを受け付けしました。担当者より受付完了メールが送信されますので、お待ちください。以降のやり取りはマイページのお問合せチャットからお願いいたします。');</script>";
  echo $alert;
  //お問合せ受付メールを送信
  //ユーザ側
  $name = mb_convert_encoding($_POST['name'], "UTF-8", "HTML-ENTITIES");
  $sender = mb_convert_encoding($_POST['name'], "UTF-8", "HTML-ENTITIES");
  $to = mb_convert_encoding($_POST['mail'], "UTF-8", "HTML-ENTITIES");
  $subject = subject_accept_inquiry;
  $message = "※このメールはシステムからの自動配信です。\n"
             ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
             .$name."様\n\n"
             ."以下の内容でお問合せをシステム受付いたしました。\n"
             ."今後のやり取りはマイページのお問合せチャットよりお願いいたします。\n"
             .url."mypage.php\n\n"
             ."―――――――――\n"
             ."SC戦略プロセス改善G：sctool@sample.com\n"
             ."HP：".url."index.php\n\n"
             ."━━━━━━□■□　お問い合わせ内容　□■□━━━━━━\n"
             ."問合せ者：".$sender."\n"
             ."件名：".$inquiry_subject."\n"
             ."お問合せ内容：".$body."\n\n";
  list($err_message, $bool) = send_mail($to, $subject, $message);

  //管理者側
  $name = '管理者';
  $to = kanri_mail;
  $subject = subject_accept_inquiry;
  $message = "※このメールはシステムからの自動配信です。\n"
             ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
             .$name."様\n\n"
             ."以下の内容でお問合せをシステム受付いたしました。\n"
             ."今後のやり取りは管理画面のお問合せチャットよりお願いいたします。\n"
             .url."inquiry_management.php\n\n"
             ."―――――――――\n"
             ."SC戦略プロセス改善G：sctool@sample.com\n"
             ."HP：".url."index.php\n\n"
             ."━━━━━━□■□　お問い合わせ内容　□■□━━━━━━\n"
             ."問合せ者：".$sender."\n"
             ."件名：".$inquiry_subject."\n"
             ."お問合せ内容：".$body."\n\n";
  list($err_message, $bool) = send_mail($to, $subject, $message);

  //cc追加者
  $subject = subject_accept_inquiry;
  $message = "※このメールはシステムからの自動配信です。\n"
              ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
              ."以下のお問合せのCcに追加されました。\n"
              ."今後のやり取りはマイページのお問合せチャットよりお願い致します。\n"
              .url."mypage.php\n\n"
              ."なお、会員登録がお済みでない方は以下のリンクから会員登録をお願いいたします。\n"
              .url."pre_register.php\n\n"
              ."―――――――――\n"
              ."SC戦略プロセス改善G：sctool@sample.com\n"
              ."HP：".url."index.php\n\n"
              ."━━━━━━□■□　お問い合わせ内容　□■□━━━━━━\n"
              ."問合せ者：".$sender."\n"
              ."件名：".$inquiry_subject."\n"
              ."お問合せ内容：".$body."\n\n";
  foreach($send_list as $to){
    list($err_message, $bool) = send_mail($to, $subject, $message);
  }
  
?>
  <form action = "mypage.php" method = "post" name = "post"></form>
<?php } ?>

<?php
  function insert_inquiry($pdo){
    $err_message = null;
    try{
      $inquiry_no = uniqid();
      foreach ($_FILES['upfile']['tmp_name'] as $no => $tmp_name) {
        //ファイル名が重複していた場合連番をつける
        $filename = mb_convert_encoding($_FILES['upfile']['name'][$no], "UTF-8", "HTML-ENTITIES");

        if($filename){
          $count = $pdo -> prepare('SELECT COUNT(*) AS count FROM inquiry_file WHERE file_name like :filename');
          $count -> bindValue(":filename", "%".$filename, PDO::PARAM_STR);
          $count -> execute();
          $total_count = $count -> fetch(PDO::FETCH_ASSOC);
      
          if($total_count && $total_count['count'] > 0){
            $filename = "(".$total_count['count'].")".$filename;
          }

          //ファイル格納
          $path = inquiry_file_path;
          move_uploaded_file($tmp_name, $path.$filename);

          //ファイル名、ファイルパスをDBに格納
          $tsql = "INSERT INTO inquiry_file (inquiry_no, file_name, file_path) VALUES (?, ?, ?);";
          $stmt = $pdo->prepare($tsql);
          $params = array($inquiry_no, $filename, $path.$filename);
          $stmt->execute($params);
        }
      }

      //DBに格納
      $user_no = $_SESSION['USER_NO'];
      $subject = mb_convert_encoding($_POST['subject'], "UTF-8", "HTML-ENTITIES");
      $body = mb_convert_encoding($_POST['body'], "UTF-8", "HTML-ENTITIES");
      $publishing_setting = mb_convert_encoding($_POST['publishing-setting'], "UTF-8", "HTML-ENTITIES");

      $create_time = new DateTime();
      $create_time -> setTimeZone( new DateTimeZone('Asia/Tokyo'));
      $create_time = $create_time -> format('Y-m-d H:i');

      $status = '未対応';
      $response = '';

      $send_list = json_decode($_POST['send_list']);
      $cc = implode(",", $send_list);

      $tsql = "INSERT INTO inquiry (inquiry_no, user_no, subject, body, create_time, status, publishing_setting, cc) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
      $stmt = $pdo->prepare($tsql);
      $params = array($inquiry_no, $user_no, $subject, $body, $create_time, $status, $publishing_setting, $cc);
      $stmt->execute($params);

      return [$err_message, TRUE];
    } catch (Exception $e){
      $err_message = '[ERR-04]お問合せの登録に失敗しました。（' . $e->getMessage() . '）';
      return [$err_message, FALSE];
    }
  }
?>
<?php include('footer.php'); ?>
