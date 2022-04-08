<?php include('header.php'); ?>
<?php
  $apply_id = mb_convert_encoding($_POST['apply_id'], "UTF-8", "HTML-ENTITIES");
  $poster = mb_convert_encoding($_POST['poster'], "UTF-8", "HTML-ENTITIES");
  $chat_message = mb_convert_encoding($_POST['message'], "UTF-8", "HTML-ENTITIES");
  $type = mb_convert_encoding($_POST['type'], "UTF-8", "HTML-ENTITIES");
  $sender = $_SESSION['NAME'];

  //DB接続
  list($pdo, $err_message, $bool) = connection_db_2();

  if($bool){
    //メッセージ更新
    list($err_message, $bool) = update_inquiry_message($pdo);
  }

  if($bool){
    //お問合せ返答更新通知メールの送信
    $subject = subject_reply_apply_inquiry_update;
    $message_kanri = "※このメールはシステムからの自動配信です。\n"
                     ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
                     ."申込番号#".$apply_id."の問合せの返答が更新されました。\n"
                     ."各種申込の申込一覧よりご確認ください。\n"
                     .url."apply_list.php\n\n"
                     ."―――――――――\n"
                     ."SC戦略プロセス改善G：sctool@sample.com\n"
                     ."HP：".url."index.php\n\n"
                     ."━━━━━━□■□　ご返答内容　□■□━━━━━━\n"
                     ."From：".$sender."\n"
                     .$chat_message;
    $message_user = "※このメールはシステムからの自動配信です。\n"
                    ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
                    ."申込番号#".$apply_id."の問合せの返答が更新されました。\n"
                    ."各種申込の申込一覧よりご確認ください。\n"
                    .url."apply_list.php\n\n"
                    ."―――――――――\n"
                    ."SC戦略プロセス改善G：sctool@sample.com\n"
                    ."HP：".url."index.php\n\n"
                    ."━━━━━━□■□　ご返答内容　□■□━━━━━━\n"
                    ."From：".$sender."\n"
                    .$chat_message;
    $to = kanri_mail;
    list($err_message, $bool) = send_mail($to, $subject, $message_kanri);
    $to = $poster;
    list($err_message, $bool) = send_mail($to, $subject, $message_user);
  }
?>
<form action = "apply_detail.php" method = "post" name = "post">
  <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  <input type = "hidden" name = "apply_id" value = "<?php echo $apply_id; ?>">
  <input type = "hidden" name = "type" value = "<?php echo $type; ?>">
</form>
<?php
function update_inquiry_message($pdo){
  $err_message = null;
  try{
    $message_no = mb_convert_encoding($_POST['message_no'], "UTF-8", "HTML-ENTITIES");
    $message = mb_convert_encoding($_POST['message'], "UTF-8", "HTML-ENTITIES");

    $sql = "SELECT * from inquiry_chat WHERE message_no = ?";
    $stmt = $pdo -> prepare($sql);
    $params = array($message_no);
    $stmt -> execute($params);
    $inquiry_chat = $stmt -> fetch(PDO::FETCH_ASSOC);

    if($inquiry_chat['message'] == $message){
      return [$err_message, FALSE];
    }

    $sql = "UPDATE inquiry_chat SET message = ? WHERE message_no = ?";
    $stmt = $pdo -> prepare($sql);
    $params = array($message, $message_no);
    $stmt -> execute($params);

    return [$err_message, TRUE];
  } catch (Exception $e){
    $err_message = '[ERR-02]DBの更新に失敗しました。';
    return [$err_message, FALSE];
  }
}
?>
<?php include('footer.php'); ?>