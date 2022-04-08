<?php if($table == 'denryoku_oya_apply'){ ?>
  <table class = "apply_detail_table">
    <thead>
      <tr>
        <th class = "col" style = "width:100px;">区分</th>
        <th class = "col" style = "width:200px;">書込みサイト</th>
        <th class = "col" style = "width:200px;">品目番号</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($apply_detail as $row){ ?>
        <tr>
          <td><?php echo $row['category'] ?></td>
          <td><?php echo $row['site'] ?></td>
          <td><?php echo $row['item_number'] ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
<?php }elseif($table == 'kurorom_apply'){ ?>
  <table class = "apply_detail_table">
    <thead>
      <tr>
        <th class = "col" style = "width:100px;">区分</th>
        <th class = "col" style = "width:100px;">書込みサイト</th>
        <th class = "col" style = "width:100px;">品目番号</th>
        <th class = "col" style = "width:100px;">書込み後サイト</th>
        <th class = "col" style = "width:100px;">新品目番号</th>
        <th class = "col" style = "width:100px;">品目名称</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($apply_detail as $row){ ?>
        <tr>
          <td><?php echo $row['category'] ?></td>
          <td><?php echo $row['site'] ?></td>
          <td><?php echo $row['item_number'] ?></td>
          <td><?php echo $row['later_site'] ?></td>
          <td><?php echo $row['new_item_number'] ?></td>
          <td><?php echo $row['item_name'] ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
<?php }elseif($table == 'redmine_apply'){ ?>
  <table class = "apply_detail_table">
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
      <?php foreach($apply_detail as $row){ ?>
        <tr>
          <td><?php echo $row['category'] ?></td>
          <td><?php echo $row['family_name'] ?></td>
          <td><?php echo $row['first_name'] ?></td>
          <td><?php echo $row['id'] ?></td>
          <td><?php echo $row['note'] ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
<?php } ?>

