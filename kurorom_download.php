<?php include('header.php'); ?>
<?php
  if(isset($_SESSION['USER_NO'])){
    $login_flg = 1;
  }
?>
<main>
  <div class = "table" style = "padding: 10px;">
    <h2 class = "subheading" style = "margin-bottom:30px;">公開中ツールC</h2>
    <table>
      <thead>
        <tr>
          <td class = "non"></td>
          <th class= "col">インストール</th>
          <th class= "col">操作手順(エラーコード表)</th>
          <th class= "col">ツール本体</th>
          <th class= "col">その他 申請書</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class = "row">公開中ツールC(大阪)</th>
          <td class = "txt" rowspan = "2">
            <a href="#">インストール手順</a>
          </td>
          <td class = "txt" rowspan = "2">
            <a href="#">操作手順</a><br>
            <a href="#">エラーコード</a>
          </td>
          <td class = "txt">
            <?php if(isset($login_flg)){ ?>
              <a href="#">Ver3.0 大阪版</a><br>
            <?php }else{ ?>
              <a href="#" onclick = "jump_login();">Ver3.0 大阪版</a><br>
            <?php } ?>
          </td>
          <td class = "txt" rowspan = "2">
            <a href="#">マスタ登録依頼書</a>
          </td>
        </tr>
        <tr>
          <th class = "row">公開中ツールC(東京)</th>
          <td class = "txt">
            <?php if(isset($login_flg)){ ?>
              <a href="#">Ver3.0  東京版</a><br>
            <?php }else{ ?>
              <a href="#" onclick = "jump_login();">Ver3.0 東京版</a><br>
            <?php } ?>
          </td>
        </tr>
      </tbody>
    </table>
    <div class = "attention">
      <p><i class="material-icons" style = "color: red;">&#xE002;</i><b>注意事項</b></p>
      <p>通信状態が悪いと通信が途中で切れる場合があります。(セッション切れ)</p>
    </div>
  </div>
  <form name = "to_login_form" action = "login.php" method = "post"></form>
</main>
<?php include('footer.php'); ?>
