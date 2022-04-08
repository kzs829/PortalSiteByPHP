<?php include('header.php'); ?>
<?php
//DBの接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  //コラムの取得
  $blog = get_blog($pdo);
}
?>
<main>
  <div style = "padding: 10px;">
    <h2 class = "subheading">Column</h2>
    <div style = "background-color:white;">
      <?php if(empty($blog)){ ?>
        <p style = "padding:20px;">コラムはまだありません。</p>
      <?php } ?>
      <?php foreach($blog as $row){ ?>
        <div class = "info-card" onclick = "location.href = 'blog_page.php?blog_no=<?php echo $row['blog_no']; ?>'">
          <h2 class = "datetime" style = "font-size:14px; border:none;"><?php echo substr($row['post_time'], 0, 10); ?></h2>
          <?php if($row['image']){ ?>
            <img src="<?php echo column_sumnail_path.$row['image']; ?>" width="200" height="120" style = "vertical-align:top; object-fit:cover;">
          <?php }else{ ?>
            <img src="./image/no-image.jpg" width="200" height="120" style = "vertical-align:top; object-fit:cover;">
          <?php } ?>
          <span class = "image-label" style = "margin-right:-200px; left:-204px; top:-7px; width:200px;"><b><?php echo $row['major_category']; ?></b></span>
          <div style = "display:inline-block; width:60%;">
            <h1><?php echo $row['subject']; ?></h1>
            <div class = "line-limit">
              <p style = "white-space: pre-wrap;"><?php echo $row['body']; ?></p>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</main>
<?php
//新着情報の取得
function get_blog($pdo){
  if(isset($_GET['major_category'])){
    $major_category = $_GET['major_category'];
    $result = $pdo -> prepare('SELECT * FROM blog where major_category = ? ORDER BY post_time DESC');
    $params = array($major_category);
    $result -> execute($params);
  }else{
    $result = $pdo -> query('SELECT * FROM blog ORDER BY post_time DESC');
  }
  $blog = $result -> fetchAll(PDO::FETCH_ASSOC);

  return $blog;
}  
?>
<?php include('footer.php'); ?>