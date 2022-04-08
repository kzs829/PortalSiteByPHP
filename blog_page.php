<?php include('header.php'); ?>
<?php
$query = $_SERVER['QUERY_STRING'];
$blog_no = str_replace('blog_no=', '', $query);
//DBの接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  //コラムの取得
  $blog = get_blog($pdo, $blog_no);
}
?>
<main>
<div style = "margin-bottom:20px; margin-top:10px;">
  <a href = "blog_list.php">コラム一覧へ</a>
</div>
<div class = "blog-bg">
  <?php if(isset($err_message)){ ?>
    <p class = "err_message"><?php echo $err_message; ?></p>
  <?php } ?>
  <form action = "update_blog.php?<?php echo $query; ?>" method = "post" name = "blog_edit" accept-charset="ASCII" enctype="multipart/form-data">
    <?php foreach($blog as $row){ ?>
      <div class = "blog-head">
        <h2 id = "subject-show" style = "display:inline-block; font-size:30px;"><?php echo $row['subject']; ?></h2>
        <input type = "text" name = "subject" class = "textbox" id = "subject-edit" value = "<?php echo $row['subject']; ?>" required maxlength = '50'>
        <?php if(isset($_SESSION['ADMIN_FLG']) && $_SESSION['ADMIN_FLG'] == 1){ ?>
          <a class = "edit-button" id = "blog-edit" style = "float:right; margin-top:8px;"><i class="material-icons">&#xE150;</i></a>
          <a class = "edit-button" id = "blog-cancel" style = "float:right; margin-top:8px;"><i class="material-icons">&#xE14C;</i></a>
          <a class = "edit-button" id = "blog-save" href = "javascript:blog_edit.submit()" onclick="document.charset='ASCII';" style = "float:right; margin-top:8px;">
            <i class="material-icons">&#xE5CA;</i>
          </a>
        <div style = "clear:float;"></div>
        <?php } ?>
        <p class = "datetime" style = "margin-bottom:10px; margin-top:3px;"><?php echo substr($row['post_time'], 0, 10); ?></p>
        <span class = "label"><?php echo $row['major_category']; ?></span>
      </div>
      <div class = "blog-body">
        <?php if($row['image']){ ?>
          <img src="<?php echo column_sumnail_path.$row['image']; ?>" width="400" style = "object-fit:cover;">
        <?php }?>
        <p id = "body-show" style = "white-space: pre-wrap;"><?php echo $row['body']; ?></p>
        <input type="file" name="image" id = "samnail" accept="image/jpeg, image/png" style = "display:block;">
        <input type = "button" id = "add_link_button" value = "リンクを挿入" class = "button" onclick = "add_link();">
        <div style = "display:none;" id = "dialog" >
          <p style = "font-size:14px;">url</p>
          <input type = "text" id = "url" class = "textbox">
          <p style = "font-size:14px;">表示するテキスト</p>
          <input type = "text" id = "display_name" class = "textbox">
        </div>
        <textarea rows = "10" name = "body" class = "textarea" id = "body-edit" required maxlength = '1000'><?php echo $row['body']; ?></textarea>
        <?php if(isset($_SESSION['ADMIN_FLG']) && $_SESSION['ADMIN_FLG'] == 1){ ?>
          <div></div>
          <a href = "javascript:delete_confirm();" style = "color:red; display:inline-block; margin-top:50px;">このコラムを削除する</a>
        <?php } ?>
      </div>
      <input type = "hidden" name = "blog_no" value = "<?php echo $row['blog_no']; ?>">
    <?php } ?>
  </form>
</div>
</main>
<script type="text/javascript">
//編集ボタンの制御
$(function(){
  $('#blog-cancel').hide();
  $('#blog-save').hide();
  $('#subject-edit').hide();
  $('#body-edit').hide();
  $('#add_link_button').hide();
  $('#samnail').hide();
  $('#blog-edit').on('click', function(){
    $('#blog-edit').hide();
    $('#blog-cancel').show();
    $('#blog-save').show();
    $('#subject-show').hide();
    $('#subject-edit').show();
    $('#body-show').hide();
    $('#body-edit').show();
    $('#add_link_button').show();
    $('#samnail').show();
  });
  $('#blog-cancel, #blog-save').on('click', function(){
    $('#blog-edit').show();
    $('#blog-cancel').hide();
    $('#blog-save').hide();
    $('#subject-show').show();
    $('#subject-edit').hide();
    $('#body-show').show();
    $('#body-edit').hide();
    $('#add_link_button').hide();
    $('#samnail').hide();
  });
});

//新着情報削除ボタン押下時の処理
function delete_confirm(){
  if(window.confirm('コラムを削除してもよろしいですか？')){
    location.href = "delete_blog.php?<?php echo $query; ?>";
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
function get_blog($pdo, $blog_no){
  $result = $pdo -> prepare('SELECT * FROM blog where blog_no = ?');
  $params = array($blog_no);
  $result -> execute($params);
  $blog = $result -> fetchAll(PDO::FETCH_ASSOC);

  return $blog;
}  
?>
<?php include('footer.php'); ?>