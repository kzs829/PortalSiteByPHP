<?php include('header.php'); ?>
<?php
  $inquiry_no = mb_convert_encoding($_POST['inquiry_no'], "UTF-8", "HTML-ENTITIES");
  $inquiry_subject = mb_convert_encoding($_POST['subject'], "UTF-8", "HTML-ENTITIES");
  $body = mb_convert_encoding($_POST['body'], "UTF-8", "HTML-ENTITIES");

  //DB接続
  list($pdo, $err_message, $bool) = connection_db_2();

  if($bool){
    //お問い合わせ更新
    list($err_message, $bool) = update_inquiry($pdo);
  }

  if($bool){
    //メール送信
    $to = kanri_mail;
    $subject = subject_inquiry_update;
    $message = "※このメールはシステムからの自動配信です。\n"
               ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
               ."管理者様\n\n"
               ."お問合せ番号#".$inquiry_no."のお問合せに以下の更新がありました。\n\n"
               ."返信は管理画面のお問合せチャットよりお願いいたします。\n"
               .url."inquiry_management.php\n\n"
               ."―――――――――\n"
               ."SC戦略プロセス改善G：sctool@sample.com\n"
               ."HP：".url."index.php\n\n"
               ."━━━━━━□■□　お問合せ内容　□■□━━━━━━\n"
               ."件名：".$inquiry_subject."\n"
               ."お問合せ内容：".$body;
    list($err_message, $bool) = send_mail($to, $subject, $message);
  }
?>
<form action = "inquiry_chat.php" method = "post" name = "post">
  <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  <input type = "hidden" name = "inquiry_no" value = "<?php echo $inquiry_no; ?>">
</form>
<?php
function update_inquiry($pdo){
  $err_message = null;
  try{
    $inquiry_no = mb_convert_encoding($_POST['inquiry_no'], "UTF-8", "HTML-ENTITIES");
    $subject = mb_convert_encoding($_POST['subject'], "UTF-8", "HTML-ENTITIES");
    $body = mb_convert_encoding($_POST['body'], "UTF-8", "HTML-ENTITIES");

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

    $sql = "SELECT * from inquiry WHERE inquiry_no = ?";
    $stmt = $pdo -> prepare($sql);
    $params = array($inquiry_no);
    $stmt -> execute($params);
    $inquiry = $stmt -> fetch(PDO::FETCH_ASSOC);

    if($inquiry['body'] == $body && $inquiry['subject'] == $subject){
      return [$err_message, FALSE];
    }

    $sql = "UPDATE inquiry SET subject = ?, body = ? WHERE inquiry_no = ?";
    $stmt = $pdo -> prepare($sql);
    $params = array($subject, $body, $inquiry_no);
    $stmt -> execute($params);

    return [$err_message, TRUE];
  } catch (Exception $e){
    $err_message = '[ERR-02]DBの更新に失敗しました。';
    return [$err_message, FALSE];
  }
}
?>
<?php include('footer.php'); ?>