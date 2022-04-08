<?php include('header.php'); ?>
<?php
$err_message = null;
if(isset($_POST['err_message'])){
  $err_message = $_POST['err_message'];
}
?>
<main>
  <div class = "login" style = "padding: 10px; text-align:center;">
    <h2 class = "subheading-center">ログイン画面</h2>
    <p class = "err_message"><?php echo $err_message; ?></p>
    <form action = "login_func.php" method = "post" name = "login_content">
      <div style = "width:400px; background-color:white; border-radius:5px; border:1px solid #ccc; margin:20px auto; padding:0px 30px; text-align:left;">
        <dl>
          <dt>メールアドレス　<span class = "supplement">30文字以内</span></dt>
          <dd>
            <input type = "email" id = "mail" name = "mail" class = "textbox" required maxlength = '30' style = "width:380px;">
            <p class = "validation"></p>
          </dd>
          <dt>パスワード　<span class = "supplement">6文字以上12文字以内</span></dt>
          <dd>
            <input type = "password" id = "password" name = "password" class = "textbox" required minlength = '6' maxlength = '12' style = "width:380px;">
            <p class = "validation"></p>
          </dd>
        </dl>
        <button type = "button" class = "long_button" onclick = "post_login_data();">ログイン</button>
      </div>
    </form>
    <a href = "pre_register.php">登録がお済みでない方はこちら</a>
  </div>
</main>
<script type="text/javascript">
function post_login_data() {
  //バリデーション
  var mail_message = '※正しいメールアドレスの形式で入力してください';
  var required_message = "※必須入力です";
  var password_length_message = "※パスワードは6文字以上12文字以下で入力してください。"
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

  if(!$('#password').val()){
    vali_err('password', required_message);
    err_list.push(1);
  }else if($('#password').val().length < 6){
    vali_err('password', password_length_message);
    err_list.push(1);
  }else{
    vali_no_err('password');
  }

  if(err_list.length == 0){
    var f = document.forms["login_content"];
    f.submit();
  }
}
</script>
<?php include('footer.php'); ?>
