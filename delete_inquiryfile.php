<?php include('header.php'); ?>
<?php
  $file_no = $_POST['file_no'];
  list($pdo, $err_message, $bool) = connection_db_2();
  try{
    $sql = "update inquiry_file set deleted_flg = 1 WHERE file_no = ?;";
    $stmt = $pdo -> prepare($sql);
    $params = array($file_no);
    $stmt -> execute($params);
  } catch (Exception $e){
    $err_message = '[ERR-02]DBの更新に失敗しました。';
  }
?>
<form action = "inquiry_chat.php" method = "post" name = "post">
  <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  <input type = "hidden" name = "inquiry_no" value = "<?php echo $_POST['inquiry_no'] ?>">
</form>
<?php include('footer.php'); ?>