<?php include('header.php'); ?>
<?php
  $response = mb_convert_encoding($_POST['response'], "UTF-8", "HTML-ENTITIES");
  $apply_id = mb_convert_encoding($_POST['apply_id'], "UTF-8", "HTML-ENTITIES");
  $type = mb_convert_encoding($_POST['type'], "UTF-8", "HTML-ENTITIES");
  $sender = $_SESSION['NAME'];

  //DBとの接続
  list($pdo, $err_message, $bool) = connection_db_2();
  
  if($bool){
    //お問合せチャットDBの更新
    list($err_message, $bool) = update_inquiry_chat($pdo);
  }

  if($bool && $response){
    //お問合せ返答通知メールの送信
    $subject = subject_reply_apply_inquiry;
    $message_kanri =  "※このメールはシステムからの自動配信です。\n"
                      ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
                      ."申込番号#".$apply_id."に以下のメッセージを受け付けました。\n"
                      ."各種申込の申込一覧よりご確認ください。\n"
                      .url."apply_list.php\n\n"
                      ."―――――――――\n"
                      ."SC戦略プロセス改善G：sctool@sample.com\n"
                      ."HP：".url."index.php\n\n"
                      ."━━━━━━□■□　ご返答内容　□■□━━━━━━\n"
                      ."From：".$sender."\n"
                      .$response."\n\n";
    $message_user = "※このメールはシステムからの自動配信です。\n"
                    ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
                    ."申込番号#".$apply_id."に以下のメッセージを受け付けました。\n"
                    ."各種申込の申込一覧よりご確認ください。\n"
                    .url."apply_list.php\n\n"
                    ."―――――――――\n"
                    ."SC戦略プロセス改善G：sctool@sample.com\n"
                    ."HP：".url."index.php\n\n"
                    ."━━━━━━□■□　ご返答内容　□■□━━━━━━\n"
                    ."From：".$sender."\n"
                    .$response."\n\n";
    
    $to = kanri_mail;
    list($err_message, $bool) = send_mail($to, $subject, $message_kanri);
    $to = mb_convert_encoding($_POST['poster'], "UTF-8", "HTML-ENTITIES");
    list($err_message, $bool) = send_mail($to, $subject, $message_user);
  }
?>
<form action = "apply_detail.php" method = "post" name = "post">
  <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  <input type = "hidden" name = "response" value = "<?php echo $response; ?>">
  <input type = "hidden" name = "apply_id" value = "<?php echo $apply_id; ?>">
  <input type = "hidden" name = "type" value = "<?php echo $type; ?>">
</form>
<?php
function update_inquiry_chat($pdo){
  $err_message = null;
  try{
    $message_no = uniqid();

    //チャットをDB格納
    $apply_id = mb_convert_encoding($_POST['apply_id'], "UTF-8", "HTML-ENTITIES");
    $response = mb_convert_encoding($_POST['response'], "UTF-8", "HTML-ENTITIES");

    if($response){
      $user_no = $_SESSION['USER_NO'];
      $send_time = new DateTime();
      $send_time -> setTimeZone( new DateTimeZone('Asia/Tokyo'));
      $send_time = $send_time -> format('Y-m-d H:i');

      $sql = "INSERT INTO inquiry_chat (message_no, inquiry_no, user_no, message, send_time) VALUES (?, ?, ?, ?, ?);";
      $stmt = $pdo -> prepare($sql);
      $params = array($message_no, $apply_id, $user_no, $response, $send_time);
      $stmt -> execute($params);

      return [$err_message, True];
    }
  } catch (Exception $e){
    $err_message = '[ERR-02]DBの更新に失敗しました。（' . $e->getMessage() . '）';
    return [$err_message, False];
  }
}
?>
<?php include('footer.php'); ?>