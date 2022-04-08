<?php include('header.php'); ?>
<?php
$query = $_SERVER['QUERY_STRING'];
$newinfo_no = str_replace('newinfo_no=', '', $query);
//DBの接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  //新着情報の取得
  $new_info = get_newinfo($pdo, $newinfo_no);
}
?>
<main>
<div style = "margin-bottom:20px; margin-top:10px;">
  <a href = "new_info.php">新着情報一覧へ</a>
</div>
<div class = "article_bg">
  <?php if(isset($err_message)){ ?>
    <p class = "err_message"><?php echo $err_message; ?></p>
  <?php } ?>
  <form action = "update_newinfo.php?<?php echo $query; ?>" method = "post" name = "newinfo_edit">
    <?php foreach($new_info as $row){ ?>
      <div class = "blog-head">
        <h2 id = "subject-show" style = "display:inline-block;"><?php echo $row['subject']; ?></h2>
        <input type = "text" name = "subject" class = "textbox" id = "subject-edit" value = "<?php echo $row['subject']; ?>" required maxlength = '50'>
        <?php if(isset($_SESSION['ADMIN_FLG']) && $_SESSION['ADMIN_FLG'] == 1){ ?>
          <a class = "edit-button" id = "newinfo-edit" style = "float:right; margin-top:8px;"><i class="material-icons">&#xE150;</i></a>
          <a class = "edit-button" id = "newinfo-cancel" style = "float:right; margin-top:8px;"><i class="material-icons">&#xE14C;</i></a>
          <a class = "edit-button" id = "newinfo-save" href = "javascript:newinfo_edit.submit()" style = "float:right; margin-top:8px;">
            <i class="material-icons">&#xE5CA;</i>
          </a>
        <div style = "clear:float;"></div>
        <?php } ?>
        <p class = "datetime" style = "margin-bottom:10px;"><?php echo substr($row['post_time'], 0, 10); ?></p>
        <span class = "label"><?php echo $row['major_category']; ?></span>
      </div>
      <div class = "blog-body">
        <p id = "body-show" style = "white-space: pre-wrap;"><?php echo $row['body']; ?></p>
        <input type = "button" id = "add_link_button" value = "リンクを挿入" class = "button" onclick = "add_link();">
        <div style = "display:none;" id = "dialog" >
          <p style = "font-size:14px;">url</p>
          <input type = "text" id = "url" class = "textbox">
          <p style = "font-size:14px;">表示するテキスト</p>
          <input type = "text" id = "display_name" class = "textbox">
        </div>
        <textarea rows = "10" name = "body" class = "textarea" id = "body-edit" required maxlength = '1000'><?php echo $row['body']; ?></textarea>
        <input type = "hidden" name = "newinfo_no" value = "<?php echo $row['newinfo_no']; ?>">
        <?php if(isset($_SESSION['ADMIN_FLG']) && $_SESSION['ADMIN_FLG'] == 1){ ?>
          <a href = "javascript:delete_confirm();" style = "color:red; display:block; margin-top:50px;">この新着情報を削除する</a>
        <?php } ?>
      </div>
    <?php } ?>
  </form>
</div>
</main>
<script type="text/javascript">
//編集ボタンの制御
$(function(){
  $('#newinfo-cancel').hide();
  $('#newinfo-save').hide();
  $('#subject-edit').hide();
  $('#body-edit').hide();
  $('#add_link_button').hide();
  $('#newinfo-edit').on('click', function(){
    $('#newinfo-edit').hide();
    $('#newinfo-cancel').show();
    $('#newinfo-save').show();
    $('#subject-show').hide();
    $('#subject-edit').show();
    $('#body-show').hide();
    $('#body-edit').show();
    $('#add_link_button').show();
  });
  $('#newinfo-cancel, #newinfo-save').on('click', function(){
    $('#newinfo-edit').show();
    $('#newinfo-cancel').hide();
    $('#newinfo-save').hide();
    $('#subject-show').show();
    $('#subject-edit').hide();
    $('#body-show').show();
    $('#body-edit').hide();
    $('#add_link_button').hide();
  });
});

//新着情報削除ボタン押下時の処理
function delete_confirm(){
  if(window.confirm('新着情報を削除してもよろしいですか？')){
    location.href = "delete_newinfo.php?<?php echo $query; ?>";
  }
}

//リンク挿入ダイアログ
function add_link(){
  $("#dialog").dialog({
		modal:true,
		title:"リンクを挿入",
		buttons: {
		  "追加": function() {
        var url = $("#url").val();
        var display_name = $("#display_name").val();
        $(this).dialog("close");
        var body = $('#body-edit').val();
        var link_tag = '<a href = "' + url + '">' + display_name +'</a>';
        $('#body-edit').val(body + '\n' + link_tag);
		  },
		  "キャンセル": function() {
			  $(this).dialog("close"); 
		  }
		}
	});
}
</script>
<?php
//新着情報の取得
function get_newinfo($pdo, $newinfo_no){
  $result = $pdo -> prepare('SELECT * FROM new_info where newinfo_no = ?');
  $params = array($newinfo_no);
  $result -> execute($params);
  $new_info = $result -> fetchAll(PDO::FETCH_ASSOC);

  return $new_info;
}  
?>
<?php include('footer.php'); ?>