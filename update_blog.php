<?php include('header.php'); ?>
<?php
  var_dump($_FILES['image']['tmp_name']);

  $query = $_SERVER['QUERY_STRING'];

  //DB接続
  list($pdo, $err_message, $bool) = connection_db_2();

  try{
    $subject = mb_convert_encoding($_POST['subject'], "UTF-8", "HTML-ENTITIES");
    $body = mb_convert_encoding($_POST['body'], "UTF-8", "HTML-ENTITIES");
    $blog_no = $_POST['blog_no'];

    if(isset($_FILES['image']['name'])){
      $filename = mb_convert_encoding($_FILES['image']['name'], "UTF-8", "HTML-ENTITIES");
      
      $count = $pdo -> prepare('SELECT COUNT(*) AS count FROM blog WHERE image like :filename');
      $count -> bindValue(":filename", "%".$filename, PDO::PARAM_STR);
      $count -> execute();
      $total_count = $count -> fetch(PDO::FETCH_ASSOC);

      if($total_count['count'] > 0){
        $filename = "(".$total_count['count'].")".$filename;
      }
    }

    //画像をフォルダに格納
    if($_FILES['image']['tmp_name']){
      $tempfile = $_FILES['image']['tmp_name'];
      $path = column_sumnail_path;
      move_uploaded_file($tempfile, $path.$filename);

      $sql = "UPDATE blog SET subject = ?, body = ?, image = ? WHERE blog_no = ?";
      $stmt = $pdo -> prepare($sql);
      $params = array($subject, $body, $filename, $blog_no);  
    }else{
      $sql = "UPDATE blog SET subject = ?, body = ? WHERE blog_no = ?";
      $stmt = $pdo -> prepare($sql);
      $params = array($subject, $body, $blog_no);  
    }

    $stmt -> execute($params);

  } catch (Exception $e){
    $err_message = '[ERR-02]DBの更新に失敗しました。（' . $e->getMessage() . '）';
  }
?>
<form action = "blog_page.php?<?php echo $query; ?>" method = "post" name = "post">
  <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
</form>
<?php include('footer.php'); ?>