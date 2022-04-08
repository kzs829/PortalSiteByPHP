<?php include('header.php'); ?>
<?php
$err_message = null;
if(isset($_POST['err_message'])){
  $err_message = $_POST['err_message'];
}

if($_GET['admin_flg'] == 1){
  $_SESSION['ADMIN_FLG'] = 1;
}

//DBとの接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  //トークンが有効か確認
  list($err_message, $result, $bool) = confirm_token($pdo);
}

if($bool == false){
  $alert = "<script type='text/javascript'>alert('URLの有効期限が切れています。再度仮登録を行ってください。');</script>";
  echo $alert;
?>
  <form action = "pre_register.php" method = "post" name = "post"></form>
<?php
  exit;
}
?>    
<main>
  <div style = "padding: 10px; text-align:center;">
    <h2 class = "subheading-center">新規登録画面</h2>
    <p class = "err_message"><?php echo $err_message; ?></p>
    <form action = "register_confirm.php" method = "post" name = "register_content">
      <div style = "width:400px; background-color:white; border-radius:5px; border:1px solid #ccc; margin:20px auto; padding:0px 30px; text-align:left;">
        <?php
        if(isset($_SESSION['ADMIN_FLG'])){
          if($_SESSION['ADMIN_FLG'] == 1){
        ?>
            <p>ユーザ権限</p>
            <input readonly type = "text" name = "admin_flg" class = "textbox" value = "管理者">
        <?php
          }
        }
        ?>
        <dl>
          <dt>名前　<span class = "supplement">10文字以内</span></dt>
          <dd>
            <input type = "text" id = "name" name = "name" class = "textbox" value = "<?php if(isset($_POST['name'])){ echo $_POST['name']; } ?>" required maxlength = '10' style = "width:380px;">
            <p class = "validation"></p>
        </dd>
          <dt>メールアドレス　<span class = "supplement">30文字以内</span></dt>
          <dd>
            <input type = "email" id = "mail" name = "mail" class = "textbox" value = "<?php echo $result['mail']; ?>" readonly style = "width:380px;">
            <p class = "validation"></p>
        </dd>
          <dt>パスワード　<span class = "supplement">6文字以上12文字以内</span></dt>
          <dd>
            <input type = "password" id = "password" name = "password" class = "textbox" required minlength = '6' maxlength = '12' style = "width:380px;">
            <p class = "validation"></p>
          </dd>   
        </dl>
        <input type = "hidden" name = "token" value = "<?php echo $_GET['token']; ?>">
        <button type = "button" class = "long_button" onclick = "post_register_content();">次へ</button>
      </div>
    </form>
    <?php
    if(!(isset($_SESSION['ADMIN_FLG']))){
    ?>
    <?php
    }
    ?>
  </div>
</main>
<?php
function confirm_token($pdo){
  $err_message = null;
  $token = $_GET['token'];
  $tsql = "SELECT * FROM pre_register WHERE token = ? AND date + 1 > getdate();";
  $stmt = $pdo -> prepare($tsql);
  $params = array($token);
  $stmt -> execute($params);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if(isset($result['mail'])){
    if($result['invalid_flg'] == 1){
      $err_message = 'URLの有効期限が切れています。再度仮登録を行ってください。';
      return [$err_message, $result, false];
    }
    return [$err_message, $result, true];
  }else{
    $err_message = 'URLの有効期限が切れています。再度仮登録を行ってください。';
    return [$err_message, $result, false];
  }
}
?>
<script type="text/javascript">
function post_register_content() {
  //バリデーション
  var mail_message = '※正しいメールアドレスの形式で入力してください';
  var required_message = "※必須入力です";
  var password_length_message = "※パスワードは6文字以上12文字以下で入力してください。"
  var mail_check = "[^A-Za-z0-9@.!#$%&'*+\/=?^_`{|}~-]";
  var bool;
  var err_list = [];

  if(!$('#name').val()){
    vali_err('name', required_message);
    err_list.push(1);
  }else{
    vali_no_err('name');
  }

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
    var f = document.forms["register_content"];
    f.submit();
  }
}
</script>
<?php include('footer.php'); ?>