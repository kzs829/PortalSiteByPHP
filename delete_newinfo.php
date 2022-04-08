<?php include('header.php'); ?>
<?php
  $query = $_SERVER['QUERY_STRING'];
  $newinfo_no = str_replace('newinfo_no=', '', $query);

  //DB接続
  list($pdo, $err_message, $bool) = connection_db_2();
 
  try{
    $sql = "DELETE FROM new_info WHERE newinfo_no = ?;";
    $stmt = $pdo -> prepare($sql);
    $params = array($newinfo_no);
    //SQLを実行
    $stmt -> execute($params);
  } catch (Exception $e){
    $err_message = '[ERR-02]DBの更新に失敗しました。（' . $e->getMessage() . '）';
  }
?>
<form action = "new_info.php" method = "post" name = "post">
  <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
</form>
<?php include('footer.php'); ?>