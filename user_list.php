<?php include('header.php'); ?>
<?php
if(!(isset($_SESSION['MAIL']))){
  $alert = "<script type='text/javascript'>alert('ユーザー一覧の閲覧にはログインが必要です。');</script>";
  echo $alert;
?>
  <form action = "login.php" method = "post" name = "post"></form>
<?php
  exit;
}
?>
<?php
//1ページに表示する件数
define('max_view', 10);

//DBの接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  //カウント
  list($all, $admin_user, $general_user, $deleted_user) = count_user($pdo);
  //ユーザーの取得
  list($user, $pages, $now) = get_user($pdo);
}
?>
<main>
  <div style = "padding: 10px;">
    <h2 class = "subheading">ユーザー一覧</h2>
    <?php if(isset($err_message)){ ?>
      <p class = "err_message"><?php echo $err_message; ?></p>
    <?php } ?>
    <p>全て：<?php echo $all['count']; ?>人　管理者：<?php echo $admin_user['count']; ?>人　一般ユーザー：<?php echo $general_user['count']; ?>人　削除済：<?php echo $deleted_user['count']; ?>人</p>
    <p>ユーザー名で絞り込みができます。</p>
    <form action = "user_list.php" method = "get" style="display:inline;">
      <input type = "text" class = "textbox" name = "name" value = "<?php if(isset($_GET['name'])){ echo $_GET['name']; } ?>">
      <input type = "submit" value = "絞り込む" class = "button" style = "display:inline-block;">
    </form>
    <input type = "button" value = "すべて表示" onclick = "location.href='user_list.php'" class = "button" style = "display:inline-block;">
    <?php if(empty($user)){ ?>
      <p style = "padding:20px;">ユーザーはまだいません。</p>
    <?php } ?>
    <table>
      <thead>
        <tr>
          <th class= "col" style = "width:200px;">名前</th>
          <th class= "col" style = "width:200px;">メールアドレス</th>
          <th class= "col" style = "width:100px;">権限</th>
          <th class= "col" style = "width:200px;">備考</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($user as $row){ ?>
        <tr class = "user_row">
          <td class = "txt"><?php echo $row['name']; ?></td>
          <td class = "txt"><?php echo $row['mail']; ?></td>
          <td class = "txt"><?php if($row['admin_flg'] == 1){ ?>管理者<?php }else{ ?>一般<?php } ?></td>
          <td class = "txt"><?php if($row['deleted_flg'] == 1){ ?>削除済<?php } ?></td>
          <input type = "hidden" class = "user_no" value = "<?php echo $row['user_no']; ?>">
          <form action = "user_detail.php" method = "post" id = "<?php echo $row['user_no']; ?>">
            <input type = "hidden" name = "user_no" value = "<?php echo $row['user_no']; ?>">
          </form>
        </tr>
        <?php } ?>
      </tbody>
    </table>
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
</main>
<?php
function count_user($pdo){
  $tsql = "SELECT COUNT(*) AS count FROM user_info;";
  $result = $pdo -> query($tsql);
  $all = $result -> fetch(PDO::FETCH_ASSOC);
  $tsql = "SELECT COUNT(*) AS count FROM user_info WHERE admin_flg = 1;";
  $result = $pdo -> query($tsql);
  $admin_user = $result -> fetch(PDO::FETCH_ASSOC);
  $tsql = "SELECT COUNT(*) AS count FROM user_info WHERE admin_flg = 0;";
  $result = $pdo -> query($tsql);
  $general_user = $result -> fetch(PDO::FETCH_ASSOC);
  $tsql = "SELECT COUNT(*) AS count FROM user_info WHERE deleted_flg = 1;";
  $result = $pdo -> query($tsql);
  $deleted_user = $result -> fetch(PDO::FETCH_ASSOC);

  return [$all, $admin_user, $general_user, $deleted_user];
}

function get_user($pdo){
  if(isset($_GET['name'])){
    $count = $pdo -> prepare('SELECT COUNT(*) AS count FROM user_info WHERE name like :name');
    $count -> bindValue(":name", '%'.$_GET['name'].'%', PDO::PARAM_STR);
    $count -> execute();
  }else{
    $count = $pdo -> query('SELECT COUNT(*) AS count FROM user_info');
  }
  $total_count = $count -> fetch(PDO::FETCH_ASSOC);

  //必要なページ数を取得      
  $pages = ceil($total_count['count'] / max_view);

  //現在のページ番号を取得
  if(!isset($_GET['page_id'])){
    $now = 1;
  }else{
    $now = $_GET['page_id'];
  }

  if(isset($_GET['name'])){
    $sql = 'SELECT * FROM user_info WHERE name like :name ORDER BY user_no DESC OFFSET :start ROWS FETCH NEXT :max ROWS ONLY;';
    $result = $pdo -> prepare($sql);
    $result -> bindValue(":name", '%'.$_GET['name'].'%', PDO::PARAM_STR);
  }else{
    $result = $pdo -> prepare('SELECT * FROM user_info ORDER BY user_no DESC OFFSET :start ROWS FETCH NEXT :max ROWS ONLY;');
  }
  
  if($now == 1){
    $result -> bindValue(":start", $now - 1, PDO::PARAM_INT);
    $result -> bindValue(":max", max_view, PDO::PARAM_INT);
  }else{
    $result -> bindValue(":start", ($now - 1) * max_view, PDO::PARAM_INT);
    $result -> bindValue(":max", max_view, PDO::PARAM_INT);
  }

  $result -> execute();
  $user = $result -> fetchAll(PDO::FETCH_ASSOC);

  return [$user, $pages, $now];
}  
?>
<script type="text/javascript">
  $('.user_row').click(function(){
    var user_no = $(this).children('input').val();
    var f = document.getElementById(user_no);
    f.submit();
  });
</script>
<?php include('footer.php'); ?>