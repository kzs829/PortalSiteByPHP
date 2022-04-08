<?php include('header.php'); ?>
<?php
//DB接続
list($pdo, $err_message, $bool) = connection_db_2();

if($bool){
  $user_info = get_user_data($pdo);
}
?>
<main>
  <div style = "padding: 10px;">
    <?php if(isset($err_message)){ ?>
      <p class = "err_message"><?php echo $err_message; ?></p>
    <?php } ?>
    <a href = "user_list.php">ユーザー一覧へ</a>
    <?php foreach($user_info as $row){ ?>
      <div style = "margin-top:30px;">
        <a class = "edit-button" id = "edit" style = "margin-top:8px;"><i class="material-icons">&#xE150;</i></a>
        <a class = "edit-button" id = "cancel" style = "margin-top:8px;"><i class="material-icons">&#xE14C;</i></a>
        <a class = "edit-button" id = "save" href = "javascript:userinfo_edit.submit()" style = "margin-top:8px;">
          <i class="material-icons">&#xE5CA;</i>
        </a>
      </div>
      <form action = "update_userinfo_byadmin.php" name = "userinfo_edit" method = "post">
        <input type = "hidden" name = "user_no" value = "<?php echo $row['user_no']; ?>">
        <table>
          <tbody>
            <tr>
              <th class = "row">名前</th>
              <td><?php echo $row['name']; ?></td>
            </tr>
            <tr>
              <th class = "row">メールアドレス</th>
              <td><?php echo $row['mail']; ?></td>
            </tr>
            <tr>
              <th class = "row">権限</th>
              <td>
                <p id = "authority_show"><?php if($row['admin_flg'] == 0){ ?>一般<?php }else{ ?>管理者<?php } ?></p>
                <select name = "authority" class = "select-box" id = "authority_edit">
                  <option hidden class = "select-item" value = "<?php if($row['admin_flg'] == 0){ echo '一般'; }else{ echo '管理者'; }?>">
                    <?php if($row['admin_flg'] == 0){ echo '一般'; }else{ echo '管理者'; }?>
                  </option>
                　<option class = "select-item" value = "一般">一般</option>
                  <option class = "select-item" value = "管理者">管理者</option>
                </select>
              </td>
            </tr>
            <tr>
              <th class = "row">備考</th>
              <td>
                <?php if($row['deleted_flg'] == 1){ ?>
                  <p>削除済</p>
                <?php } ?>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
      <?php if($row['deleted_flg'] == 0){ ?>
        <input class = "button" type = "button" onclick = "delete_user();" value = "ユーザー削除">
        <form action = "delete_user.php" name = "user_data" method = "post">
          <input type = "hidden" name = "user_no" value = "<?php echo $row['user_no']; ?>">
          <input type = "hidden" name = "admin_flg" value = "<?php echo $row['admin_flg']; ?>">
        </form>
      <?php }else{ ?>
        <input class = "button" type = "button" onclick = "restoration_user();" value = "ユーザー復活">
        <form action = "user_restoration.php" name = "user_restoration_data" method = "post">
          <input type = "hidden" name = "user_no" value = "<?php echo $row['user_no']; ?>">
          <input type = "hidden" name = "mail" value = "<?php echo $row['mail']; ?>">
          <input type = "hidden" name = "name" value = "<?php echo $row['name']; ?>">
          <input type = "hidden" name = "password" value = "<?php echo $row['password']; ?>">
        </form>
      <?php } ?>
    <?php } ?>
  </div>
</main>
<?php
//ユーザー情報の取得
function get_user_data($pdo){
  $user_no = $_POST['user_no'];

  $tsql = "select * from user_info where user_no = ?";
  $result = $pdo->prepare($tsql);
  $param = array($user_no);
  $result->execute($param);
  $user_info = $result -> fetchAll(PDO::FETCH_ASSOC);
  
  return $user_info;
}
?>
<script type="text/javascript">
  function delete_user(){
    if(window.confirm('このユーザーを削除します。よろしいですか？')){
      var f = document.forms["user_data"];
      f.submit();
    }
  }

  function restoration_user(){
    if(window.confirm('このユーザーを復活します。よろしいですか？')){
      var f = document.forms["user_restoration_data"];
      f.submit();
    }
  }

//編集、保存ボタンの制御
$(function(){
  $('#save').hide();
  $('#cancel').hide();
  $('#authority_edit').hide();
  $('#edit').on('click', function(){
    $('#edit').hide();
    $('#authority_show').hide();
    $('#save').show();
    $('#cancel').show();
    $('#authority_edit').show();
  });
  $('#cancel, #save').on('click', function(){
    $('#edit').show();
    $('#authority_show').show();
    $('#save').hide();
    $('#cancel').hide();
    $('#authority_edit').hide();
  });
});
</script>
<?php include('footer.php'); ?>