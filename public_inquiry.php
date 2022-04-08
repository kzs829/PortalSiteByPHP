<?php include('header.php'); ?>
<?php
//1ページに表示する件数
define('max_view', 5);

//DBの接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  //問合せデータの取得
  list($inquiry_data, $pages, $now, $err_message, $bool) = get_inquiry($pdo);
}
?>
<main>
  <div class = "table" style = "padding: 10px;">
    <h2 class = "subheading">みんなのQ＆A</h2>
    <p>
      他の人がしたお問合せを閲覧できます。<br>
      各お問合せをクリックするとお問合せに対する返答を閲覧できます。<br><br>
    </p>
    <?php if(isset($err_message)){ ?>
      <p class = "err_message"><?php echo $err_message; ?></p>
    <?php } ?>
    <?php if(isset($update_message)){ ?>
      <p class = "err_message"><?php echo $update_message; ?></p>
    <?php } ?>
    <div class = "search-area">
      <p>ステータスで絞り込む</p>
      <form action = "public_inquiry.php" method = "get" style="display:inline;">
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
          <option class = "select-item">対応済</optionalue>
          <option class = "select-item">対応中</optione>
        </select>
        <input type = "submit" value = "絞り込む" class = "button" id = "button-filter" style = "margin-top:0;">
      </form>
        <input type = "button" value = "すべて表示" onclick = "location.href='public_inquiry.php'" class = "button" id = "button-allview" style = "margin-top:0;">
    </div>
    <?php if(empty($inquiry_data)){ ?>
      <p>お問合せはまだありません。</p>
    <?php }else{ ?>
      <div class = "inquiry-list">
        <?php foreach($inquiry_data as $row){ ?>
          <div class = "inquiry-card">
            <p class = "inquiry-card-content" style = "margin-left:20px;"><b><?php echo $row['subject']; ?></b></p>
            <p class = "inquiry-card-content" style = "margin-left:20px; margin-right:20px; float:right;">
              <span class = "datetime" style = "font-size:14px;">受付日<?php echo substr($row['create_time'], 0, 10); ?></span>
              <span class = "label" title = "<?php echo $row['status']; ?>" style = "display:block; text-align:center;"><?php echo $row['status']; ?></span>
            </p>
            <div style = "clear:both;"></div>
            <input type = "hidden" class = "inquiry_no" value = "<?php echo $row['inquiry_no']; ?>">
          </div>
          <form action = "public_inquiry_chat.php" method = "post" id = "<?php echo $row['inquiry_no']; ?>">
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
</main>
<?php
//お問合せの取得
function get_inquiry($pdo){
  $err_message = null;

  try{
    if(isset($_GET['status'])){
      $status = $_GET['status'];
      $count = $pdo -> prepare("SELECT COUNT(*) AS count FROM inquiry WHERE status = :status AND publishing_setting = '公開' AND deleted_flg = 0");
      $count -> bindValue(":status", $status, PDO::PARAM_STR);
      $count -> execute();
    }else{
      $count = $pdo -> query("SELECT COUNT(*) AS count FROM inquiry WHERE publishing_setting = '公開' AND deleted_flg = 0");
    }

    //必要なページ数を取得      
    if($count){
      $total_count = $count -> fetch(PDO::FETCH_ASSOC);
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
        WHERE status = :status
        AND publishing_setting = '公開'
        AND deleted_flg = 0
        ORDER BY create_time DESC
        OFFSET :start ROWS 
        FETCH NEXT :max ROWS ONLY;  
        ";
        $result = $pdo -> prepare($tsql);
        $result -> bindValue(":status", $status, PDO::PARAM_STR);
      }else{
        $tsql = "
        SELECT * FROM inquiry
        WHERE publishing_setting = '公開'
        AND deleted_flg = 0
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
?>
<script type="text/javascript">
  $('.inquiry-card').click(function(){
    var inquiry_no = $(this).children('input').val();
    var f = document.getElementById(inquiry_no);
    f.submit();
  });
</script>
<?php include('footer.php'); ?>