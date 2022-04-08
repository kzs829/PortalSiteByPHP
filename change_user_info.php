<?php include('header.php'); ?>
<main>
  <div style = "padding: 10px;">
    <h2 class = "subheading">アカウント情報変更</h2>
    <p>変更したい内容を入力してください。</p>
    <form action = "change_userinfo_confirm.php" method = "post">
      <p>名前</p>
      <?php
      if(isset($_POST['name'])){
      ?>
        <input type = "text" name = "name" class = "textbox" value = "<?php echo $_POST['name']; ?>" required maxlength = '20'>
      <?php
      }else{
      ?>
        <input type = "text" name = "name" class = "textbox" value = "<?php echo $_SESSION['NAME']; ?>" required maxlength = '20'>
      <?php
      }
      ?>
      <p>メールアドレス</p>
      <?php
      if(isset($_POST['mail'])){
      ?>
        <input type = "email" name = "mail" class = "textbox" value = "<?php echo $_POST['mail']; ?>" required maxlength = '60' style = "display:block;">
      <?php
      }else{
      ?>
        <input type = "email" name = "mail" class = "textbox" value = "<?php echo $_SESSION['MAIL']; ?>" required maxlength = '60' style = "display:block;">
      <?php
      }
      ?>      
      <button type = "submit" class = "button" style = "display:inline-block;">次へ</button>
      <button type = "button" class = "button" onclick = "location.href='mypage.php'" style = "display:inline-block;">戻る</button>
    </form>
  </div>
</main>
<?php include('footer.php'); ?>
