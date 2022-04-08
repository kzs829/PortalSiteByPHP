<?php include('header.php'); ?>
<?php
  if(isset($_SESSION['USER_NO'])){
    $login_flg = 1;
  }
?>
<main>
  <div class = "table" style = "padding: 10px;">
    <h2 class = "subheading">公開中システムA</h2>
    <p>
      <button class = "button" style = "background-color:rgba(71, 87, 149, .8); color:white;" onclick = "location.href = 'grouping_download.php'"><span style = "font-size:18px;">説明資料ダウンロード</span></button>
    </p>
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
          <th class = "row">公開中ツールA-a</th>
          <td class = "txt">
            <a href="#">インストール手順</a><br>
          </td>
          <td class = "txt">
            <a href="#">操作手順</a><br>
            <a href="#">エラーコード</a><br>
          </td>
          <td class = "txt">
            <?php if(isset($login_flg)){ ?>
              <a href="#">Ver1.0.1.33</a>
            <?php }else{ ?>
              <a href="#" onclick = "jump_login();">Ver1.0</a>
            <?php } ?>
          </td>
          <td class = "txt">
            <a href="#">データ削除依頼書</a>
          </td>
        </tr>
        <tr>
          <th class = "row">公開中ツールA-b</th>
          <td class = "txt">-</td>
          <td class = "txt">-</td>
          <td class = "txt">
            <?php if(isset($login_flg)){ ?>
              <a href="#">Ver1.2.2.4</a>
            <?php }else{ ?>
              <a href="#" onclick = "jump_login();">Ver1.2</a>
            <?php } ?>
          </td>
          <td class = "txt">-</td>
        </tr>
        <tr>
          <th class = "row">公開中ツールA-c</th>
          <td class = "txt">-</td>
          <td class = "txt">-</td>
          <td class = "txt">
            <?php if(isset($login_flg)){ ?>
              <a href="#">Ver1.0.1.9</a>
            <?php }else{ ?>
              <a href="#" onclick = "jump_login();">Ver1.0</a>
            <?php } ?>
          </td>
          <td class = "txt">-</td>
        </tr>
      </tbody>
    </table>
    <div class = "attention">
      <p><i class="material-icons" style = "color: red;">&#xE002;</i><b>注意事項</b></p>
      <p>
      通信状態が悪いと通信が途中で切れる場合があります。(セッション切れ)
      </p>
    </div>
  </div>
  <form name = "to_login_form" action = "login.php" method = "post"></form>
</main>
<?php include('footer.php'); ?>