<?php include('header.php'); ?>
<?php
  $apply_id = $_POST['apply_id'];
  $status = $_POST['status'];
  $mail = $_POST['mail'];
  $name = $_POST['name'];

  //DB接続
  list($pdo, $err_message, $bool) = connection_db_2();

  if($bool){
    //申込ステータス更新
    list($err_message, $bool) = update_apply($pdo, $apply_id);
  }

  if($bool){
    //メール送信
    if($status == '対応中'){
      $to = $mail;
      $subject = subject_accept_apply;
      $message = "※このメールはシステムからの自動配信です。\n"
              ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
              .$name."様\n\n"
              ."申込ID【".$apply_id."】の申込を担当者にて受付いたしました。\n"
              ."対応完了時に再度ご連絡いたしますので、今しばらくお待ちくださいませ。\n\n"
              ."申込内容、対応状況などは申込一覧画面よりご覧いただけます。\n"
              ."申込一覧画面：".url."apply_list.php\n\n"
              ."―――――――――\n"
              ."SC戦略プロセス改善G：sctool@sample.com\n"
              ."HP：".url."index.php\n\n";
      list($err_message, $bool) = send_mail($to, $subject, $message);  
    }elseif($status == '対応済'){
      $to = $mail;
      $subject = subject_complete_apply_work;
      $message = "※このメールはシステムからの自動配信です。\n"
              ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
              .$name."様\n\n"
              ."申込ID【".$apply_id."】の申込について、作業が完了いたしましたのでお知らせいたします。\n\n"
              ."申込内容は申込一覧画面よりご覧いただけます。\n"
              ."申込一覧画面：".url."apply_list.php\n\n"
              ."―――――――――\n"
              ."SC戦略プロセス改善G：sctool@sample.com\n"
              ."HP：".url."index.php\n\n";
      list($err_message, $bool) = send_mail($to, $subject, $message);  
    }
    $alert = "<script type='text/javascript'>alert('ステータスの更新が完了しました。');</script>";
    echo $alert;
  }else{
    $alert = "<script type='text/javascript'>alert('ステータスの更新に失敗しました。');</script>";
    echo $alert;
  }
?>
<form action = "apply_detail.php" method = "post" name = "post">
  <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  <input type = "hidden" name = "apply_id" value = "<?php echo $apply_id; ?>">
  <input type = "hidden" name = "type" value = "<?php echo $_POST['type']; ?>"> 
</form>
<?php
function update_apply($pdo, $apply_id){
  $err_message = null;
  $status = $_POST['status'];
  $create_time = new DateTime();
  $create_time -> setTimeZone( new DateTimeZone('Asia/Tokyo'));
  $create_time = $create_time -> format('Y-m-d H:i');
  try{
    if($status == '対応中'){
      $sql = "UPDATE application SET status = '対応中', reseption_date = ? WHERE apply_id = ?";
    }elseif($status == '対応済'){
      $sql = "UPDATE application SET status = '対応済', complete_date = ? WHERE apply_id = ?";
    }
    $stmt = $pdo -> prepare($sql);
    $params = array($create_time, $apply_id);
    $stmt -> execute($params);

    return [$err_message, TRUE];
  } catch (Exception $e){
    $err_message = '[ERR-02]DBの更新に失敗しました。';
    return [$err_message, FALSE];
  }
}
?>
<?php include('footer.php'); ?>