<?php include('header.php'); ?>
<?php
$type = $_POST['type'];
if($type == "親品番リスト登録依頼"){
  $table = "denryoku_oya_apply";
}elseif($type == "黒ROMマスタ登録依頼"){
  $table = "kurorom_apply";
}elseif($type == "RedmineID登録依頼"){
  $table = "redmine_apply";
}

//DB接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  //データ取得
  list($applicate_info, $apply_detail, $inquiry_chat) = get_apply_data($pdo, $table);
}
?>
<main>
  <div style = "padding: 10px;">
    <h2 class = "subheading">申込詳細</h2>
    <p>申込に対する問合せは画面下部の申込問合せエリアよりお願いいたします。問合せいただけるのは申込者ご本人とHP管理者のみです。</p>
    <?php if(isset($err_message)){ ?>
      <p class = "err_message"><?php echo $err_message; ?></p>
    <?php } ?>
    <?php
    foreach($applicate_info as $row){
      $apply_id = $row['apply_id'];
      if(isset($_SESSION['MAIL']) && $_SESSION['MAIL'] == $row['mail']){
        $self_flg = 1;
      }
        $status = $row['status'];
    ?>
      <?php if(isset($self_flg)){ ?>
          <a href = "#" onclick = "withdraw_apply();" style = "display:block; color:red;">申込を取り下げる</a>
      <?php } ?>
      <a href = "apply_list.php" style = "display:block;">申込一覧画面へ戻る</a><br>
      <form action = "withdraw_apply.php" method = "post" id = "withdraw_apply">
        <input type = "hidden" name = "mail" value = "<?php echo $row['mail']; ?>">
        <input type = "hidden" name = "name" value = "<?php echo $row['name']; ?>">
        <input type = "hidden" name = "apply_id" value = "<?php echo $row['apply_id']; ?>"> 
        <input type = "hidden" name = "type" value = "<?php echo $row['type']; ?>"> 
      </form>
      <p style = "display:inline-block;">ステータス</p>
      <?php
      if(isset($_SESSION['ADMIN_FLG']) && $_SESSION['ADMIN_FLG'] == 1){
        $admin_flg = 1;
      ?>
        <form action = "update_apply.php" method = "post" id = "apply_status" style = "display:inline-block;">
          <select name = "status" class = "select-box">
            <option hidden><?php echo $row['status']; ?></option>
            <option value = "対応中">対応中</option>
            <option value = "対応済">対応済</option>
          </select>
          <input type = "hidden" name = "mail" value = "<?php echo $row['mail']; ?>">
          <input type = "hidden" name = "name" value = "<?php echo $row['name']; ?>">
          <input type = "hidden" name = "apply_id" value = "<?php echo $row['apply_id']; ?>"> 
          <input type = "hidden" name = "type" value = "<?php echo $row['type']; ?>"> 
        </form>
      <?php }else{ ?>
        <span class = "label" title = "<?php echo $row['status']; ?>" style = "padding:7px 13px; text-align:center;"><?php echo $row['status']; ?></span>
      <?php } ?>
      <p style = "display:inline-block; margin-left:20px;">受付日</p>
      <p class = "datetime" style = "display:inline-block;">
        <?php if($row['reseption_date']){ echo substr($row['reseption_date'], 0, 10); }else{ echo '未受付'; } ?>
      </p>
      <p style = "display:inline-block; margin-left:20px;">対応完了日</p>
      <p class = "datetime" style = "display:inline-block;">
        <?php if($row['complete_date']){ echo substr($row['complete_date'], 0, 10); }else{ echo '未完了'; } ?>
      </p>
      <?php if(isset($_SESSION['ADMIN_FLG']) && $_SESSION['ADMIN_FLG'] == 1){ ?>
        <input type = "submit" class = "button" id = "update_button" value = "更新" style = "display:inline-block; margin-left:20px;">
      <?php } ?>
      <p>
        <span>申込ID</span>
        <span style = "font-size:14px; color:#696969;"><b><?php echo $row['apply_id'] ?></b></span>
      </p>
      <table>
        <tbody>
          <tr>
            <th class = "row" rowspan="2">申込者</th>
            <th class = "row">部門</th>
            <td><?php echo $row['department'] ?></td>
          </tr>
          <tr>
            <th class = "row">氏名</th>
            <td><?php echo $row['name'] ?></td>
          </tr>
          <tr>
            <th class = "row" colspan="2">申請日</th>
            <td><?php echo substr($row['apply_date'], 0, 10); ?></td>
          </tr>
          <tr>
            <th class = "row" colspan="2">希望納期</th>
            <td><?php if($row['delivery_date'] == '1900-01-01 00:00:00.000'){ echo 'なし'; }else{ echo substr($row['delivery_date'], 0, 10); } ?></td>
          </tr>
          <tr>
            <th class = "row" rowspan="2">申込内容</th>
            <th class = "row">種別</th>
            <td><?php echo $row['type'] ?></td>
          </tr>
          <tr>
            <th class = "row">詳細</th>
            <td><?php include('apply_detail_table.php'); ?></td>
          </tr>
        </tbody>
      </table>
    <?php } ?>
    <p class = "border" style = "margin:40px 0;"></p>
    <div class = "message-area">
      <p><b>申込問合せ</b></p><br>
      <?php if(isset($err_message)){ ?>
      <p class = "err_message"><?php echo $err_message; ?></p>
      <?php } ?>
      <?php if(empty($inquiry_chat)){ ?>
      <p>チャットはまだありません。</p>
      <?php
      } else {
        foreach($inquiry_chat as $row){
      ?>
        <div class = "message-box">
          <form action = "update_apply_inquiry_message.php" method = "post" name = "message_update<?php echo $row['message_no'] ?>" accept-charset="ASCII" enctype="multipart/form-data">
            <p style = "display:inline-block; margin:0;"><?php echo $row['name'] ?><?php if($row['deleted_flg'] == 1){ echo '（削除されたユーザー）'; } ?></p>
            <p style = "display:inline-block; color:#666666; margin-top:0; font-size:14px;"><?php echo substr($row['send_time'], 0, 16); ?></p>
            <?php if($_SESSION['USER_NO'] == $row['user_no']){ ?>
              <a class = "edit-button" id = "message-edit<?php echo $row['message_no'] ?>" onclick = "edit('<?php echo $row['message_no'] ?>')" style = "float:right;"><i class="material-icons">&#xE150;</i></a>
              <a class = "edit-button" id = "message-cansel<?php echo $row['message_no'] ?>" onclick = "cansel('<?php echo $row['message_no'] ?>')" style = "float:right;"><i class="material-icons">&#xE14C;</i></a>
              <a class = "edit-button" id = "message-save<?php echo $row['message_no'] ?>" onclick = "save('<?php echo $row['message_no'] ?>')" href = "javascript:message_update<?php echo $row['message_no'] ?>.submit()" style = "float:right;"><i class="material-icons">&#xE5CA;</i></a>
          　<?php } ?>
            <div style = "clear:float;"></div>
            <p style = "margin:0; white-space:pre-wrap;" id = "message-content-show<?php echo $row['message_no'] ?>"><?php echo $row['message'] ?></p>
            <textarea name = "message" class = "textarea" id = "message-content-edit<?php echo $row['message_no'] ?>" rows = "10" required maxlength = '2000'><?php echo $row['message']; ?></textarea>
            <input type = "hidden" name = "message_no" value = "<?php echo $row['message_no'] ?>">
            <input type = "hidden" name = "apply_id" value = "<?php echo $apply_id ?>">
            <input type = "hidden" name = "type" value = "<?php echo $_POST['type']; ?>">
            <input type = "hidden" name = "poster" value = "<?php echo $mail; ?>">
          </form>
        </div>
      <?php
        }
      }
      ?>
    </div>
    <?php if(isset($self_flg) || (isset($admin_flg) && $admin_flg == 1)){ ?>
      <p class = "border" style = "margin:40px 0;"></p>
      <div class = "reply-area">
        <form action = "send_apply_inquiry_chat.php" name = "chat_content" method = "post" accept-charset="ASCII" enctype="multipart/form-data">
          <?php foreach($applicate_info as $row){ ?>
            <p>返信<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></p>
            <textarea name="response" id = "response" placeholder = "ここにメッセージ内容を入力" rows="4" cols="40" class = "textarea" style = "display: block; margin-top:10px;" maxlength = '2000'></textarea>
            <p class = "validation"></p>
            <input type = "hidden" name = "apply_id" value = "<?php echo $row['apply_id']; ?>">
            <input type = "hidden" name = "poster" value = "<?php echo $row['mail']; ?>">
            <input type = "hidden" name = "type" value = "<?php echo $_POST['type']; ?>">
            <input type = "button" value = "送信" class = "button" onclick = "send_chat();">
          <?php } ?>
        </form>
      </div>
    <?php } ?>
  </div>
</main>
<?php
//申込情報の取得
function get_apply_data($pdo, $table){
  $apply_id = $_POST['apply_id'];

  $tsql = "select * from application where apply_id = ?";
  $result = $pdo->prepare($tsql);
  $param = array($apply_id);
  $result->execute($param);
  $applicate_info = $result -> fetchAll(PDO::FETCH_ASSOC);
  
  $tsql = "select * from $table where apply_id = ?";
  $result = $pdo->prepare($tsql);
  $param = array($apply_id);
  $result->execute($param);
  $apply_detail = $result -> fetchAll(PDO::FETCH_ASSOC);

  $tsql = "
  select * from inquiry_chat
  inner join user_info
  on inquiry_chat.user_no = user_info.user_no
  where inquiry_chat.inquiry_no = ?
  order by inquiry_chat.send_time;
  ";
  $result = $pdo->prepare($tsql);
  $params = array($apply_id);
  $result->execute($params);
  $inquiry_chat = $result -> fetchAll(PDO::FETCH_ASSOC);

  return [$applicate_info, $apply_detail, $inquiry_chat];
}
?>
<script type="text/javascript">
  $('#update_button').click(function(){
    if(window.confirm('ステータスを更新してもよろしいですか？\n更新した場合申込者に通知メールが送信されます。')){
      var f = document.getElementById('apply_status');
      if(f.elements["status"].value == '未対応' || f.elements["status"].value == '取り下げ'){
        alert('ステータス未対応、取り下げへの更新はできません。');
      }else{
        f.submit();
      }
    }
  });

  //申込取り下げリンク押下時
  function withdraw_apply(){
    if('<?php echo $status; ?>' != '未対応'){
      alert('対応中、対応済の申込は取り下げできません');
    }else{
      if(window.confirm('この申込を取り下げてもよろしいですか？')){
        var f = document.getElementById('withdraw_apply');
        f.submit();
      }
    }
  }

  //送信ボタン押下時の処理
  function send_chat() {
    //バリデーション
    var required_message = "※必須入力です";
    var item_list = ['response'];
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
      var f = document.forms["chat_content"];
      document.charset='ASCII';
      f.submit();
    }
  }

  //メッセージ編集ボタンの制御
  $(function() {
    var message_no_list = [];

    <?php foreach($inquiry_chat as $row ){ ?>
      message_no_list.push('<?php echo $row['message_no'] ?>');
    <?php } ?>;

    message_no_list.forEach(function(message_no){
      $('#message-cansel' + message_no).hide();
      $('#message-save' + message_no).hide();
      $('#message-content-edit' + message_no).hide();
      $('#input-file-message' + message_no).hide();
    });
  });
  function edit(message_no){
    $('#message-edit' + message_no).hide();
    $('#message-cansel' + message_no).show();
    $('#message-save' + message_no).show();
    $('#message-content-edit' + message_no).show();
    $('#message-content-show' + message_no).hide();
    $('#input-file-message' + message_no).show();
  }
  function cansel(message_no){
    $('#message-edit' + message_no).show();
    $('#message-cansel' + message_no).hide();
    $('#message-save' + message_no).hide();
    $('#message-content-edit' + message_no).hide();
    $('#message-content-show' + message_no).show();
    $('#input-file-message' + message_no).hide();
  }
  function save(message_no){
    $('#message-edit' + message_no).show();
    $('#message-cansel' + message_no).hide();
    $('#message-save' + message_no).hide();
    $('#message-content-edit' + message_no).hide();
    $('#message-content-show' + message_no).show();
    $('#input-file-message' + message_no).hide();
    var f = document.forms["message_update" + message_no];
    document.charset='ASCII';
    f.submit();
  }
</script>
<?php include('footer.php'); ?>