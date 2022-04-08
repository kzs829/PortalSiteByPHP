<?php include('header.php'); ?>
<?php
?>
<main>
  <div class = "post_newinfo" style = "padding: 10px;">
    <h3 class = "subheading">新着情報投稿</h3>
    <p>新着情報の投稿は以下のフォームからお願いいたします。</p>
    <form method = "post" name = "newinfo_content" action = "newinfo_insert_db.php" accept-charset="utf-8">
      <p>大分類</p>
      <select name = "major_category" class = "select-box">
      　<option class = "select-item" value = "リリース" selected>リリース</option>
      　<option class = "select-item" value = "イベント">イベント</option>
        <option class = "select-item" value = "その他">その他</option>
      </select>
      <p>件名<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></p>
      <input type = "text" id = "subject" name = "subject" class = "textbox" required maxlength = '50' >
      <p class = "validation"></p>
      <p>内容<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></p>
      <input type = "button" value = "リンクを挿入" class = "button" onclick = "add_link();">
      <div style = "display:none;" id = "dialog" >
        <p style = "font-size:14px;">url</p>
        <input type = "text" id = "url" class = "textbox">
        <p style = "font-size:14px;">表示するテキスト</p>
        <input type = "text" id = "display_name" class = "textbox">
      </div>
      <textarea rows = "10" id = "body" name = "body" class = "textarea" id = "body" required maxlength = '1000' ></textarea>
      <p class = "validation"></p>
      <input type = "button" value = "投稿" class = "button" onclick = "post_newinfo();">
    </form>
  </div>
</main>
<script type="text/javascript">
function add_link(){
  $("#dialog").dialog({
		modal:true,
		title:"リンクを挿入",
		buttons: {
		  "追加": function() {
        var url = $("#url").val();
        var display_name = $("#display_name").val();
        $(this).dialog("close");
        var body = $('#body').val();
        var link_tag = '<a href = "' + url + '">' + display_name +'</a>';
        $('#body').val(body + '\n' + link_tag);
		  },
		  "キャンセル": function() {
			  $(this).dialog("close"); 
		  }
		}
	});
}

function post_newinfo() {
  //バリデーション
  var required_message = "※必須入力です";
  var item_list = ['subject', 'body'];
  var err_list = [];
  
  for(var i = 0; i < item_list.length; i++){
    if(!$('#'+ item_list[i]).val()){
      vali_err(item_list[i], required_message);
      err_list.push(1);
    }else{
      vali_no_err(item_list[i]);
    }
  }

  if(err_list.length == 0){
    var f = document.forms["newinfo_content"];
    f.submit();
  }
}
</script>
<?php include('footer.php'); ?>
