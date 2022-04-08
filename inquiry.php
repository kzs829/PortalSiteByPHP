<?php include('header.php'); ?>
<?php
if(!(isset($_SESSION['MAIL']))){
  $alert = "<script type='text/javascript'>alert(' お問合せにはログインが必要です。');</script>";
  echo $alert;
?>
  <form action = "login.php" method = "post" name = "post"></form>
<?php
  exit;
}
?>
<main>
  <div class = "inquiry" style = "padding: 10px;">
    <h3 class = "subheading">お問合せ</h3>
    <p>
      <b>お問合せは以下のフォームからお願いいたします。</b><br>
      <b>添付いただきたい情報</b><br>
      <i class="material-icons">&#xE5CA;</i>
      どのような操作を行っているときに発生したか<br>
      <i class="material-icons">&#xE5CA;</i>
      アプリ名とバージョン<br>
      <i class="material-icons">&#xE5CA;</i>
      ログ<br><br>
    </p>
    <form method = "post" name = "inquiry_content" action = "inquiry_insert_db.php" accept-charset="ASCII" enctype="multipart/form-data">
      <p>名前<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></p>
      <input readonly type = "text" id = "name" name = "name" class = "textbox" value = "<?php echo $_SESSION['NAME']; ?>">
      <p>メールアドレス<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></p>
      <input readonly type = "email" id = "mail" name = "mail" class = "textbox" value = "<?php echo $_SESSION['MAIL']; ?>">
      <p>件名<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></p>
      <input type = "text" id = "subject" name = "subject" class = "textbox" required maxlength = '20' >
      <p class = "validation"></p>
      <p>お問合せ内容<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></p>
      <textarea rows = "10" id = "body" name = "body" class = "textarea" required maxlength = '2000' ></textarea>
      <p class = "validation"></p>
      <p>添付ファイル<span class = "label" title = "任意" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">任意</span></p>
      <input type="file" name="upfile[]" multiple="multiple">
      <p>CC<span class = "label" title = "任意" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">任意</span>　<a title = "ヘルプ" href = "javascript:showModal();"><i class="material-icons">&#xE8FD;</i></a></p>
      <div class = "wrap_cc">
        <input type = "text" class = "textbox" id = "cc" autocomplete="off">
        <ul id = "search_result" class = "search_candidate"></ul>
      </div>
      <ul id = "cc_list"></ul>
      <div class = "modal-window" id = "modal-window">
        <div class = "modal-inner">
          <a class = "close-modal" id = "close-modal"><i class="material-icons">&#xE14C;</i></a>
          <img src = "<?php echo old_other_documents_path; ?>CC追加方法.jpg"></img>
        </div>
        <div class="black-background" id = "black-background"></div>
      </div>
      <p>公開設定</p>
      <select name = "publishing-setting" class = "select-box">
      　<option class = "select-item" selected>公開</option>
        <option class = "select-item">非公開</option>
      </select>
      <p style = "margin-top:0;"><i class="material-icons" style = "color: red;">&#xE002;</i>公開にするとすべてのユーザーがこのお問合せを閲覧できます。<br>　（名前やメールアドレスは公開されません。）</p>
      <input type = "button" value = "送信" class = "button" onclick = "post_inquiry();">
    </form>
  </div>
</main>
<script type="text/javascript">
function post_inquiry() {
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

  var cc_list = $('#cc_list').children("li");

  send_list = [];
  for (var i = 0; i < cc_list.length; i++){
    var mail = cc_list[i].innerText.split("/")[1].replace("×", "");
    send_list.push(mail);
  }

  if(err_list.length == 0){
    var f = document.forms["inquiry_content"];
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = "send_list";
    input.value = JSON.stringify(send_list);
    f.appendChild(input);
    document.charset='ASCII';
    f.submit();
  }
}

//Ccリアルタイム検索
$("#cc").keyup(function(){
  $("#search_result li").remove();
  var search_text = $("#cc").val();
  if(search_text != ""){
    var ul = document.getElementById('search_result');    
    var li = document.createElement("li");
    li.className = "candidate";
    var text = document.createTextNode(search_text);
    li.appendChild(text);
    ul.appendChild(li);
    //検索実施
    $.post("search_realtime.php", {search_str : search_text}, function(data){
      var search_result = "";
      if (data.length > 0){ 
        data = JSON.parse(data)
        for ( item in data ) {
          search_result = data[item].name + "/" + data[item].mail;
          var li = document.createElement("li");
          li.className = "candidate";
          var text = document.createTextNode(search_result);
          li.appendChild(text);
          ul.appendChild(li);
        }
      }else{
        var li = document.createElement("li");
        var text = document.createTextNode("連絡先候補がありません");
        li.appendChild(text);
        ul.appendChild(li);
      } 
    })
  } 
});

//Cc追加
$("#search_result").on("click", ".candidate", function(){
  $("#search_result li").remove();
  if($(this).text().match(/@/)){
    $("#cc").val("");
    var ul = document.getElementById('cc_list');
    var li = document.createElement("li");

    var a = document.createElement("a");
    a.appendChild(document.createTextNode("×"));
    a.className = "cc_delete";
    a.title = "CCから削除";

    var span = document.createElement("span");
    var text = document.createTextNode($(this).text());
    span.appendChild(text);
    span.title = $(this).text();
    
    li.appendChild(span);
    li.appendChild(a);
    ul.appendChild(li);
  }
});

//Cc削除
$("#cc_list").on("click", "li a", function(){
  $(this).parent().remove();
});

</script>
<?php include('footer.php'); ?>