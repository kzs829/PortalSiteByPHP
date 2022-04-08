<?php include('header.php'); ?>
<?php
$err_message = null;
if(isset($_POST['err_message'])){
  $err_message = $_POST['err_message'];
}
?>
<main>
  <div style = "padding: 10px; text-align:center;">
    <h2 class = "subheading-center">仮登録画面</h2>
    <p class = "err_message"><?php echo $err_message; ?></p>
    <div style = "width:400px; background-color:white; border-radius:5px; border:1px solid #ccc; margin:20px auto; padding:0px 30px; text-align:left;">
      <p style = "margin-top:20px;">
        登録ボタンをクリックすると入力されたメールアドレスにメールが送信されます。<br>
        メールに記載のURLから本登録を実施してください。
      </p>
      <form action = "pre_user_insert_db.php" method = "post" name = "pre_register_content">
        <dl>
          <dt>メールアドレス　<span class = "supplement">30文字以内</span></dt>
          <dd>
            <input type = "email" id = "mail" name = "mail" class = "textbox" required maxlength = '30' style = "width:380px;">
            <p class = "validation"></p>
          </dd>
        </dl>
        <button type = "button" class = "long_button" onclick = "post_pre_register();">登録</button>
      </form>
    </div>
    <?php if(!(isset($_SESSION['MAIL']))){ ?>
      <a href = "login.php">ログイン画面へ戻る</a>
    <?php } ?>
  </div>
</main>
<script type="text/javascript">
function post_pre_register() {
  //バリデーション
  var mail_message = '※正しいメールアドレスの形式で入力してください';
  var required_message = "※必須入力です";
  var mail_check = "[^A-Za-z0-9@.!#$%&'*+\/=?^_`{|}~-]";
  var bool;
  var err_list = [];

  bool = validate($('#mail'),mail_message,mail_check);
  if(bool == false){
    err_list.push(1);
  }else{
    bool = vali_mail_atmark($('#mail'));
    if(bool == false){
      err_list.push(1);
    }
  }

  if(err_list.length == 0){
    var f = document.forms["pre_register_content"];
    f.submit();
  }
}
</script>
<?php include('footer.php'); ?>
