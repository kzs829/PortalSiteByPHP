<?php include('header.php'); ?>
<?php
if(!(isset($_SESSION['MAIL']))){
  $alert = "<script type='text/javascript'>alert('お申込にはログインが必要です。');</script>";
  echo $alert;
?>
  <form action = "login.php" method = "post" name = "post"></form>
<?php
  exit;
}
?>
<?php
if(isset($_POST['json_applicant_info'])){
  $json_app_details = $_POST['json_app_details'];
  $json_applicant_info = $_POST['json_applicant_info'];
  $app_details = json_decode( $json_app_details , true );
  $applicant_info = json_decode( $json_applicant_info , true );
}
?>
<main>
  <div style = "padding: 10px;">
    <h2 class = "subheading">ツールAマスタ登録依頼</h2>
    <dl>
      <dt>申請部門<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd>
        <input type = "text" name = "department" id = "department" class = "textbox" required maxlength = '20' value = "<?php if(isset($applicant_info)){echo $applicant_info[0];} ?>"  placeholder = "例）生産開発部">
        <p class = "validation"></p>
      </dd>
      <dt>申請者氏名<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd><input readonly type = "text" name = "name" id = "name" class = "textbox" value = "<?php echo $_SESSION['NAME']; ?>"></dd>
      <dt>申請者メールアドレス<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd><input readonly type = "email" name = "mail" id = "mail" class = "textbox" value = "<?php echo $_SESSION['MAIL']; ?>"></dd>
      <dt>希望納期<span class = "label" title = "任意" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">任意</span></dt>
      <dd><input type = "text" name = "delivery_date" placeholder = '2000/01/01' id = "delivery_date" class = "datepicker" required value = "<?php if(isset($applicant_info)){echo $applicant_info[3];} ?>"></dd>
      <dt>区分</dt>
      <dd>
        <select name = "category" class = "select-box" id = "category">
      　  <option class = "select-item" selected>新規</option>
          <option class = "select-item">削除</option>
          <option class = "select-item">変更</option>
        </select>
      </dd>
    </dl>
    <dl>
      <p style = "margin-top:30px;">変更前</p>
      <dt>書込みサイト<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd>
        <input type = "text" name = "site" id = "site" class = "textbox" style = "ime-mode:disabled;" required maxlength = '5'  placeholder = "例）A21">
        <p class = "validation"></p>
      </dd>
      <dt>品目番号<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd>
        <input type = "text" name = "item_number" id = "item_number" style = "ime-mode:disabled;" class = "textbox" required maxlength = '20'  placeholder = "例）CBE-00000-102-00">
        <p class = "validation"></p>
      </dd>
      <dt>書込み後サイト<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd>
        <input type = "text" name = "later_site" id = "later_site" style = "ime-mode:disabled;" class = "textbox" required maxlength = '20'  placeholder = "例）A21">
        <p class = "validation"></p>
      </dd>
      <dt>新品目番号<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd>
        <input type = "text" name = "new_item_number" id = "new_item_number" style = "ime-mode:disabled;" class = "textbox" required maxlength = '20'  placeholder = "例）NWA-00000-001-01">
        <p class = "validation"></p>
      </dd>
      <dt>品目名称<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd>
        <input type = "text" name = "item_name" id = "item_name" style = "ime-mode:disabled;" class = "textbox" required maxlength = '20'  placeholder = "例）MX25000000000A-10G">
        <p class = "validation"></p>
      </dd>
    </dl>
    <dl>
      <p style = "margin-top:30px;">変更後（区分が変更のときのみ入力が必要です）</p>
      <dt>書込みサイト<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd>
        <input type = "text" name = "change_site" id = "change_site" style = "ime-mode:disabled;" class = "textbox" disabled required maxlength = '5'  placeholder = "例）A21">
        <p class = "validation"></p>
      </dd>
      <dt>品目番号<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd>
        <input type = "text" name = "change_item_number" id = "change_item_number" style = "ime-mode:disabled;" class = "textbox" disabled required maxlength = '20'  placeholder = "例）CBE-000000-102-00">
        <p class = "validation"></p>
      </dd>
      <dt>書込み後サイト<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd>
        <input type = "text" name = "change_later_site" id = "change_later_site" style = "ime-mode:disabled;" class = "textbox" disabled required maxlength = '20'  placeholder = "例）A21">
        <p class = "validation"></p>
      </dd>
      <dt>新品目番号<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd>
        <input type = "text" name = "change_new_item_number" id = "change_new_item_number" style = "ime-mode:disabled;" class = "textbox" disabled required maxlength = '20'  placeholder = "例）NWA-000000-000-01">
        <p class = "validation"></p>
      </dd>
      <dt>品目名称<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd>
        <input type = "text" name = "change_item_name" id = "change_item_name" style = "ime-mode:disabled;" class = "textbox" disabled required maxlength = '20'  placeholder = "例）MX25000000000A-10G">
        <p class = "validation"></p>
      </dd>
    </dl>
    <button type = "button" class = "button" onclick="add_table();" style = "margin-left:475px; margin-bottom:30px;">追加</button>
    <table id="request_table">
      <thead>
        <tr>
          <th class = "col" style = "width:100px;">区分</th>
          <th class = "col" style = "width:100px;">書込みサイト</th>
          <th class = "col" style = "width:100px;">品目番号</th>
          <th class = "col" style = "width:100px;">書込み後サイト</th>
          <th class = "col" style = "width:100px;">新品目番号</th>
          <th class = "col" style = "width:100px;">品目名称</th>
        </tr>
      </thead>
      <tbody>
        <tr>
        </tr>
        <?php
        if(isset($app_details)){
          foreach($app_details as $row){
        ?>
          <tr class = "details">
            <td><?php echo $row[0] ?></td>
            <td><?php echo $row[1] ?></td>
            <td><?php echo $row[2] ?></td>
            <td><?php echo $row[3] ?></td>
            <td><?php echo $row[4] ?></td>
            <td><?php echo $row[5] ?></td>
            <td><input type = "button" value = "削除" class = "button" onclick = "rowdel(this)"></td>
          </tr>
        <?php
          }
        }
        ?>
      </tbody>
    </table>
    <input type = "submit" value = "申込" class = "button" onclick = "post_apply_data()">
    <form name = "apply_data" action = "kurorom_apply_confirm.php" method = "post">
      <input type = "hidden" name = "json_app_details">
      <input type = "hidden" name = "json_applicant_info">
      <input type = "hidden" name = "uuid">
    </form>
  </div>
</main>
<script type="text/javascript">
//テーブルの行追加
function add_table(){
  //バリデーション
  var id_list = ['site', 'item_number', 'later_site', 'new_item_number', 'item_name'];
  var alfa_num_message = '※半角英数字かハイフン(-)で入力してください';
  var alfa_num_check = "[^A-Za-z0-9-]";
  var bool;
  var err_list = [];

  for(var i = 0; i < id_list.length; i++){
    bool = validate($('#'+ id_list[i]), alfa_num_message, alfa_num_check);
    if(bool == false){ err_list.push(1); }
  }

  var category = document.getElementById("category").value;
  if(category == '変更'){
    var id_list = ['change_site', 'change_item_number', 'change_later_site', 'change_new_item_number', 'change_item_name'];

    for(var i = 0; i < id_list.length; i++){
      bool = validate($('#'+ id_list[i]), alfa_num_message, alfa_num_check);
      if(bool == false){ err_list.push(1); }
    }
  }

  if(err_list.length != 0){
    exit;
  }

  //テーブルの行追加
  var site = document.getElementById("site").value;
  var item_number = document.getElementById("item_number").value;
  var later_site = document.getElementById("later_site").value;
  var new_item_number = document.getElementById("new_item_number").value;
  var item_name = document.getElementById("item_name").value;
  if(category == '変更'){
    var items = ['変更(前)', site, item_number, later_site, new_item_number, item_name];
  }else{
    var items = [category, site, item_number, later_site, new_item_number, item_name];
  }

  var table = document.getElementById('request_table');
  var newRow = table.insertRow();
  newRow.className = "details";
  var newCell;
  var newText;

  for(var i = 0; i < items.length; i++){
    newCell = newRow.insertCell();
    newText = document.createTextNode(items[i]);
    newCell.appendChild(newText);
  }

  //削除ボタンの生成
  newCell = newRow.insertCell();
  newCell.innerHTML = '<input type = "button" value = "削除" class = "button" onclick = "rowdel(this)">';

  if(category == '変更'){
    var change_site = document.getElementById("change_site").value;
    var change_item_number = document.getElementById("change_item_number").value;
    var change_later_site = document.getElementById("change_later_site").value;
    var change_new_item_number = document.getElementById("change_new_item_number").value;
    var change_item_name = document.getElementById("change_item_name").value;
    var change_items = ['変更(後)', change_site, change_item_number, change_later_site, change_new_item_number, change_item_name];
   
    newRow = table.insertRow();
    newRow.className = "details";

    for(var i = 0; i < change_items.length; i++){
      newCell = newRow.insertCell();
      newText = document.createTextNode(change_items[i]);
      newCell.appendChild(newText);
    }

    //削除ボタンの生成
    newCell = newRow.insertCell();
    newCell.innerHTML = '<input type = "button" value = "削除" class = "button" onclick = "rowdel(this)">';
  }
}

//テーブル行の削除
function rowdel(obj) {
  // 削除ボタンを押下された行を取得
  tr = obj.parentNode.parentNode;
  // trのインデックスを取得して行を削除する
  tr.parentNode.deleteRow(tr.sectionRowIndex);
}

//申込情報の送信
function post_apply_data() {
  //テーブルの値を配列に格納
  var app_details = [];
  var row_data = [];
  $('.details').each(function(i) {
    $('td', $(this)).each(function(j) {
    row_data.push($(this).text());
    });
    app_details.push(row_data);
    row_data = [];
  });

  //バリデーション
  var required_message = "※必須入力です";
  if(!$('#department').val()){
    vali_err('department', required_message);
    alert('未入力項目があります。');
    exit;
  }else{
    vali_no_err('department');
  }

  if(app_details.length == 0){
    alert('申込内容が未入力です。');
    exit;
  }

  //テキストボックスの情報を配列に格納
  var department = document.getElementById("department").value;
  var name = document.getElementById("name").value;
  var mail = document.getElementById("mail").value;
  var delivery_date = document.getElementById("delivery_date").value;
  var applicant_info = [department, name, mail, delivery_date];
  //UUID生成
  var uuid = generateUuid();

  //配列をJsonにしてsubmit
  var json_app_details = JSON.stringify(app_details);
  var json_applicant_info = JSON.stringify(applicant_info);
  var f = document.forms["apply_data"]
  f.elements["json_app_details"].value = json_app_details;
  f.elements["json_applicant_info"].value = json_applicant_info;
  f.elements["uuid"].value = uuid;
  f.submit();
}

$(function(){
  //select要素の取得
  var select = document.querySelector("#category");
  //option要素の取得（配列）
  var options = document.querySelectorAll("#category .select-item");
  //select要素のchangeイベントの登録
  select.addEventListener('change', function(){
    //選択されたoption番号を取得
    var index =  this.selectedIndex;
    //options[ index ].value にoption要素のvalue属性値
    //options[ index ].innerHTML にoption要素内の文字列
    //テキストボックスの活性非活性の制御
    if(options[ index ].innerHTML == '変更'){
      var change_site = document.querySelector('input[name = "change_site"]');
      change_site.disabled = false;
      var change_item_number = document.querySelector('input[name = "change_item_number"]');
      change_item_number.disabled = false;
      var change_later_site = document.querySelector('input[name = "change_later_site"]');
      change_later_site.disabled = false;
      var change_new_item_number = document.querySelector('input[name = "change_new_item_number"]');
      change_new_item_number.disabled = false;
      var change_item_name = document.querySelector('input[name = "change_item_name"]');
      change_item_name.disabled = false;
    }else{
      var change_site = document.querySelector('input[name = "change_site"]');
      change_site.disabled = true;
      var change_item_number = document.querySelector('input[name = "change_item_number"]');
      change_item_number.disabled = true;
      var change_later_site = document.querySelector('input[name = "change_later_site"]');
      change_later_site.disabled = true;
      var change_new_item_number = document.querySelector('input[name = "change_new_item_number"]');
      change_new_item_number.disabled = true;
      var change_item_name = document.querySelector('input[name = "change_item_name"]');
      change_item_name.disabled = true;
    }
  });
});
</script>
<?php include('footer.php'); ?>
