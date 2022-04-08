<?php include('header.php'); ?>
<?php
//DBの接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  //新着情報の取得
  $new_info = get_newinfo($pdo);
}
?>
<main>
  <div class = "release-info" style = "padding: 10px;">
    <h2 class = "subheading">News</h2>
    <div class = "tabs">
      <input class = "tab-radio" id = "release-radio" name = "tab-radio" type = "radio" value = "リリース" checked>
      <label class = "tab-label" id = "release-label" for = "release-radio">　リリース情報</label>
      <input class = "tab-radio" id = "event-radio" name = "tab-radio" type = "radio" value = "イベント">
      <label class = "tab-label" id = "event-label" for = "event-radio">　イベント</label>
      <input class = "tab-radio" id = "other-radio" name = "tab-radio" type = "radio" value = "その他">
      <label class = "tab-label" id = "other-label" for = "other-radio">　その他</label>
      <div class = "tab-content">
        <div class = "grouping-info">
          <?php if(empty($new_info)){ ?>
            <p style = "padding:20px;">新着情報はまだありません。</p>
          <?php } ?>
          <?php foreach($new_info as $row){ ?>
            <div class = "info-card" onclick = "location.href = 'newinfo_page.php?newinfo_no=<?php echo $row['newinfo_no']; ?>'">
              <span class = "label"><?php echo $row['major_category']; ?></span>
              <h1 style = "margin-top:10px;"><?php echo $row['subject']; ?></h1>
              <h2 class = "datetime"><?php echo substr($row['post_time'], 0, 10); ?></h2>
              <div class = "line-limit">
                <p style = "white-space: pre-wrap;"><?php echo $row['body']; ?></p>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</main>
<script type="text/javascript">
$(function(){
  $('input[name="tab-radio"]:radio').change( function() {
    var radioval = $(this).val();
    var params = getParameter();
    params['major_category'] = radioval;
    window.location.href = setParameter(params);
  });
});

//ラジオボタンのチェックの変更
var params = getParameter();
var major_category = decodeURI(params['major_category'])
$('input[value=' + major_category + ']').prop('checked', true);
</script>
<?php
//新着情報の取得
function get_newinfo($pdo){
  if(isset($_GET['major_category'])){
    $major_category = $_GET['major_category'];
  }else{
    $major_category = 'リリース';
  }
  $result = $pdo -> prepare('SELECT * FROM new_info where major_category = ? ORDER BY post_time DESC');
  $params = array($major_category);
  $result -> execute($params);
  $new_info = $result -> fetchAll(PDO::FETCH_ASSOC);

  return $new_info;
}  
?>
<?php include('footer.php'); ?>