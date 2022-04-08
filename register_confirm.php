<?php include('header.php'); ?>
<?php
  $name = $_POST['name'];
  $mail = $_POST['mail'];
  $password = $_POST['password'];

  //DBとの接続
  list($pdo, $err_message, $bool) = connection_db_2();

  if($bool){
    //アカウントが既存か確認
    list($deleted_flg, $message, $bool) = confirm_exist($pdo);
  }
?>
<main>
  <div style = "padding: 10px; text-align:center;">
    <h2 class = "subheading-center">新規登録確認画面</h2>
    <div style = "width:400px; background-color:white; border-radius:5px; border:1px solid #ccc; margin:20px auto; padding:0px 30px; text-align:left;">
      <p style = "margin-top:20px;"><?php echo $message; ?></p>
      <?php if($deleted_flg == 1){ ?>
        <form action = "user_restoration.php" method = "post" style = "display:inline;">
      <?php }else{ ?>
        <form action = "user_insert_db.php" method = "post" style = "display:inline;">
      <?php } ?>
        <?php
        if(isset($_SESSION['ADMIN_FLG'])){
          if($_SESSION['ADMIN_FLG'] == 1){
        ?>
            <p>ユーザ権限</p>
            <input readonly type = "text" name = "admin_flg" class = "textbox" value = "管理者" style = "width:380px;">
        <?php
          }
        }
        ?>
        <p>名前</p>
        <input readonly type = "text" name = "name" class = "textbox" value = "<?php echo $name; ?>" style = "width:380px;">
        <p>メールアドレス</p>
        <input readonly type = "text" name = "mail" class = "textbox" value = "<?php echo $mail; ?>" style = "display:block; width:380px;">
        <input type = "hidden" name = "password" value = "<?php echo $password; ?>">
        <?php if($bool == FALSE){ ?>
          <button type = "submit" class = "long_button" style = "display:inline-block; margin-top:25px; margin-bottom:10px;" disabled>登録する</button>
        <?php
        }else{ 
          if($deleted_flg == 0){?>
            <button type = "submit" class = "long_button" style = "display:inline-block; margin-top:25px; margin-bottom:10px;">登録する</button> 
        <?php
          }else{
        ?>
            <button type = "submit" class = "long_button" style = "display:inline-block; margin-top:25px; margin-bottom:10px;">復活する</button> 
        <?php    
          }
        }
        ?>
      </form>
      <form action = "register.php?token=<?php echo $_POST['token'] ?>" method = "post" style = "display:inline;">
        <input type = "hidden" name = "name" value = "<?php echo $name; ?>">
        <input type = "hidden" name = "mail" value = "<?php echo $mail; ?>">
        <input type = "hidden" name = "password" value = "<?php echo $password; ?>">
        <button type = "submit" class = "long_button" style = "display:inline-block; background-color:#ccc; margin-top:0;">戻る</button>
      </form>
    </div>
  </div>
</main>
<?php
function confirm_exist($pdo){
  $mail = $_POST['mail'];

  $tsql = "SELECT * FROM user_info WHERE mail = ?;";
  $stmt = $pdo -> prepare($tsql);
  $params = array($mail);
  $stmt -> execute($params);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if(isset($result['mail'])){
    $deleted_flg = $result['deleted_flg'];
    if($deleted_flg == 0){
      $message = 'このメールアドレスは既に登録されています。';
      return [$deleted_flg, $message, FALSE];
    }else if($deleted_flg == 1){
      $message = 'このメールアドレスは以前登録されたことがあります。<br>アカウントを復活しますか？';
      return [$deleted_flg, $message, TRUE];
    }
  }
  $deleted_flg = 0;
  $message = 'こちらの内容で登録してもよろしいですか？';
  return [$deleted_flg, $message, TRUE];
  }
?>
<?php include('footer.php'); ?>