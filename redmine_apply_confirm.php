<?php include('header.php');?>
<?php

//DBとの接続
list($pdo, $err_message, $bool) = connection_db_2();

$json_app_details = $_POST['json_app_details'];
$json_applicant_info = $_POST['json_applicant_info'];
$uuid = $_POST['uuid'];

$app_details = json_decode( $json_app_details , true );
$applicant_info = json_decode( $json_applicant_info , true );
?>
<main>
  <div style = "padding: 10px;">
    <h2 class = "subheading">RedmineID登録依頼確認</h2>
    <dl style = "margin:20px 0;">
      <dt>申請部門</dt>
      <dd><input type = "text" class = "textbox" value = '<?php echo $applicant_info[0]; ?>' readonly></dd>
      <dt>申請者氏名</dt>
      <dd><input type = "text" class = "textbox" value = '<?php echo $applicant_info[1]; ?>' readonly></dd>
      <dt>申請者メールアドレス</dt>
      <dd><input type = "text" class = "textbox" value = '<?php echo $applicant_info[2]; ?>' readonly></dd>
      <dt>希望納期</dt>
      <dd><input type = "text" class = "textbox" value = '<?php if($applicant_info[3]){echo $applicant_info[3];}else{echo 'なし';} ?>' readonly></dd>
    </dl>
    <table>
      <thead>
        <tr>
          <th class = "col" style = "width:100px;">区分</th>
          <th class = "col" style = "width:100px;">姓</th>
          <th class = "col" style = "width:100px;">名</th>
          <th class = "col" style = "width:200px;">登録ID(メールアドレス)</th>
          <th class = "col" style = "width:200px;">備考</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($app_details as $row){ ?>
          <tr>
            <td class = "txt"><?php echo $row[0]; ?></td>
            <td class = "txt"><?php echo $row[1]; ?></td>
            <td class = "txt"><?php echo $row[2]; ?></td>
            <td class = "txt"><?php echo $row[3]; ?></td>
            <td class = "txt"><?php echo $row[4]; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <form action = "redmine_apply_insert.php" method = "post" style = "display:inline">
      <input type = "hidden" name = "json_app_details" value = '<?php echo $json_app_details; ?>'>
      <input type = "hidden" name = "json_applicant_info" value = '<?php echo $json_applicant_info; ?>'>
      <input type = "hidden" name = "uuid" value = '<?php echo $uuid; ?>'>
      <input type = "submit" value = "申し込む" class = "button" style = "display:inline-block;">
    </form>
    <form action = "redmine_apply_form.php" method = "post" style = "display:inline">
      <input type = "hidden" name = "json_app_details" value = '<?php echo $json_app_details; ?>'>
      <input type = "hidden" name = "json_applicant_info" value = '<?php echo $json_applicant_info; ?>'>
      <input type = "submit" value = "修正する" class = "button" style = "display:inline-block;">
    </form>
  </div>
</main>
<?php include('footer.php');?>
