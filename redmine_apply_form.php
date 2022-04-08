<?php include('header.php');?>
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
    <h2 class = "subheading">RedmineID登録依頼</h2>
    <dl>
      <dt>申請部門<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd>
        <input type = "text" name = "department" id = "department" class = "textbox" required maxlength = '20' value = "<?php if(isset($applicant_info)){echo $applicant_info[0];} ?>"   placeholder = "例）生産開発部">
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
        </select>
      </dd>
    </dl>
    <dl>
      <dt>姓<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd>
        <input type = "text" name = "family_name" id = "family_name" class = "textbox" required maxlength = '10'  placeholder = "例）山田">
        <p class = "validation"></p>
      </dd>
      <dt>名<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd>
        <input type = "text" name = "first_name" id = "first_name" class = "textbox" required maxlength = '10'  placeholder = "例）太郎">
        <p class = "validation"></p>
      </dd>
      <dt>登録ID(メールアドレス)<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></dt>
      <dd>
        <input type = "email" name = "id" id = "id" class = "textbox" required maxlength = '30'  placeholder = "例）sample@example.com">
        <p class = "validation"></p>
      </dd>
      <dt>備考<span class = "label" title = "任意" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">任意</span></dt>
      <dd><textarea rows = "5" name = "note" id = "note" class = "textarea" maxlength = '300' style = "width:300px;"></textarea></dd>
    </dl>
    <button type = "button" class = "button" onclick="add_table();" style = "margin-left:475px; margin-bottom:30px;">追加</button>
    <table id="request_table">
      <thead>
        <tr>
          <th class = "col" style = "width:100px;">区分</th>
          <th class = "col" style = "width:100px;">姓</th>
          <th class = "col" style = "width:100px;">名</th>
          <th class = "col" style = "width:200px;">登録ID(メールアドレス)</th>
          <th class = "col" style = "width:200px;">備考</th>
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
            <td><input type = "button" value = "削除" class = "button" onclick = "rowdel(this)"></td>
          </tr>
        <?php
          }
        }
        ?>
      </tbody>
    </table>
    <input type = "submit" value = "申込" class = "button" onclick = "post_apply_data()">
    <form name = "apply_data" action = "redmine_apply_confirm.php" method = "post">
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
  var mail_message = '※正しいメールアドレスの形式で入力してください';
  var mail_check = "[^A-Za-z0-9@.!#$%&'*+\/=?^_`{|}~-]";
  var bool;
  var err_list = [];

  var id_list = ['family_name', 'first_name', 'id'];
  for(var i = 0; i < id_list.length; i++){
    bool = validation('#'+ id_list[i]);
    if(bool == false){ err_list.push(1); }
  }

  bool = validate($('#id'),mail_message,mail_check);
  if(bool == false){
    err_list.push(1);
  }else{
    bool = vali_mail_atmark($('#id'));
    if(bool == false){
      err_list.push(1);
    }
  }

  if(err_list.length != 0){
    exit;
  }

  var category = document.getElementById("category").value;
  var family_name = document.getElementById("family_name").value;
  var first_name = document.getElementById("first_name").value;
  var id = document.getElementById("id").value;
  var note = document.getElementById("note").value;

  var items = [category, family_name, first_name, id, note];

  var table = document.getElementById('request_table');
  var newRow = table.insertRow();
  newRow.className = "details"
  var newCell
  var newText

  for(var i = 0; i < items.length; i++){
    newCell = newRow.insertCell();
    newText = document.createTextNode(items[i]);
    newCell.appendChild(newText);
  }

  //削除ボタンの生成
  newCell = newRow.insertCell();
  newCell.innerHTML = '<input type = "button" value = "削除" class = "button" onclick = "rowdel(this)">';
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
</script>
<?php include('footer.php'); ?>