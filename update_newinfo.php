<?php include('header.php'); ?>
<?php
  $query = $_SERVER['QUERY_STRING'];

  //DB接続
  list($pdo, $err_message, $bool) = connection_db_2();

  try{
    $subject = $_POST['subject'];
    $newinfo_no = $_POST['newinfo_no'];
    $body = $_POST['body'];

    $sql = "UPDATE new_info SET subject = ?, body = ? WHERE newinfo_no = ?";
    $stmt = $pdo -> prepare($sql);
    $params = array($subject, $body, $newinfo_no);
    //SQLを実行
    $stmt -> execute($params);
  } catch (Exception $e){
    $err_message = '[ERR-02]DBの更新に失敗しました。（' . $e->getMessage() . '）';
  }
?>
<form action = "newinfo_page.php?<?php echo $query; ?>" method = "post" name = "post">
  <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
</form>
<?php include('footer.php'); ?>