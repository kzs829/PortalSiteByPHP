<?php include('header.php'); ?>
<?php
  $query = $_SERVER['QUERY_STRING'];
  $inquiry_no = str_replace('inquiry_no=', '', $query);
  list($pdo, $err_message, $bool) = connection_db_2();
  try{
    $sql = "update inquiry set deleted_flg = 1 WHERE inquiry_no = ?;";
    $stmt = $pdo -> prepare($sql);
    $params = array($inquiry_no);
    //SQLを実行
    $stmt -> execute($params);
  } catch (Exception $e){
    $err_message = '[ERR-02]DBの更新に失敗しました。（' . $e->getMessage() . '）';
  }
?>
<form action = "inquiry_management.php" method = "post" name = "post">
  <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
</form>
<?php include('footer.php'); ?>