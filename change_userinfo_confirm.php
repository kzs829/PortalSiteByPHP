<?php include('header.php'); ?>
<?php
  $name = $_POST['name'];
  $mail = $_POST['mail'];
?>
<main>
  <div style = "padding: 10px;">
    <h2 class = "subheading">アカウント情報変更確認</h2>
    <p>こちらの内容で変更してもよろしいでしょうか。</p>
    <form action = "update_user_info.php" method = "post" style = "display:inline;">
      <p>名前</p>
      <input readonly type = "text" name = "name" class = "textbox" value = "<?php echo $name; ?>">
      <p>メールアドレス</p>
      <input readonly type = "text" name = "mail" class = "textbox" value = "<?php echo $mail; ?>" style = "display:block;">
      <button type = "submit" class = "button" style = "display:inline-block;">変更する</button>
    </form>
    <form action = "change_user_info.php" method = "post" style = "display:inline-block;">
      <input type = "hidden" name = "name" class = "textbox" value = "<?php echo $name; ?>">
      <input type = "hidden" name = "mail" class = "textbox" value = "<?php echo $mail; ?>">
      <button type = "submit" class = "button" style = "display:inline-block;">戻る</button>
    </form>
  </div>
</main>
<?php include('footer.php'); ?>
