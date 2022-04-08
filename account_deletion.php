<?php include('header.php'); ?>
<?php
$err_message = null;
if(isset($_POST['err_message'])){
  $err_message = $_POST['err_message'];
}
?>
<main>
  <h2 class = "subheading">アカウントの削除</h2>
  <p class = "err_message"><?php echo $err_message; ?></p>
  <p>アカウントを削除します。<br>よろしいですか？<br>※削除ボタンをクリックすると削除が実行されます。</p>
  <form action = "delete_user.php" method = "post">
    <input type = "hidden" name = "user_no" value = "<?php echo $_SESSION['USER_NO']; ?>">
    <input type = "hidden" name = "admin_flg" value = "<?php echo $_SESSION['ADMIN_FLG']; ?>">
    <button type = "submit" class = "button" style = "display: inline-block; background-color: #ff1a1a;">削除</button>
    <button type = "button" class = "button" onclick="location.href='index.php'" style = "display: inline-block;">キャンセル</button>
  </form>
</main>
<?php include('footer.php'); ?>