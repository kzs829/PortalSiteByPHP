<?php include('header.php'); ?>
<?php
//DBとの接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  //お問合せをDBにinsert
  list($err_message, $bool) = insert_newinfo($pdo);
}

if($bool == FALSE){
  $alert = "<script type='text/javascript'>alert('新着情報の投稿に失敗しました。');</script>";
  echo $alert;
?>
  <form action = "post_newinfo.php" method = "post" name = "post">
    <input type = "hidden" name = "err_message" value = "<?php echo $err_message; ?>">
  </form>
<?php
}else{
  $alert = "<script type='text/javascript'>alert('新着情報を投稿しました。');</script>";
  echo $alert;
?>
  <form action = "post_newinfo.php" method = "post" name = "post"></form>
<?php } ?>

<?php
  function insert_newinfo($pdo){
    $err_message = null;
    try{    
      //inquiry.htmlの値を取得
      $major_category = $_POST['major_category'];
      $subject = $_POST['subject'];
      $body = $_POST['body'];

      $post_time = new DateTime();
      $post_time -> setTimeZone( new DateTimeZone('Asia/Tokyo'));
      $post_time = $post_time -> format('Y-m-d H:i');

      // INSERT文を変数に格納。
      $tsql = "INSERT INTO new_info (major_category, subject, body, post_time) VALUES (?, ?, ?, ?);";

      //挿入する値は空のまま、SQL実行の準備をする
      $stmt = $pdo->prepare($tsql);
      //挿入する値を配列に格納する
      $params = array($major_category, $subject, $body, $post_time);
      //SQLを実行
      $stmt->execute($params);

      return [$err_message, TRUE];
    } catch (Exception $e){
      $err_message = '[ERR-09]新着情報の登録に失敗しました。（' . $e->getMessage() . '）';
      return [$err_message, FALSE];
    }
  }
?>
<?php include('footer.php'); ?>