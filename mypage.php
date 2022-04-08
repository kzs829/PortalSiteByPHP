<?php include('header.php'); ?>
<?php
if(!(isset($_SESSION['MAIL']))){
  $alert = "<script type='text/javascript'>alert('マイページの閲覧にはログインが必要です。');</script>";
  echo $alert;
?>
  <form action = "login.php" method = "post" name = "post"></form>
<?php
  exit;
}
?>
<?php
//1ページに表示する件数
define('max_view', 5);

//DBの接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  //問合せデータの取得
  list($inquiry_data, $pages, $now, $err_message, $bool) = get_my_inquiry($pdo);
}

if(isset($_POST['err_message'])){
  $err_message = $_POST['err_message'];
}
?>
<main>
  <div style = "padding: 10px;">
    <h2 class = "subheading">マイページ</h2>
    <?php if(isset($err_message)){ ?>
      <p class = "err_message"><?php echo $err_message; ?></p>
    <?php } ?>
    <h2 class = "subheading-mini">お問合せ</h2>
    <?php if(empty($inquiry_data)){ ?>
      <p>お問合せはまだありません。</p>
    <?php }else{ ?>
      <p style = "margin-bottom:10px;">
        ご利用者様の投稿したお問合せと、Ccに追加されたお問合せが表示されています。<br>
        各お問合せをクリックするとチャット画面へ遷移します。
      </p>
      <div class = "inquiry-list">
        <?php foreach($inquiry_data as $row){ ?>
          <div class = "inquiry-card">
            <p class = "inquiry-card-content" style = "margin-left:10px; width:150px;"><b><?php echo $row['name']; ?></b></p>
            <p class = "inquiry-card-content"><b>お問合せ番号：#<?php echo $row['inquiry_no']; ?></b></p>
            <p class = "inquiry-card-content" style = "margin-left:20px;"><b><?php echo $row['subject']; ?></b></p>
            <p class = "inquiry-card-content" style = "margin-left:20px; margin-right:20px; float:right;">
              <span class = "datetime" style = "font-size:14px;">受付日<?php echo substr($row['create_time'], 0, 10); ?></span>
              <span class = "label" title = "<?php echo $row['status']; ?>" style = "display:block; text-align:center;"><?php echo $row['status']; ?></span>
            </p>
            <div style = "clear:both;"></div>
            <input type = "hidden" class = "inquiry_no" value = "<?php echo $row['inquiry_no']; ?>">
          </div>
          <form action = "inquiry_chat.php" method = "post" id = "<?php echo $row['inquiry_no']; ?>">
            <input type = "hidden" name = "inquiry_no" value = "<?php echo $row['inquiry_no']; ?>">
          </form>
        <?php } ?>
      </div>      
      <div class = "paging">
      　<?php 
        for($n = 1; $n <= $pages; $n++){
          if($n == $now){
        ?>
            <span class = 'paging-item'><?php echo $now; ?></span>
        <?php
          }else{
            if(isset($_GET['page_id'])){
              $url_param = str_replace("page_id=" . $now, "", $_SERVER['QUERY_STRING']);
              if($url_param == ""){
                $url_param = "?page_id=" . $n;
              }else{
                $url_param = "?" . $url_param . "page_id=" . $n;
              }
            }else{
              if($_SERVER['QUERY_STRING'] == ""){
                $url_param = "?page_id=" . $n;
              }else{
                $url_param = "?" . $_SERVER['QUERY_STRING'] . "&page_id=" . $n;
              }
            }
            $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER["SCRIPT_NAME"] . $url_param;
        ?>
            <a class = 'paging-item' href = '<?php echo $url; ?>'><?php echo $n; ?></a>
        <?php
          }
        }
        ?>
      </div>
    <?php } ?>
    <h2 class = "subheading-mini">アカウント情報</h2>
    <div class = "user-info">
      <p>名前</p>
      <input readonly type = "text" name = "name" class = "textbox" value = "<?php echo $_SESSION['NAME']; ?>">
      <p>メールアドレス</p>
      <input readonly type = "email" name = "mail" class = "textbox" value = "<?php echo $_SESSION['MAIL']; ?>">
      <input type = "button" value = "アカウント情報変更" class = "button" onclick = "location.href='change_user_info.php'">
      <p><a href = "change_password.php">パスワード変更</a></p>
      <p><a href = "account_deletion.php" style = "color: red;">アカウント削除</a></p>
    </div>
  </div>
</main>
<?php
//お問合せの取得
function get_my_inquiry($pdo){
  $err_message = null;
  try{
    $count = $pdo -> prepare('SELECT COUNT(*) AS count FROM inquiry WHERE (user_no = :user_no OR cc LIKE :cc) AND deleted_flg = 0');
    $count -> bindValue(":user_no", $_SESSION['USER_NO'], PDO::PARAM_STR);
    $count -> bindValue(":cc", "%".$_SESSION['MAIL']."%", PDO::PARAM_STR);
    $count -> execute();
    $total_count = $count -> fetch(PDO::FETCH_ASSOC);

    //必要なページ数を取得      
    if($total_count){
      $pages = ceil($total_count['count'] / max_view);

      //現在のページ番号を取得
      if(!isset($_GET['page_id'])){
      $now = 1;
      }else{
      $now = $_GET['page_id'];
      }

      $tsql = "
      SELECT * FROM inquiry
      INNER JOIN user_info
      ON inquiry.user_no = user_info.user_no
      WHERE (inquiry.user_no = :user_no OR inquiry.cc LIKE :cc)
      AND inquiry.deleted_flg = 0
      ORDER BY create_time DESC
      OFFSET :start ROWS
      FETCH NEXT :max ROWS ONLY;
      ";

      $result = $pdo -> prepare($tsql);
      $result -> bindValue(":user_no", $_SESSION['USER_NO'], PDO::PARAM_INT);
      $result -> bindValue(":cc", "%".$_SESSION['MAIL']."%", PDO::PARAM_STR);

      if($now == 1){
        $result -> bindValue(":start", $now - 1, PDO::PARAM_INT);
        $result -> bindValue(":max", max_view, PDO::PARAM_INT);
      }else{
        $result -> bindValue(":start", ($now - 1) * max_view, PDO::PARAM_INT);
        $result -> bindValue(":max", max_view, PDO::PARAM_INT);
      }
      
      $result -> execute();
      $inquiry_data = $result -> fetchAll(PDO::FETCH_ASSOC);

      return [$inquiry_data, $pages, $now, $err_message, TRUE];
    }
  } catch (Exception $e){
    $err_message = '[ERR-03]問合せデータの取得に失敗しました。（' . $e->getMessage() . '）';
    return [$inquiry_data, $pages, $now, $err_message, FALSE];
  }
}
?>
<script type="text/javascript">
  $('.inquiry-card').click(function(){
    var inquiry_no = $(this).children('input').val();
    var f = document.getElementById(inquiry_no);
    f.submit();
  });
</script>
<?php include('footer.php'); ?>