<?php include('header.php'); ?>
<?php
//DBとの接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  //コラムをDBにinsert
  list($err_message, $bool) = insert_blog($pdo);
}

if($bool == FALSE){
  $alert = "<script type='text/javascript'>alert('コラムの投稿に失敗しました。');</script>";
  echo $alert;
?>
  <form action = "post_newinfo.php" method = "post" name = "post">
    <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  </form>
<?php
}else{
  $alert = "<script type='text/javascript'>alert('コラムを投稿しました。');</script>";
  echo $alert;
?>
  <form action = "post_blog.php" method = "post" name = "post"></form>
<?php } ?>
<?php
  function insert_blog($pdo){
    $err_message = null;
    try{
      if($_FILES['image']['name']){
        $filename = mb_convert_encoding($_FILES['image']['name'], "UTF-8", "HTML-ENTITIES");

        $count = $pdo -> prepare('SELECT COUNT(*) AS count FROM blog WHERE image like :filename');
        $count -> bindValue(":filename", "%".$filename, PDO::PARAM_STR);
        $count -> execute();
        $total_count = $count -> fetch(PDO::FETCH_ASSOC);
  
        if($total_count['count'] > 0){
          $filename = "(".$total_count['count'].")".$filename;
        }  
      }else{
        $filename = '';
      }

      //画像をフォルダに格納
      if($_FILES['image']['tmp_name']){
        $tempfile = $_FILES['image']['tmp_name'];
        $path = column_sumnail_path;
        move_uploaded_file($tempfile, $path.$filename);
      }
            
      $major_category = mb_convert_encoding($_POST['major_category'], "UTF-8", "HTML-ENTITIES");
      $subject = mb_convert_encoding($_POST['subject'], "UTF-8", "HTML-ENTITIES");
      $body = mb_convert_encoding($_POST['body'], "UTF-8", "HTML-ENTITIES");

      $post_time = new DateTime();
      $post_time -> setTimeZone( new DateTimeZone('Asia/Tokyo'));
      $post_time = $post_time -> format('Y-m-d H:i');

      $tsql = "INSERT INTO blog (major_category, subject, body, post_time, image) VALUES (?, ?, ?, ?, ?);";
      $stmt = $pdo->prepare($tsql);
      $params = array($major_category, $subject, $body, $post_time, $filename);
      $stmt->execute($params);

      return [$err_message, TRUE];
    } catch (Exception $e){
      $err_message = '[ERR-12]コラムの登録に失敗しました。';
      return [$err_message, FALSE];
    }
  }
?>
<?php include('footer.php'); ?>