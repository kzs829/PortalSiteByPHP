<?php include('header.php'); ?>
<?php
  $inquiry_no = mb_convert_encoding($_POST['inquiry_no'], "UTF-8", "HTML-ENTITIES");
  $poster = mb_convert_encoding($_POST['poster'], "UTF-8", "HTML-ENTITIES");
  $chat_message = mb_convert_encoding($_POST['message'], "UTF-8", "HTML-ENTITIES");
  $cc = mb_convert_encoding($_POST['cc'], "UTF-8", "HTML-ENTITIES");
  $sender = $_SESSION['NAME'];

  $cc_list =  preg_split('/,/', $cc);

  //DB接続
  list($pdo, $err_message, $bool) = connection_db_2();

  if($bool){
    //メッセージ更新
    list($err_message, $bool) = update_inquiry_message($pdo);
  }

  if($bool){
    //お問合せ返答更新通知メールの送信
    $subject = subject_reply_inquiry_update;
    $message_kanri = "※このメールはシステムからの自動配信です。\n"
                     ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
                     ."お問合せ番号#".$inquiry_no."の返答が更新されました。\n"
                     ."管理画面のお問合せチャットよりご確認ください。\n"
                     .url."inquiry_management.php\n\n"
                     ."―――――――――\n"
                     ."SC戦略プロセス改善G：sctool@sample.com\n"
                     ."HP：".url."index.php\n\n"
                     ."━━━━━━□■□　ご返答内容　□■□━━━━━━\n"
                     ."From：".$sender."\n"
                     .$chat_message;
    $message_user = "※このメールはシステムからの自動配信です。\n"
                    ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
                    ."お問合せ番号#".$inquiry_no."の返答が更新されました。\n"
                    ."マイページのお問合せチャットよりご確認ください。\n"
                    .url."mypage.php\n\n"
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
    foreach($cc_list as $to){
      list($err_message, $bool) = send_mail($to, $subject, $message_user);
    }
  }
?>
<form action = "inquiry_chat.php" method = "post" name = "post">
  <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  <input type = "hidden" name = "inquiry_no" value = "<?php echo $inquiry_no; ?>">
</form>
<?php
function update_inquiry_message($pdo){
  $err_message = null;
  try{
    $message_no = mb_convert_encoding($_POST['message_no'], "UTF-8", "HTML-ENTITIES");
    $message = mb_convert_encoding($_POST['message'], "UTF-8", "HTML-ENTITIES");

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
        $params = array($message_no, $filename, $path.$filename);
        $stmt->execute($params);
      }
    }

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