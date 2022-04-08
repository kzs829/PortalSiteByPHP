<?php include('header.php'); ?>
<?php
  $apply_id = $_POST['apply_id'];
  $mail = $_POST['mail'];
  $name = $_POST['name'];

  //DB接続
  list($pdo, $err_message, $bool) = connection_db_2();
  if($bool){
    //申込ステータス更新
    list($err_message, $bool) = update_apply($pdo, $apply_id);
  }
  if($bool){
    $to = $mail;
    $subject = subject_withdraw_apply;
    $message = "※このメールはシステムからの自動配信です。\n"
            ."※本メールアドレス(postmaster@sample.co.jp)は送信専用のため返信はできません。\n\n"
            .$name."様\n\n"
            ."申込ID【".$apply_id."】の申込が取り下げされました。\n\n"
            ."申込内容は申込一覧画面よりご覧いただけます。\n"
            ."申込一覧画面：".url."apply_list.php\n\n"
            ."―――――――――\n"
            ."SC戦略プロセス改善G：sctool@sample.com\n"
            ."HP：".url."index.php\n\n";
    list($err_message, $bool) = send_mail($to, $subject, $message);  
    $alert = "<script type='text/javascript'>alert('申込の取り下げが完了しました。');</script>";
    echo $alert;
  }else{
    $alert = "<script type='text/javascript'>alert('申込の取り下げに失敗しました。');</script>";
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
  try{
    $sql = "UPDATE application SET status = '取り下げ' WHERE apply_id = ?";
    $stmt = $pdo -> prepare($sql);
    $params = array($apply_id);
    $stmt -> execute($params);

    return [$err_message, TRUE];
  } catch (Exception $e){
    $err_message = '[ERR-02]DBの更新に失敗しました。';
    return [$err_message, FALSE];
  }
}
?>
<?php include('footer.php'); ?>