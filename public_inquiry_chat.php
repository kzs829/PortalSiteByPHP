<?php include('header.php'); ?>
<?php
$inquiry_no = $_POST['inquiry_no'];
//DB接続
list($pdo, $err_message, $bool) = connection_db_2();
if($bool){
  //問い合わせ情報とチャット情報を取得
  list($inquiry_chat, $inquiry, $inquiry_file, $chat_files) = get_inquiry_chat($pdo, $inquiry_no);
}

if(isset($_POST['err_message'])){
  $err_message = $_POST['err_message'];
}
?>
<main>
  <div style = "padding: 10px;">
    <?php if(isset($err_message)){ ?>
      <p class = "err_message"><?php echo $err_message; ?></p>
    <?php } ?>
    <div class = "info-area">
      <?php foreach($inquiry as $row){ ?>
        <p style = "color:#666666; margin-bottom:10px; font-size:14px;"><?php echo substr($row['create_time'], 0, 16); ?></p>
        <p style = "display:inline-block; margin-bottom:0; font-size:12px;">題名</p>
        <p id = "inquiry-subject-show" style = "margin-top:0; margin-bottom:10px;"><b><?php echo $row['subject']; ?></b></p>
        <p style = "margin-bottom:0; font-size:12px;">説明</p>
        <p id = "inquiry-body-show" style = "margin:0;"><?php echo $row['body']; ?></p>
      <?php } ?>
      <?php if($inquiry_file){ ?>
        <p style = "margin-bottom:0; margin-top:10px; font-size:12px;">添付ファイル</p>
        <?php foreach($inquiry_file as $row){ ?>
          <a style = "display:block;" href = "<?php echo $row['file_path'] ?>" download = "<?php echo $row['file_name'] ?>"><?php echo $row['file_name'] ?></a>
        <?php } ?>
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
            <?php if($row['admin_flg'] == 0){ ?>
              <p>ユーザー</p>
            <?php }else{ ?>
              <p>管理者</p>
            <?php } ?>
            <p><?php echo $row['message'] ?></p>
            <?php if($chat_files[$row['message_no']]){ ?>
              <p style = "margin-bottom:0; margin-top:10px; font-size:12px;">添付ファイル</p>
              <?php foreach($chat_files[$row['message_no']] as $file){ ?>
                <a style = "display:block;" href = "<?php echo $file['file_path'] ?>" download = "<?php echo $file['file_name'] ?>"><?php echo $file['file_name'] ?></a>
              <?php } ?>
            <?php } ?>
          </div>
      <?php
        }
      }
      ?>
    </div>
  </div>
</main>
<?php
//チャット情報の取得
function get_inquiry_chat($pdo, $inquiry_no){
  $tsql = "
  select * from inquiry_chat
  inner join user_info
  on inquiry_chat.user_no = user_info.user_no
  where inquiry_chat.inquiry_no = ?;
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
  where inquiry_no = ?;
  ";
  $result = $pdo->prepare($tsql);
  $params = array($inquiry_no);
  $result->execute($params);
  $inquiry_file = $result -> fetchAll(PDO::FETCH_ASSOC);

  $chat_files = [];
  foreach($inquiry_chat as $row){
    $tsql = "
    select * from inquiry_file
    where inquiry_no = ?;
    ";
    $result = $pdo->prepare($tsql);
    $params = array($row['message_no']);
    $result->execute($params);
    $chat_file = $result -> fetchAll(PDO::FETCH_ASSOC);

    $chat_files[$row['message_no']] = $chat_file;
  }

  return [$inquiry_chat, $inquiry, $inquiry_file, $chat_files];
}
?>
<?php include('footer.php'); ?>