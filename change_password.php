<?php include('header.php'); ?>
<?php
$err_message = null;
if(isset($_POST['err_message'])){
  $err_message = $_POST['err_message'];
}
?>
<main>
  <div style = "padding: 10px;">
    <h2 class = "subheading">パスワード変更</h2>
    <p class = "err_message"><?php echo $err_message; ?></p>
    <form action = "change_password_confirm.php" method = "post">
      <p>現在のパスワード</p>
      <input type = "password" name = "current_pass" class = "textbox" required minlength = '6' maxlength = '12'>
      <p>新しいパスワード</p>
      <input type = "password" name = "new_pass" class = "textbox" required minlength = '6' maxlength = '12'>
      <p>新しいパスワード(確認用)</p>
      <input type = "password" name = "new_pass2" class = "textbox" required minlength = '6' maxlength = '12' style = "display:block;">
      <button type = "submit" class = "button" style = "display:inline-block;">次へ</button>
      <button type = "button" class = "button" onclick = "location.href='mypage.php'" style = "display:inline-block;">戻る</button>
    </form>
  </div>
</main>
<?php include('footer.php'); ?>
