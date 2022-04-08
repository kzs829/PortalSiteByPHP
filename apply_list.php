<?php include('header.php'); ?>
<?php
//1ページに表示する件数
define('max_view', 10);

//DBの接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  list($err_message, $no_complete, $complete, $ontheway, $bool) = count_apply($pdo);
}

if($bool){
  //問合せデータの取得
  list($apply_data, $pages, $now, $err_message, $bool) = get_apply_info($pdo);
}  

$now_date = new DateTime();
$now_date -> setTimeZone( new DateTimeZone('Asia/Tokyo'));
$now_date = $now_date -> format('Y-m-d');
?>
<main>
  <div style = "padding: 10px;">
    <h2 class = "subheading">申込一覧</h2>
    <?php if(isset($err_message)){ ?>
      <p class = "err_message"><?php echo $err_message; ?></p>
    <?php } ?>
    <p>未対応：<?php echo $no_complete['count']; ?>件　対応中：<?php echo $ontheway['count']; ?>件　対応済：<?php echo $complete['count']; ?>件</p>
    <div class = "search-area" style = "margin-bottom:20px;">
      <p>申込IDかステータスで絞り込みができます</p>
      <form action = "apply_list.php" method = "get" id = "search_provided" style="display:inline;">
        <?php if(isset($_GET['apply_id'])){ ?>
          <input type = "text" placeholder = "申込ID" name = "apply_id" id = "apply_id_form" class = "textbox" value = "<?php echo $_GET['apply_id']; ?>">
        <?php }else{ ?>
          <input type = "text" placeholder = "申込ID" name = "apply_id" id = "apply_id_form" class = "textbox">
        <?php } ?>
        <select name = "status" class = "select-box" id = "status_form">
          <?php if(isset($_GET['status'])){ ?>
            <option hidden value = "<?php echo $_GET['status']; ?>" class = "select-item"><?php echo $_GET['status']; ?></option>
          <?php }else{ ?>
           <option hidden value = "" class = "select-item">選択してください</option>
          <?php } ?>
        　<option value = "未対応" class = "select-item">未対応</option>
          <option value = "対応中" class = "select-item">対応中</option>
          <option value = "対応済" class = "select-item">対応済</option>
        </select>
        <input type = "button" value = "絞り込む" class = "button" id = "button-filter" onclick = "modify_query();">
      </form>
      <input type = "button" value = "すべて表示" onclick = "location.href='apply_list.php'" class = "button" id = "button-allview">
      <p>納期</p>
      <input type = "button" value = "1週間以内" onclick = "location.href='apply_list.php?delivery=week'" class = "mini_button">
      <input type = "button" value = "超過" onclick = "location.href='apply_list.php?delivery=over'" class = "mini_button">
    </div>
    <?php if(empty($apply_data)){ ?>
      <p>お申込はまだありません。</p>
    <?php }else{ ?>
        <div class = "apply-list">
        <?php foreach($apply_data as $row){ ?>
          <div class = "inquiry-card">
            <span class = "inquiry-card-content" style = "margin-left:10px; width:150px;"><b><?php echo $row['name']; ?></b></span>
            <span class = "inquiry-card-content" style = "display:inline-box; width:180px;"><b><?php echo $row['type']; ?></b></span>
            <span class = "inquiry-card-content"><b>申込ID/<?php echo $row['apply_id']; ?></b></span>
            <span style = "display:inline-block; float:right; width:140px;">
              <p class = "datetime" style = "font-size:14px;">申請日<?php echo substr($row['apply_date'], 0, 10); ?></p>
              <p class = "datetime" style = "font-size:14px; <?php if($row['delivery_date'] != '1900-01-01 00:00:00.000' && $row['delivery_date'] < $now_date && $row['status'] != '対応済'){echo 'color:red;';} ?>">希望納期<?php if($row['delivery_date'] == '1900-01-01 00:00:00.000'){ echo 'なし'; }else{ echo substr($row['delivery_date'], 0, 10); } ?></p>
            </span>
            <span class = "label" title = "<?php echo $row['status']; ?>" style = "margin:0 20px; margin-top:7px; float:right; display:block; text-align:center;"><?php echo $row['status']; ?></span>
            <div style = "clear:both;"></div>
            <input type = "hidden" class = "apply_id" value = "<?php echo $row['apply_id']; ?>">
          </div>
          <form action = "apply_detail.php" method = "post" id = "<?php echo $row['apply_id']; ?>">
            <input type = "hidden" name = "apply_id" value = "<?php echo $row['apply_id']; ?>">
            <input type = "hidden" name = "type" value = "<?php echo $row['type']; ?>">
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
function count_apply($pdo){
  $err_message = null;
  try{
    $tsql = "SELECT COUNT(*) AS count FROM application WHERE status = '未対応';";
    $result = $pdo -> query($tsql);
    $no_complete = $result -> fetch(PDO::FETCH_ASSOC);
    $tsql = "SELECT COUNT(*) AS count FROM application WHERE status = '対応済';";
    $result = $pdo -> query($tsql);
    $complete = $result -> fetch(PDO::FETCH_ASSOC);
    $tsql = "SELECT COUNT(*) AS count FROM application WHERE status = '対応中';";
    $result = $pdo -> query($tsql);
    $ontheway = $result -> fetch(PDO::FETCH_ASSOC);

    return [$err_message, $no_complete, $complete, $ontheway, TRUE];
  } catch (Exception $e){
    $err_message = '[ERR-03]問合せデータの取得に失敗しました。（' . $e->getMessage() . '）';
    return [$err_message, $no_complete, $complete, $ontheway, FALSE];
  }
}

function get_apply_info($pdo){
  $err_message = null;
  try{
    if(isset($_GET['apply_id']) && isset($_GET['status'])){
      $apply_id = $_GET['apply_id'];
      $status = $_GET['status'];
      $count = $pdo -> prepare('SELECT COUNT(*) AS count FROM application WHERE status = :status AND apply_id = :apply_id');
      $count -> bindValue(":status", $status, PDO::PARAM_STR);
      $count -> bindValue(":apply_id", "%".$apply_id."%", PDO::PARAM_STR);
      $count -> execute();
    }elseif(isset($_GET['status'])){
      $status = $_GET['status'];
      $count = $pdo -> prepare('SELECT COUNT(*) AS count FROM application WHERE status = :status');
      $count -> bindValue(":status", $status, PDO::PARAM_STR);
      $count -> execute();
    }elseif(isset($_GET['apply_id'])){
      $apply_id = $_GET['apply_id'];
      $count = $pdo -> prepare('SELECT COUNT(*) AS count FROM application WHERE apply_id = :apply_id');
      $count -> bindValue(":apply_id", "%".$apply_id."%", PDO::PARAM_STR);
      $count -> execute();
    }elseif(isset($_GET['delivery'])){
      $delivery = $_GET['delivery'];
      if($delivery == 'week'){
        $count = $pdo -> prepare('SELECT COUNT(*) AS count FROM application WHERE CONVERT(DATE, delivery_date) < CONVERT(DATE, GETDATE()+7)');
      }elseif($delivery == 'over'){
        $count = $pdo -> prepare('SELECT COUNT(*) AS count FROM application WHERE CONVERT(DATE, delivery_date) < CONVERT(DATE, GETDATE())');
      }  
      $count -> execute();
    }else{
      $count = $pdo -> query('SELECT COUNT(*) AS count FROM application');
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

      if(isset($_GET['apply_id']) && isset($_GET['status'])){
        $tsql = "
        SELECT * FROM application
        WHERE status = :status
        AND apply_id LIKE :apply_id
        ORDER BY apply_date DESC
        OFFSET :start ROWS
        FETCH NEXT :max ROWS ONLY;
        ";
        $result = $pdo -> prepare($tsql);
        $result -> bindValue(":status", $status, PDO::PARAM_STR);
        $result -> bindValue(":apply_id", "%".$apply_id."%", PDO::PARAM_STR);
      }elseif(isset($_GET['status'])){
        $tsql = "
        SELECT * FROM application
        WHERE status = :status
        ORDER BY apply_date DESC
        OFFSET :start ROWS
        FETCH NEXT :max ROWS ONLY;
        ";
        $result = $pdo -> prepare($tsql);
        $result -> bindValue(":status", $status, PDO::PARAM_STR);
      }elseif(isset($_GET['apply_id'])){
        $tsql = "
        SELECT * FROM application
        WHERE apply_id LIKE :apply_id
        ORDER BY apply_date DESC
        OFFSET :start ROWS
        FETCH NEXT :max ROWS ONLY;
        ";
        $result = $pdo -> prepare($tsql);
        $result -> bindValue(":apply_id", "%".$apply_id."%", PDO::PARAM_STR);
      }elseif(isset($_GET['delivery'])){
        $delivery = $_GET['delivery'];
        if($delivery == 'week'){
          $tsql = "
          SELECT * FROM application
          WHERE CONVERT(DATE, delivery_date) <= CONVERT( DATE, GETDATE()+7)
          AND status != '対応済'
          AND status != '取り下げ'
          ORDER BY apply_date DESC
          OFFSET :start ROWS
          FETCH NEXT :max ROWS ONLY;
          ";
          $result = $pdo -> prepare($tsql);
        }elseif($delivery == 'over'){
          $tsql = "
          SELECT * FROM application
          WHERE CONVERT(DATE, delivery_date) < CONVERT( DATE, GETDATE())
          AND status != '対応済'
          AND status != '取り下げ'
          ORDER BY apply_date DESC
          OFFSET :start ROWS
          FETCH NEXT :max ROWS ONLY;
          ";
          $result = $pdo -> prepare($tsql);
        }
      }else{
        $tsql = "
        SELECT * FROM application
        ORDER BY apply_date DESC
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
      $apply_data = $result -> fetchAll(PDO::FETCH_ASSOC);

      return [$apply_data, $pages, $now, $err_message, TRUE];
    }
  } catch (Exception $e){
    $err_message = '[ERR-03]問合せデータの取得に失敗しました。（' . $e->getMessage() . '）';
    return [$apply_data, $pages, $now, $err_message, FALSE];
  }
}
?>
<script type="text/javascript">
$('.inquiry-card').click(function(){
  var apply_id = $(this).children('input').val();
  var f = document.getElementById(apply_id);
  f.submit();
});

function modify_query(){
  var params = {};
  var select_apply_id = $('#apply_id_form').val();
  var select_status = $('#status_form').val();
  if(select_apply_id){
    params['apply_id'] = select_apply_id;
  }
  if(select_status){
    params['status'] = select_status;
  }
  window.location.href = setParameter(params);
}

</script>
<?php include('footer.php'); ?>