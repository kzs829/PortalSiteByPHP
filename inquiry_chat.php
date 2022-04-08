<?php include('header.php'); ?>
<?php
$inquiry_no = $_POST['inquiry_no'];
if(isset($_POST['err_message'])){
  $err_message = $_POST['err_message'];
}

//DB接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  //問い合わせ情報とチャット情報を取得
  list($inquiry_chat, $inquiry, $inquiry_file, $chat_files, $cc) = get_inquiry_chat($pdo, $inquiry_no);
}

$cc = $cc['cc'];
$cc_list =  preg_split('/,/', $cc);

foreach($inquiry as $row){
  $user_no = $row['user_no'];
  $name = $row['name'];
  $mail = $row['mail'];
}
?>
<main>
  <div style = "padding: 10px;">
    <?php if(isset($err_message)){ ?>
      <p class = "err_message"><?php echo $err_message; ?></p>
    <?php } ?>
    <div class = "info-area">
      <form action = "update_inquiry.php" method = "post" name = "inquiry_edit" accept-charset="ASCII" enctype="multipart/form-data">
        <p style = "display:inline-block; margin:0;"><?php echo $row['name'] ?><?php if($row['deleted_flg'] == 1){ echo '（削除されたユーザー）'; } ?></p>
        <p style = "display:inline-block; color:#666666; margin:0; font-size:14px;"><?php echo substr($row['create_time'], 0, 16); ?></p>
        <?php foreach($inquiry as $row){ ?>
          <?php if($_SESSION['USER_NO'] == $user_no){ ?>
            <a class = "edit-button" id = "inquiry-edit" style = "float:right; margin-top:8px;"><i class="material-icons">&#xE150;</i></a>
            <a class = "edit-button" id = "inquiry-cancel" style = "float:right; margin-top:8px;"><i class="material-icons">&#xE14C;</i></a>
            <a class = "edit-button" id = "inquiry-save" onclick = "update_inquiry();" style = "float:right; margin-top:8px;">
              <i class="material-icons">&#xE5CA;</i>
            </a>
          <?php } ?>
          <p style = "margin-bottom:0; font-size:12px;">題名</p>
          <div style = "clear:float;"></div>
          <p id = "inquiry-subject-show" style = "margin-bottom:0; margin-top:0;"><?php echo $row['subject']; ?></p>
          <input type = "text" name = "subject" class = "textbox" id = "inquiry-subject-edit" value = "<?php echo $row['subject']; ?>" required maxlength = '60'>
          <p style = "margin-bottom:0; font-size:12px;">説明</p>
          <p id = "inquiry-body-show" style = "margin:0; white-space:pre-wrap;"><?php echo $row['body']; ?></p>
          <textarea name = "body" class = "textarea" id = "inquiry-body-edit" rows = "10" required maxlength = '2000'><?php echo $row['body']; ?></textarea>
          <input type = "hidden" name = "inquiry_no" value = "<?php echo $row['inquiry_no']; ?>">
        <?php } ?>
        <p style = "margin-bottom:0; margin-top:10px; font-size:12px;">添付ファイル</p>
        <?php if($inquiry_file){ ?>
          <?php foreach($inquiry_file as $row){ ?>
            <a href = "<?php echo $row['file_path'] ?>" download = "<?php echo $row['file_name'] ?>"><?php echo $row['file_name'] ?></a>
            <?php if($_SESSION['NAME'] == $name){ ?>
              <a class = "edit-button" href = "javascript:delete_file<?php echo $row['file_no']; ?>.submit()"><i class="material-icons">&#xE14C;</i></a>
            <?php } ?>
            <p style = "margin:0;"></p>
          <?php } ?>
        <?php }else{ ?>
          <p style = "margin:0;">なし</p>
        <?php } ?>
        <input id = "input-file" type="file" name="upfile[]" multiple="multiple" style = "display:block; margin-top:5px;">
        <p style = "margin-bottom:0; margin-top:10px; font-size:12px;">Cc</p>
        <?php if($cc){
          foreach($cc_list as $val){ ?>
          <span style = "background-color:rgb(131, 133, 139); color:white; border-radius:10px; padding:2px 8px; margin-right:5px; display:inline-block;"><?php echo $val;?></span>
        <?php }
        }else{ ?>
          <p style = "margin:0;">なし</p>
        <?php } ?>
      </form>
      <?php foreach($inquiry_file as $row){ ?>
        <form action = "delete_inquiryfile.php" name = "delete_file<?php echo $row['file_no']; ?>" method = "post">
          <input type = "hidden" value = "<?php echo $inquiry_no; ?>" name = "inquiry_no">
          <input type = "hidden" value = "<?php echo $row['file_no'] ?>" name = "file_no">
        </form>
      <?php } ?>
    </div>
    <div class = "message-area">
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
          <form action = "update_inquiry_message.php" method = "post" name = "message_update<?php echo $row['message_no'] ?>" accept-charset="ASCII" enctype="multipart/form-data">
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
            <?php if($chat_files[$row['message_no']]){ ?>
              <p style = "margin-bottom:0; margin-top:10px; font-size:12px;">添付ファイル</p>
              <?php foreach($chat_files[$row['message_no']] as $file){ ?>
                <a href = "<?php echo $file['file_path'] ?>" download = "<?php echo $file['file_name'] ?>"><?php echo $file['file_name'] ?></a>
                <?php if($row['user_no'] == $_SESSION['USER_NO']){ ?>
                  <a class = "edit-button" href = "javascript:delete_file<?php echo $file['file_no']; ?>.submit()"><i class="material-icons">&#xE14C;</i></a>
                <?php } ?>
                <p style = "margin:0;"></p>
              <?php } ?>
            <?php } ?>
            <input id = "input-file-message<?php echo $row['message_no'] ?>" type="file" name="upfile[]" multiple="multiple" style = "display:block; margin-top:10px;">
            <input type = "hidden" name = "message_no" value = "<?php echo $row['message_no'] ?>">
            <input type = "hidden" name = "inquiry_no" value = "<?php echo $row['inquiry_no'] ?>">
            <input type = "hidden" name = "cc" value = "<?php echo $cc; ?>">
            <input type = "hidden" name = "poster" value = "<?php echo $mail; ?>">
          </form>
          <?php foreach($chat_files[$row['message_no']] as $file){ ?>
            <form action = "delete_inquiryfile.php" name = "delete_file<?php echo $file['file_no']; ?>" method = "post">
              <input type = "hidden" value = "<?php echo $inquiry_no; ?>" name = "inquiry_no">
              <input type = "hidden" value = "<?php echo $file['file_no'] ?>" name = "file_no">
            </form>
          <?php } ?>
        </div>
      <?php
        }
      }
      ?>
    </div>
    <p class = "border" style = "margin:40px 0;"></p>
    <div class = "reply-area">
    <form action = "send_inquiry_chat.php" name = "chat_content" method = "post" accept-charset="ASCII" enctype="multipart/form-data">
      <?php foreach($inquiry as $row){ ?>
        <p>ステータス</p>
        <select name = "status" class = "select-box">
          <option hidden><?php echo $row['status']; ?></option>
          <option value = "未対応">未対応</option>
          <option value = "対応中">対応中</option>
          <option value = "対応済">対応済</option>
        </select>
        <p>返信<span class = "label" title = "必須" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">必須</span></p>
        <textarea name="response" id = "response" placeholder = "ここにメッセージ内容を入力" rows="4" cols="40" class = "textarea" style = "display: block; margin-top:10px;" maxlength = '2000'></textarea>
        <p class = "validation"></p>
        <p>添付ファイル<span class = "label" title = "任意" style = "margin-left:3px; border-radius:0px; padding:1px 5px;">任意</span></p>
        <input type="file" name="upfile[]" multiple="multiple">
        <input type = "hidden" name = "inquiry_no" value = "<?php echo $inquiry_no; ?>">
        <input type = "hidden" name = "cc" value = "<?php echo $cc; ?>">
        <input type = "hidden" name = "poster" value = "<?php echo $row['mail']; ?>">
        <input type = "button" value = "送信" class = "button" onclick = "send_chat();">
      <?php } ?>
    </form>
    <?php if($_SESSION['ADMIN_FLG'] == 1){ ?>
      <a href = "javascript:delete_confirm();" style = "color:red;">このお問合せを削除する</a>
    <?php } ?>
    </div>
  </div>
</main>
<?php
//チャット情報の取得
function get_inquiry_chat($pdo, $inquiry_no){
  //cc取得
  $tsql = "
  select cc from inquiry
  where inquiry_no = ?;
  ";
  $result = $pdo->prepare($tsql);
  $params = array($inquiry_no);
  $result->execute($params);
  $cc = $result -> fetch(PDO::FETCH_ASSOC);

  $tsql = "
  select * from inquiry_chat
  inner join user_info
  on inquiry_chat.user_no = user_info.user_no
  where inquiry_chat.inquiry_no = ?
  order by inquiry_chat.send_time;
  ";
  $result = $pdo->prepare($tsql);
  $params = array($inquiry_no);
  $result->execute($params);
  $inquiry_chat = $result -> fetchAll(PDO::FETCH_ASSOC);

  $tsql = "
  select * from inquiry
  inner join user_info
  on inquiry.user_no = user_info.user_no
  where inquiry.inquiry_no = ?;
  ";
  $result = $pdo->prepare($tsql);
  $params = array($inquiry_no);
  $result->execute($params);
  $inquiry = $result -> fetchAll(PDO::FETCH_ASSOC);

  $tsql = "
  select * from inquiry_file
  where inquiry_no = ?
  AND deleted_flg = 0;
  ";
  $result = $pdo->prepare($tsql);
  $params = array($inquiry_no);
  $result->execute($params);
  $inquiry_file = $result -> fetchAll(PDO::FETCH_ASSOC);

  $chat_files = [];
  foreach($inquiry_chat as $row){
    $tsql = "
    select * from inquiry_file
    where inquiry_no = ?
    AND deleted_flg = 0;
    ";
    $result = $pdo->prepare($tsql);
    $params = array($row['message_no']);
    $result->execute($params);
    $chat_file = $result -> fetchAll(PDO::FETCH_ASSOC);

    $chat_files[$row['message_no']] = $chat_file;
  }

  return [$inquiry_chat, $inquiry, $inquiry_file, $chat_files, $cc];
}
?>
<script type="text/javascript">
//問合せ内容編集ボタンの制御
$(function(){
  $('#inquiry-cancel').hide();
  $('#inquiry-save').hide();
  $('#inquiry-body-edit').hide();
  $('#inquiry-subject-edit').hide();
  $('#input-file').hide();
  $('#inquiry-edit').on('click', function(){
    $('#inquiry-edit').hide();
    $('#inquiry-cancel').show();
    $('#inquiry-save').show();
    $('#inquiry-body-edit').show();
    $('#inquiry-body-show').hide();
    $('#inquiry-subject-show').hide();
    $('#inquiry-subject-edit').show();
    $('#input-file').show();
  });
  $('#inquiry-cancel, #inquiry-save').on('click', function(){
    $('#inquiry-edit').show();
    $('#inquiry-cancel').hide();
    $('#inquiry-save').hide();
    $('#inquiry-body-edit').hide();
    $('#inquiry-body-show').show();
    $('#inquiry-subject-show').show();
    $('#inquiry-subject-edit').hide();
    $('#input-file').hide();
  });
});

//問合せ内容更新
function update_inquiry(){
  var f = document.forms["inquiry_edit"];
  document.charset='ASCII';
  f.submit();
}

//お問合せ削除ボタン押下時の処理
function delete_confirm(){
  if(window.confirm('お問い合わせを削除してもよろしいですか？')){
    location.href = "delete_inquiry.php?inquiry_no=<?php echo $inquiry_no; ?>";
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
</script>
<?php include('footer.php'); ?>