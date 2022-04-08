<?php include('header.php'); ?>
<?php
if(!(isset($_SESSION['MAIL']))){
  $alert = "<script type='text/javascript'>alert('管理画面の閲覧にはログインが必要です。');</script>";
  echo $alert;
?>
  <form action = "login.php" method = "post" name = "post"></form>
<?php
  exit;
}
?>
<?php
$update_message = null;
if(isset($_POST['err_message'])){
  $update_message = $_POST['err_message'];
}
if($update_message){
  $alert = "<script type='text/javascript'>alert('データベースの更新に失敗しました。');</script>";
  echo $alert;
}

//1ページに表示する件数
define('max_view', 5);

//DBの接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  //問合せデータの取得
  list($inquiry_data, $pages, $now, $err_message, $bool) = get_inquiry($pdo);
  //管理者データの取得
  $admin_user = get_admin($pdo);
}
?>
<main>
  <div class = "table" style = "padding: 10px;">
    <h2 class = "subheading">管理画面</h2>
    <h2 class = "subheading-mini">お問合せ管理</h2>
    <?php if(isset($err_message)){ ?>
      <p class = "err_message"><?php echo $err_message; ?></p>
    <?php } ?>
    <?php if(isset($update_message)){ ?>
      <p class = "err_message"><?php echo $update_message; ?></p>
    <?php } ?>
    <div class = "search-area">
      <p>ステータスで絞り込みができます。</p>
      <form action = "inquiry_management.php" method = "get" style="display:inline;">
        <select name = "status" class = "select-box" id = "search-box">
          <option hidden class = "select-item">
            <?php
            if(isset($_GET['status'])){
              echo $_GET['status'];
            }else{
              echo "選択してください";
            }
            ?>
          </option>
        　<option class = "select-item">未対応</option>
          <option class = "select-item">対応済</option>
          <option class = "select-item">対応中</option>
        </select>
        <input type = "submit" value = "絞り込む" class = "button" id = "button-filter">
      </form>
      <input type = "button" value = "すべて表示" onclick = "location.href='inquiry_management.php'" class = "button" id = "button-allview">
    </div>
    <?php if(empty($inquiry_data)){ ?>
      <p>お問合せはまだありません。</p>
    <?php }else{ ?>
      <div class = "inquiry-list">
        <?php foreach($inquiry_data as $row){ ?>
          <div class = "inquiry-card">
            <p class = "inquiry-card-content" style = "margin-left:10px; width:150px;"><b><?php echo $row['name']; ?><?php if($row['deleted_flg'] == 1){ echo '（削除されたユーザー）'; } ?></b></p>
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
  </div>  
  <div class = "admin-register" style = "padding: 10px;">
    <h3 class = "subheading-mini">アカウント管理</h3>
    <p style = "margin-bottom:10px;">管理者</p>
    <?php foreach($admin_user as $row){ ?>
      <p class = "list"><?php echo $row['name'] . '／' . $row['mail']; ?></p>
    <?php } ?>
    <button type = 'button' class = "button" onclick="location.href='pre_register.php'">管理者登録</button>
    <p class = "dots" style = "margin-bottom:10px; width:238px;"></p>
    <p>ユーザー</p>
    <button type = 'button' class = "button" onclick="location.href='user_list.php'">ユーザー一覧</button>
  </div>
  <div class = "other" style = "padding: 10px;">
    <h3 class = "subheading-mini">その他管理</h3>
    <button type = 'button' class = "button" onclick="location.href='post_newinfo.php'">新着情報投稿</button>
    <button type = 'button' class = "button" onclick="location.href='post_blog.php'">コラム投稿</button>
  </div>
</main>
<?php
//お問合せの取得
function get_inquiry($pdo){
  $err_message = null;

  try{
    if(isset($_GET['status'])){
      $status = $_GET['status'];
      $count = $pdo -> prepare('SELECT COUNT(*) AS count FROM inquiry WHERE status = :status AND deleted_flg = 0');
      $count -> bindValue(":status", $status, PDO::PARAM_STR);
      $count -> execute();
    }else{
      $count = $pdo -> query('SELECT COUNT(*) AS count FROM inquiry WHERE deleted_flg = 0');
    }
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

      if(isset($_GET['status'])){
        $tsql = "
        SELECT * FROM inquiry
	    	INNER JOIN user_info
        ON inquiry.user_no = user_info.user_no
        WHERE status = :status
        AND inquiry.deleted_flg = 0
	    	ORDER BY create_time DESC
        OFFSET :start ROWS 
        FETCH NEXT :max ROWS ONLY;  
        ";
        $result = $pdo -> prepare($tsql);
        $result -> bindValue(":status", $status, PDO::PARAM_STR);
      }else{
        $tsql = "
        SELECT * FROM inquiry
	    	INNER JOIN user_info
        ON inquiry.user_no = user_info.user_no
        WHERE inquiry.deleted_flg = 0
        ORDER BY create_time DESC
        OFFSET :start ROWS 
        FETCH NEXT :max ROWS ONLY;  
        ";
        $result = $pdo -> prepare($tsql);
      }
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

//管理者情報の取得
function get_admin($pdo){
  $result = $pdo -> query('SELECT * FROM user_info WHERE admin_flg = 1 AND deleted_flg = 0');
  $admin_user = $result -> fetchAll(PDO::FETCH_ASSOC);

  return $admin_user;
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