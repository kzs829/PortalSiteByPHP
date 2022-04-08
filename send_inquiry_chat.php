<?php include('header.php'); ?>
<?php
  $response = mb_convert_encoding($_POST['response'], "UTF-8", "HTML-ENTITIES");
  $inquiry_no = mb_convert_encoding($_POST['inquiry_no'], "UTF-8", "HTML-ENTITIES");
  $cc = mb_convert_encoding($_POST['cc'], "UTF-8", "HTML-ENTITIES");
  $sender = $_SESSION['NAME'];

  $cc_list =  preg_split('/,/', $cc);

  //DBとの接続
  list($pdo, $err_message, $bool) = connection_db_2();
  
  if($bool){
    //お問合せチャットDBの更新
    list($err_message, $bool) = update_inquiry_chat($pdo);
  }

  if($bool && $response){
    //お問合せ返答通知メールの送信
    $subject = subject_reply_inquiry;
    $message_kanri =  "※このメールはシステムからの自動配信です。\n"
                      ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
                      ."お問合せ番号#".$inquiry_no."に以下の返答を受け付けました。\n"
                      ."管理画面のお問合せチャットよりご確認ください。\n"
                      .url."inquiry_management.php\n\n"
                      ."―――――――――\n"
                      ."SC戦略プロセス改善G：sctool@sample.com\n"
                      ."HP：".url."index.php\n\n"
                      ."━━━━━━□■□　ご返答内容　□■□━━━━━━\n"
                      ."From：".$sender."\n"
                      .$response."\n\n";
    $message_user = "※このメールはシステムからの自動配信です。\n"
                    ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
                    ."お問合せ番号#".$inquiry_no."に以下の返答を受け付けました。\n"
                    ."マイページのお問合せチャットよりご確認ください。\n"
                    .url."mypage.php\n\n"
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
    foreach($cc_list as $to){
      list($err_message, $bool) = send_mail($to, $subject, $message_user);
    }
  }
?>
<form action = "inquiry_chat.php" method = "post" name = "post">
  <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  <input type = "hidden" name = "response" value = "<?php echo $response; ?>">
  <input type = "hidden" name = "inquiry_no" value = "<?php echo $inquiry_no; ?>">
</form>
<?php
function update_inquiry_chat($pdo){
  $err_message = null;
  try{
    $message_no = uniqid();
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

    //チャットをDB格納
    $status = mb_convert_encoding($_POST['status'], "UTF-8", "HTML-ENTITIES");
    $inquiry_no = mb_convert_encoding($_POST['inquiry_no'], "UTF-8", "HTML-ENTITIES");
    $response = mb_convert_encoding($_POST['response'], "UTF-8", "HTML-ENTITIES");

    $sql = "UPDATE inquiry SET status = ? WHERE inquiry_no = ?";
    $stmt = $pdo -> prepare($sql);
    $params = array($status, $inquiry_no);
    $stmt -> execute($params);

    if($response){
      $user_no = $_SESSION['USER_NO'];
      $send_time = new DateTime();
      $send_time -> setTimeZone( new DateTimeZone('Asia/Tokyo'));
      $send_time = $send_time -> format('Y-m-d H:i');

      $sql = "INSERT INTO inquiry_chat (message_no, inquiry_no, user_no, message, send_time) VALUES (?, ?, ?, ?, ?);";
      $stmt = $pdo -> prepare($sql);
      $params = array($message_no, $inquiry_no, $user_no, $response, $send_time);
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