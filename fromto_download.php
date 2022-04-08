<?php include('header.php'); ?>
<?php
  if(isset($_SESSION['USER_NO'])){
    $login_flg = 1;
  }
?>
<main>
  <div class = "table" style = "padding: 10px;">
    <h2 class = "subheading">公開中ツールB</h2>
    <table>
      <thead>
        <tr>
          <td class = "non"></td>
          <th class= "col">インストール</th>
          <th class= "col">操作手順</th>
          <th class = "col">エラーコード表</th>
          <th class= "col">ツール本体</th>
          <th class= "col">その他 申請書</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class = "row">公開中ツールB PC版</th>
          <td class = "txt" rowspan = "2">
            <a href="#">インストール手順</a>
          </td>
          <td class = "txt" rowspan = "2">
            <a href="#">操作手順</a>
          </td>
          <td class = "txt" rowspan = "2">
            <a href="#">エラーコード</a>
          </td>
          <td class = "txt">
            <?php if(isset($login_flg)){ ?>
              <a href="#">Ver2.0.0.7</a><br>
            <?php }else{ ?>
              <a href="#" onclick = "jump_login();">Ver2.0</a><br>
            <?php } ?>
          </td>
          <td class = "txt">
            <a href="#">追加・削除依頼書</a><br>
          </td>
        </tr>
        <tr>
          <th class = "row">公開中ツールB HT版</th>
          <td class = "txt">
            <?php if(isset($login_flg)){ ?>
              <a href="#">Ver2.0</a><br>
            <?php }else{ ?>
              <a href="#" onclick = "jump_login();">Ver2.0</a><br>
            <?php } ?>
          </td>
          <td class = "txt">-</td>
        </tr>
        <tr>
          <th class = "row">公開中ツールB-a</th>
          <td class = "txt" rowspan = "5">
            <a href="#">インストール手順</a>
          </td>
          <td class = "txt" rowspan = "2">
            <a href="#">操作手順</a>
          </td>
          <td class = "txt" rowspan = "3">
            <a href="#">エラーコード</a>
          </td>
          <td class = "txt">
            <?php if(isset($login_flg)){ ?>
              <a href="#">Ver2.0</a><br>
            <?php }else{ ?>
              <a href="#" onclick = "jump_login();">Ver2.0</a><br>
            <?php } ?>
          </td>
          <td class = "txt">-</td>
        </tr>
        <tr>
          <th class = "row">公開中ツールB-b</th>
          <td class = "txt">
            <?php if(isset($login_flg)){ ?>
              <a href="#">Ver2.0</a><br>
            <?php }else{ ?>
              <a href="#" onclick = "jump_login();">Ver2.0</a><br>
            <?php } ?>
          </td>
          <td class = "txt">-</td>
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