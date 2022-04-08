<?php include('header.php'); ?>
<?php
  $query = $_SERVER['QUERY_STRING'];
  $blog_no = str_replace('blog_no=', '', $query);

  //DB接続
  list($pdo, $err_message, $bool) = connection_db_2();
 
  try{
    $sql = "DELETE FROM blog WHERE blog_no = ?;";
    $stmt = $pdo -> prepare($sql);
    $params = array($blog_no);
    $stmt -> execute($params);
  } catch (Exception $e){
    $err_message = '[ERR-02]DBの更新に失敗しました。（' . $e->getMessage() . '）';
  }
?>
<form action = "blog_list.php" method = "post" name = "post">
  <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
</form>
<?php include('footer.php'); ?>