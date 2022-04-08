<?php
  require "common_func.php";
  //DBとの接続
  list($pdo, $err_message, $bool) = connection_db_2();

  $search_str = $_POST["search_str"];
  $search_str = '%'.$search_str.'%';
  $tsql = "SELECT * FROM user_info WHERE mail LIKE ? OR name LIKE ?;";
  $stmt = $pdo -> prepare($tsql);
  $params = array($search_str, $search_str);
  $stmt -> execute($params);

  $result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
  if($result != NULL){
    echo json_encode($result);
  }
?>