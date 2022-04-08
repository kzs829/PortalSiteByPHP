<?php include('header.php'); ?>
<main>
  <div class = "application" style = "padding: 10px;">
    <h3 class = "subheading">各種申込</h3>
    <p>ツールAのマスタテーブルの新規/変更/削除の申込</p>
    <button type = "button" class = "button-app" onclick="location.href='kurorom_apply_form.php'">ツールAマスタ<br>登録申込</button>
    <p>RedmineIDの新規/削除の申込</p>
    <button type = "button" class = "button-app" onclick="location.href='redmine_apply_form.php'">RedmineID<br>登録申込</button>
    <p>申込状況の確認</p>
    <button type = "button" class = "button-app" onclick="location.href='apply_list.php'">申込一覧</button>
  </div>
</main>
<?php include('footer.php'); ?>
